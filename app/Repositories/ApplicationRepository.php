<?php

namespace App\Repositories;

use App\Repositories\Contracts\ApplicationRepositoryContract;
use App\Application;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Log;

class ApplicationRepository implements ApplicationRepositoryContract
{
    const TARGET_PATH = 'certificates';

    public function __construct()
    {
        $this->checkPath();
    }

    public function all($perPage = 10) 
    {
        return Application::paginate($perPage);
    }

    public function create(array $data = array())
    {
        $data = array_filter($data, function($item) {
            return !is_null($item);
        });

        $application = Application::create($data);

        return $this->findById($application);
    }

    public function update(Application $application, array $data = array())
    {
        $data = array_filter($data, function($item) {
            return !is_null($item);
        });

        if (count($data) > 0) {
            $application->update($data);
        }

        return $this->findById($application);
    }

    public function updateApns(Application $application, array $data = array())
    {
        $data = array_filter($data, function($item) {
            return !is_null($item);
        });

        foreach ($data as $partFileName => $dataItem) {
            Log::info("[{$partFileName}] will test if it is a file... ");
            if ($dataItem instanceof UploadedFile && $dataItem != null && $dataItem->isValid()) {
                Log::info("[{$partFileName}] it is file! ");
                $newFileName = "{$application->slug}_{$partFileName}";
                $dataItem->move($this->getPath(), $newFileName);
                $data[$partFileName] = $newFileName;
            } else {
                Log::info("[{$partFileName}] it is NOT a file! ");
            }
        }

        if (count($data) > 0) {
            $application->update($data);
        }

        return $this->findById($application);
    }

    public function findById($applicationId)
    {
        if ($applicationId instanceof Application) {
            $applicationId = $applicationId->getKey();
        }

        return Application::where('id', $applicationId)->first();
    }

    private function getPath($filename = null)
    {
        return self::getCertificatePath($filename);
    }

    public static function getCertificatePath($filename = null) 
    {
        $filename = !is_null($filename) ? "/" . ltrim($filename, '/') : '';

        return storage_path(ApplicationRepository::TARGET_PATH . $filename);
    }

    private function checkPath()
    {
        @mkdir($this->getPath(), 0755, true);
    }


}