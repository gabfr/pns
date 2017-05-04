<?php

namespace App\Repositories;

use App\Repositories\Contracts\DeviceRepositoryContract;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Device;
use App\Application;
use App\User;

class DeviceRepository implements DeviceRepositoryContract
{
    public function all(Application $application, $perPage = 10) 
    {
        return $application->devices()->paginate($perPage);
    }

    public function create(Application $application, array $data = array())
    {
        $data = array_filter($data, function($item) {
            return !is_null($item);
        });
        $data['application_id'] = $application->getKey();

        $notification = Device::create($data);

        return $this->findById($notification);
    }

    public function update(Device $device, array $data = array())
    {
        $data = array_filter($data, function($item) {
            return !is_null($item);
        });

        if (count($data) > 0) {
            $device->update($data);
        }

        return $this->findById($notification);
    }

    public function findById($deviceId)
    {
        if ($deviceId instanceof Device) {
            $deviceId = $deviceId->getKey();
        }

        return Device::where('id', $deviceId)->first();
    }


}