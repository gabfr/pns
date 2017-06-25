<?php

namespace App\Http\Controllers\Api\V1;

use App\CloudObject;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Requests\CloudObjects\CreateCloudObjectRequest;
use App\Http\Requests\CloudObjects\UpdateCloudObjectRequest;
use App\Repositories\Contracts\CloudObjectRepositoryContract;
use Illuminate\Http\UploadedFile;
use File;

class CloudObjectsController extends ApiBaseController
{
    const TARGET_PATH = 'cloud_objects';

    protected $cloudObjectRepo;

    public function __construct(
        CloudObjectRepositoryContract $cloudObjectRepo
    ){
        $this->cloudObjectRepo = $cloudObjectRepo;

        $this->checkPath();
    }

    private function getPath($filename = null)
    {
        if ($filename instanceof CloudObject) {
            $filename = $filename->filename;
        }

        $filename = !is_null($filename) ? "/" . ltrim($filename, '/') : '';

        return storage_path(self::TARGET_PATH . $filename);
    }

    private function checkPath()
    {
        @mkdir($this->getPath(), 0755, true);
    }

    private function getNewFilename(UploadedFile $file)
    {
        $extension = mb_strtolower($file->guessExtension());
        return str_random(30) . ".{$extension}";
    }

    public function store(CreateCloudObjectRequest $request)
    {
        $file = $request->file('object');
        $data = $request->only('name','slug', 'is_active');

        if ($file == null || ($file != null && !$file->isValid())) {
            return $this->response->errorBadRequest('O arquivo enviado não é válido!');
        }

        $originalFilename = $file->getClientOriginalName();
        $filename = $this->getNewFilename($file);

        $cloudObject = $this->cloudObjectRepo->store($data, $filename, $originalFilename);

        $file->move($this->getPath(), $cloudObject->filename);
        
        return $this->response->item(
            $cloudObject,$this->getBasicTransformer()
        );
    }

    public function update(CloudObject $cloudObject, UpdateCloudObjectRequest $request)
    {
        $file = $request->file('object');
        $data = $request->only('name','slug', 'is_active');
        \Log::info('[CloudObjectsController] Data form: ' . print_r($_POST, true));

        $originalFilename = null;
        $filename = null;

        if ($file != null) {
            if (!$file->isValid()) {
                return $this->response->errorBadRequest('O arquivo enviado não é válido!');
            }
            $originalFilename = $file->getClientOriginalName();
            $filename = $this->getNewFilename($file);
        }

        $cloudObject = $this->cloudObjectRepo->update($cloudObject, $data, $filename, $originalFilename);

        if ($file != null) {
            $file->move($this->getPath(), $cloudObject->filename);    
        }

        return $this->response->item(
            $cloudObject,$this->getBasicTransformer()
        );
    }

    public function delete(CloudObject $cloudObject, Request $request)
    {
        if (! File::exists($file = $this->getPath($cloudObject))) {
            return $this->response->errorNotFound(
                sprintf("Arquivo %s não foi encontrado", $cloudObject->filename)
            );
        }

        $this->cloudObjectRepo->delete($cloudObject);

        File::delete($file);

        return $this->response->noContent();
    }

    public function index(Request $request)
    {
        if( ( $perPage = $request->get('per_page',20) ) > 100 ) {
            $perPage = 100;
        }

        $cloudObjects = $this->cloudObjectRepo->all($perPage);

        return $this->response->paginator(
            $cloudObjects, $this->getBasicTransformer()
        );
    }

    public function show(CloudObject $cloudObject)
    {
        return $this->response->item(
            $cloudObject, $this->getBasicTransformer()
        );
    }

    public function download(CloudObject $cloudObject)
    {
        if (! File::exists($file = $this->getPath($cloudObject))) {
            return $this->response->errorNotFound(
                sprintf("Arquivo %s não foi encontrado", $cloudObject->filename)
            );
        }

        return response()->download(
            $file, $cloudObject->filename
        );
    }


}
