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

    private static $chunkSize = 25;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->notification == null) {
            Log::error('Não é possível agendar disparos de notificações. ', [$this->notification]);
            return;
        }

        $notification = $this->notification;
        $application = $this->notification->application()->first();
        $devices = $application->devices();
        $devices->where('platform', 'android')->chunk(self::$chunkSize, function($chunkOfDevices) use ($application, $notification) {
            $notificationDeliveries = [];
            foreach ($chunkOfDevices as $device) {
                $notificationDeliveries[] = NotificationDelivery::create([
                    'device_id' => $device->getKey(),
                    'notification_id' => $notification->getKey(),
                    'status' => NotificationDelivery::AGUARDANDO
                ]);
            }
            dispatch(new DispatchNotifications("android", $notification, $notificationDeliveries));
        });

        $devices->where('platform', 'ios')->chunk(self::$chunkSize, function($chunkOfDevices) use ($application, $notification) {
            $notificationDeliveries = [];
            foreach ($chunkOfDevices as $device) {
                $notificationDeliveries[] = NotificationDelivery::create([
                    'device_id' => $device->getKey(),
                    'notification_id' => $notification->getKey(),
                    'status' => NotificationDelivery::AGUARDANDO
                ]);
            }
            dispatch(new DispatchNotifications("ios", $notification, $notificationDeliveries));
        });
    }
}
