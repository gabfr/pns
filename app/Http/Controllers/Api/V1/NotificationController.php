<?php

namespace App\Http\Controllers\Api\V1;

use App\Application;
use App\Notification;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\Users\CreateUserRequest;

use App\Http\Requests\Application\CreateNotificationRequest;
use App\Http\Requests\Application\DeleteNotificationRequest;
use App\Http\Requests\Application\UpdateNotificationRequest;

use App\Http\Controllers\Api\ApiBaseController;
use App\Repositories\Contracts\NotificationRepositoryContract;

class NotificationController extends ApiBaseController
{

    protected $notificationRepo;

    public function __construct(
        NotificationRepositoryContract $notificationRepo
    ){
        $this->notificationRepo = $notificationRepo;
    }

    public function index(Application $application, Request $request)
    {
        if( ( $perPage = $request->get('per_page',20) ) > 100 ) {
            $perPage = 100;
        }

        $notifications = $this->notificationRepo->all($application, $perPage);

        return $this->response->paginator(
            $notifications, $this->getBasicTransformer()
        );
    }

    public function show(Application $application, Notification $notification, Request $request)
    {
        return $this->response->item($notification, $this->getBasicTransformer());
    }

    public function create(Application $application, CreateNotificationRequest $request)
    {
        $data = $request->only('title', 'alert_message', 'icon');

        $notification = $this->notificationRepo->create($application, $request->user(), $data);

        return $this->response->item($notification, $this->getBasicTransformer());
    }

    public function update(Application $application, Notification $notification, UpdateNotificationRequest $request)
    {
        $data = $request->only('title', 'alert_message', 'icon');

        $notification = $this->notificationRepo->update($notification, $data);

        return $this->response->item($notification, $this->getBasicTransformer());
    }

    public function delete(Application $application, Notification $notification, DeleteNotificationRequest $request)
    {
        $this->notificationRepo->delete($notification);

        return $this->response->noContent();
    }

    public function deliveries(Application $application, Notification $notification, Request $request)
    {
        return $this->response->collection($notification->notification_deliveries()->get(), $this->getBasicTransformer());
    }

}
