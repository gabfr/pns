<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Mail\Mailer;
use App\Repositories\ApplicationRepository;
use App\Notification;
use App\Device;
use App\NotificationDelivery;
use Log;
use PushNotification;

class DispatchNotifications extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $platform;
    private $application;
    private $notification;
    private $notificationDeliveries;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($platform, Notification $notification, array $notificationDeliveries)
    {
        $this->platform = $platform;
        $this->notification = $notification;
        $this->application = $this->notification->application()->first();
        $this->notificationDeliveries = $notificationDeliveries;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('DispatchNotifications::handle()');

        if ($this->notificationDeliveries == null || !is_array($this->notificationDeliveries)) {
            Log::error('Não é possível disparar notificação. ', [$this->notificationDeliveries]);
            return;
        }

        $pns = null;
        $message = null;
        if ($this->platform == 'android') {
            $pns = PushNotification::setService('fcm')->setConfig([
                'priority' => 'normal',
                'dry_run' => ($this->application->gcm_mode == "sandbox" ? true : false),
                'apiKey' => $this->application->gcm_api_key
            ]);
            $message = [
                'notification' => [
                    'title' => $this->notification->title,
                    'body' => $this->notification->alert_message,
                    'sound' => 'default'
                ],
                'data' => [
                    'url' => $this->notification->url
                ]
            ];
        } else {
            $configPayload = [
                'certificate' => ($this->application->apns_mode == "production" ? 
                    ApplicationRepository::getCertificatePath($this->application->apns_certificate_production) : 
                    ApplicationRepository::getCertificatePath($this->application->apns_certificate_sandbox)),
                'dry_run' => ($this->application->apns_mode == "sandbox" ? true : false)
            ];
            if (!is_null($this->application->apns_certificate_password)) {
                $configPayload['passPhrase'] = $this->application->apns_certificate_password;
            }
            $pns = PushNotification::setService('apn')->setConfig($configPayload);
            $message = [
                'aps' => [
                    'alert' => [
                        'body' => $this->notification->alert_message,
                        'title' => $this->notification->title
                    ],
                    'sound' => "default"
                ],
                'extraPayload' => [
                    'url' => $this->notification->url
                ]
            ];
        }

        $pns->setMessage($message);

        $deliveriesIds = [];
        $devices = [];
        foreach ($this->notificationDeliveries as $notificationDelivery) {
            $deliveriesIds[] = $notificationDelivery->getKey();
            $device = $notificationDelivery->device()->first();
            $devices[] = $device->device_token;
        }

        $pns->setDevicesToken($devices);

        $responses = null;

        try {
            $responses = $pns->send()->getFeedback();
        } catch(Exception $e) {
            Log::error('Error trying to connect to send notifications: ' . print_r($e, true));
        }
        
        Log::info('Respostas dos enviados: ' . print_r($responses, true));

        $allDeliveries = NotificationDelivery::whereIn('id', $deliveriesIds)->get();
        foreach ($allDeliveries as $delivery)
            $delivery->update(['status' => NotificationDelivery::ENVIADO]);

        if ($responses->failure > 0) {
            foreach ($responses->tokenFailList as $deviceToken) {
                $device = Device::where('application_id', $this->notification->application_id)
                    ->where('device_token', $deviceToken)
                    ->first();
                $notificationDelivery = NotificationDelivery::whereIn('id', $deliveriesIds)
                    ->where('notification_id', $this->notification->getKey())
                    ->where('device_id', $device->getKey())
                    ->first();
                if ($notificationDelivery) {
                    $notificationDelivery->update(['status' => NotificationDelivery::ERRO]);
                }
                if ($device) {
                    $device->update(['status' => false]);
                }
            }
        }
    }
}
