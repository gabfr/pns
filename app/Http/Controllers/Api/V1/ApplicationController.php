<?php

namespace App\Http\Controllers\Api\V1;

use App\Application;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\Users\CreateUserRequest;

use App\Http\Requests\Application\ApnsUpdateApplicationRequest;
use App\Http\Requests\Application\CreateApplicationRequest;
use App\Http\Requests\Application\DeleteApplicationRequest;
use App\Http\Requests\Application\GcmUpdateApplicationRequest;
use App\Http\Requests\Application\UpdateApplicationRequest;

use App\Http\Controllers\Api\ApiBaseController;
use App\Repositories\Contracts\ApplicationRepositoryContract;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ApplicationController extends ApiBaseController
{

    protected $appRepo;

    public function __construct(
        ApplicationRepositoryContract $appRepo
    ){
        $this->appRepo = $appRepo;
    }

    public function index(Request $request)
    {
        if( ( $perPage = $request->get('per_page',20) ) > 100 ) {
            $perPage = 100;
        }

        $apps = $this->appRepo->all($perPage);

        return $this->response->paginator(
            $apps, $this->getBasicTransformer()
        );
    }

    public function show(Application $application, Request $request)
    {
        return $this->response->item($application, $this->getBasicTransformer());
    }

    public function create(CreateApplicationRequest $request)
    {
        $data = $request->only('slug', 'name');

        $application = $this->appRepo->create($data);

        return $this->response->item($application, $this->getBasicTransformer());
    }

    public function update(Application $application, UpdateApplicationRequest $request)
    {
        $data = $request->only('slug', 'name');

        $application = $this->appRepo->update($application, $data);

        return $this->response->item($application, $this->getBasicTransformer());
    }

    public function delete(Application $application, DeleteApplicationRequest $request)
    {
        $this->appRepo->delete($application);

        return $this->response->noContent();
    }

    public function updateGcm(Application $application, GcmUpdateApplicationRequest $request)
    {
        $data = $request->only('gcm_mode', 'gcm_api_key');

        $application = $this->appRepo->update($application, $data);

        return $this->response->item($application, $this->getBasicTransformer());
    }

    public function updateApns(Application $application, ApnsUpdateApplicationRequest $request)
    {
        $data = [
            'apns_certificate_sandbox' => $request->file('apns_certificate_sandbox'),
            'apns_certificate_production' => $request->file('apns_certificate_production'),
            'apns_root_certificate' => $request->file('apns_root_certificate'),
            'apns_certificate_password' => $request->get('apns_certificate_password', null),
            'apns_mode' => $request->get('apns_mode', null)
        ];
        \Log::info('Request data: ' . print_r($_FILES, true));
        foreach ($data as $partFileName => $dataItem) {
            if ($dataItem instanceof UploadedFile && $dataItem != null && !$dataItem->isValid()) {
                return $this->response->errorBadRequest(
                    sprintf("Certificado %s não é válido", $partFileName)
                );
            }
        }

        $application = $this->appRepo->updateApns($application, $data);

        return $this->response->item($application, $this->getBasicTransformer());
    }


}
