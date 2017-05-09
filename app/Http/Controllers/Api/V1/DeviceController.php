<?php

namespace App\Http\Controllers\Api\V1;

use App\Application;
use App\Device;
use App\Http\Requests;
use Illuminate\Http\Request;

use App\Http\Requests\Device\CreateDeviceRequest;
use App\Http\Requests\Device\DeleteDeviceRequest;
use App\Http\Requests\Device\UpdateDeviceRequest;

use App\Http\Controllers\Api\ApiBaseController;
use App\Repositories\Contracts\DeviceRepositoryContract;

class DeviceController extends ApiBaseController
{

    protected $deviceRepo;

    public function __construct(
        DeviceRepositoryContract $deviceRepo
    ){
        $this->deviceRepo = $deviceRepo;
    }

    public function index(Application $application, Request $request)
    {
        if( ( $perPage = $request->get('per_page',20) ) > 100 ) {
            $perPage = 100;
        }

        $devices = $this->deviceRepo->all($application, $perPage);

        return $this->response->paginator(
            $devices, $this->getBasicTransformer()
        );
    }

    public function show(Application $application, Device $device, Request $request)
    {
        return $this->response->item($device, $this->getBasicTransformer());
    }

    public function create(Application $application, CreateDeviceRequest $request)
    {
        $data = $request->only('platform', 'device_token', 'device_id');

        $device = $this->deviceRepo->create($application, $data);

        return $this->response->item($device, $this->getBasicTransformer());
    }

    public function update(Application $application, Device $device, UpdateDeviceRequest $request)
    {
        $data = $request->only('platform', 'device_token', 'device_id');

        $device = $this->deviceRepo->update($device, $data);

        return $this->response->item($device, $this->getBasicTransformer());
    }

    public function delete(Application $application, Device $device, DeleteDeviceRequest $request)
    {
        $this->deviceRepo->delete($device);

        return $this->response->noContent();
    }

    public function deliveries(Application $application, Device $device, Request $request)
    {
        return $this->response->collection($device->notification_deliveries()->get(), $this->getBasicTransformer());
    }

}
