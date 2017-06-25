<?php

namespace App\Repositories;

use App\Repositories\Contracts\CloudObjectRepositoryContract;
use App\CloudObject;

class CloudObjectRepository implements CloudObjectRepositoryContract
{
    public function all($perPage = 20)
    {
        return CloudObject::orderBy('created_at', 'DESC')->paginate($perPage);
    }

    public function store(array $data, $filename, $originalFilename)
    {
        $data = array_filter($data,function($item){
            return !is_null($item);
        });

        $data = array_merge(
            array_filter(
                ['filename' => $filename, 'original_filename' => $originalFilename], 
                function($item) { return !is_null($item); }
            ), 
            $data
        );

        $cloudObject = CloudObject::create($data);

        return $this->findById($cloudObject);
    }

    public function update(CloudObject $cloudObject, array $data, $filename, $originalFilename)
    {
        $data = array_filter($data, function($item) {
            return !is_null($item);
        });

        \Log::info('[CloudObject] Will update: ' . print_r($data, true));

        $data = array_merge(
            array_filter(
                ['filename' => $filename, 'original_filename' => $originalFilename], 
                function($item) { return !is_null($item); }
            ), 
            $data
        );

        \Log::info('[CloudObject] Will update: ' . print_r($data, true));

        $cloudObject->update($data);

        return $this->findById($cloudObject);
    }

    public function delete(CloudObject $cloudObject)
    {
        return $cloudObject->delete();
    }

    public function findById($id)
    {
        if($id instanceof CloudObject){
            $id = $id->getKey();
        }

        return CloudObject::where('id',$id)->first();
    }
}