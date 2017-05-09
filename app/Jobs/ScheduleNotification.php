<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notification;
use App\Device;
use App\NotificationDelivery;
use Log;

class ScheduleNotification extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $notification;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Notification $notification)
    {
        Log::info('ScheduleNotification::__construct(Notification)');
        $this->notification = $notification;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('ScheduleNotification::handle()');
        $chunkSize = 25;

        if ($this->notification == null) {
            Log::error('Não é possível agendar disparos de notificações. ', [$this->notification]);
            return;
        }

        $notification = $this->notification;
        $application = $this->notification->application()->first();

        Device::where('application_id', $application->getKey())
            ->where('platform', 'android')
            ->where('status', true)
            ->chunk($chunkSize, function($chunkOfDevices) use ($application, $notification) {
                Log::info('Will schedule for Android... ');
                $notificationDeliveries = [];
                foreach ($chunkOfDevices as $device) {
                    Log::info("Android - {$device->device_token}... ");
                    $notificationDeliveries[] = NotificationDelivery::create([
                        'device_id' => $device->getKey(),
                        'notification_id' => $notification->getKey(),
                        'status' => NotificationDelivery::AGUARDANDO
                    ]);
                }
                dispatch(new DispatchNotifications("android", $this->notification, $notificationDeliveries));
            });

        Device::where('application_id', $application->getKey())
            ->where('platform', 'ios')
            ->where('status', true)
            ->chunk($chunkSize, function($chunkOfDevices) use ($application, $notification) {
                Log::info('Will schedule for iOS... ');
                $notificationDeliveries = [];
                foreach ($chunkOfDevices as $device) {
                    Log::info("iOS - {$device->device_token}... ");
                    $notificationDeliveries[] = NotificationDelivery::create([
                        'device_id' => $device->getKey(),
                        'notification_id' => $notification->getKey(),
                        'status' => NotificationDelivery::AGUARDANDO
                    ]);
                }
                dispatch(new DispatchNotifications("ios", $this->notification, $notificationDeliveries));
            });
    }

    public function failed(Exception $e)
    {
        Log::info('ScheduleNotification failed: ' . print_r($e, true));
    }
}
