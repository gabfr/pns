<?php

namespace App\Repositories;

use App\Repositories\Contracts\NotificationRepositoryContract;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Notification;
use App\Application;
use App\User;

class NotificationRepository implements NotificationRepositoryContract
{
    public function all(Application $application, $perPage = 10) 
    {
        return $application->notifications()->paginate($perPage);
    }

    public function create(Application $application, User $user, array $data = array())
    {
        $data = array_filter($data, function($item) {
            return !is_null($item);
        });
        $data['application_id'] = $application->getKey();
        $data['created_by'] = $user->getKey();

        $notification = Notification::create($data);

        return $this->findById($notification);
    }

    public function update(Notification $notification, array $data = array())
    {
        $data = array_filter($data, function($item) {
            return !is_null($item);
        });

        if (count($data) > 0) {
            $notification->update($data);
        }

        return $this->findById($notification);
    }

    public function delete(Notification $notification)
    {
        $notification->delete();
    }

    public function findById($notificationId)
    {
        if ($notificationId instanceof Notification) {
            $notificationId = $notificationId->getKey();
        }

        return Notification::where('id', $notificationId)->first();
    }


}