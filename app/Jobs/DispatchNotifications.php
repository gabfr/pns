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
        if ($this->notificationDeliveries == null || !is_array($this->notificationDeliveries)) {
            Log::error('Não é possível disparar notificação. ', [$this->notificationDeliveries]);
            return;
        }

        $pns = null;
        $message = null;
        if ($this->platform == 'android') {
            $pns = PushNotification::app([
                'environment' => $this->application->gcm_mode,
                'apiKey' => $this->application->gcm_api_key,
                'service' => 'gcm'
            ]);
            $message = PushNotification::Message($this->notification->title, [
                'alert' => $this->notification->alert_message,
                'title' => $this->notification->title,
                'icon' => $this->notification->icon,
                'url' => $this->notification->url
            ]);
        } else {
            $pns = PushNotification::app([
                'environment' => $this->application->apns_mode,
                'certificate' => ($this->application->apns_mode == "production" ? 
                    ApplicationRepository::getPath($this->application->apns_certificate_production) : 
                    ApplicationRepository::getPath($this->application->apns_certificate_sandbox)),
                'passPhrase' => $this->application->apns_certificate_password,
                'service' => 'apns'
            ]);
            $message = PushNotification::Message($this->notification->title, [
                'aps' => [
                    'alert' => [
                        'body' => $this->notification->alert_message,
                        'title' => $this->notification->title
                    ],
                    'badge' => 1,
                    'sound' => "sound.caf"
                ],
                'url' => $this->notification->url
            ]);
        }
        $pns->adapter->setAdapterParameters(['sslverifypeer' => false]);

        $devices = [];
        foreach ($this->notificationDeliveries as $notificationDelivery) {
            $device = $notificationDelivery->device()->first();
            $devices[] = PushNotification::Device($device->device_token);
        }

        $collection = $pns->to(PushNotification::DeviceCollection($devices))->send($message);
        $responses = [];
        foreach ($collection as $push) {
            $responses[] = $push->getAdapter()->getResponse();
        }
        
        Log::info('Respostas dos enviados: ' . print_r($responses, true));
    }
}
