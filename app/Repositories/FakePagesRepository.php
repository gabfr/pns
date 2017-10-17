<?php

namespace App\Repositories;

use App\Repositories\Contracts\FakePagesRepositoryContract;
use App\FakePage;
use App\User;
use App\Application;

class FakePagesRepository implements FakePagesRepositoryContract
{
    public function all($user, Application $application = null, $perPage = 20)
    {
        $fakePages = null;

        if (!$user) {
            $fakePages = FakePage::onlyActive()->orderBy('name');

            if (!is_null($application)) 
                $fakePages->where('application_id', $application->getKey());
            else {
                $application = Application::where('id', env('PNS_DEFAULT_APP', -1))
                    ->orWhere('slug', env('PNS_DEFAULT_APP', -1))->first();
                    
                $fakePages->whereNull('application_id');
                if ($application) 
                    $fakePages->orWhere('application_id', $application->getKey());
            }
        } else {
            $fakePages = FakePage::orderBy('name')->with(['application']);
        }

        return $fakePages->paginate($perPage);
    }

    public function store(array $data = array())
    {

        $data = array_filter($data,function($item){
            return !is_null($item);
        });

        $fakePage = FakePage::create($data);

        return $this->findById($fakePage);
    }

    public function update(FakePage $fakePage, array $data = array())
    {
        $data = array_filter($data, function($item) {
            return !is_null($item);
        });

        $fakePage->update($data);

        return $this->findById($fakePage);
    }

    public function delete(FakePage $fakePage)
    {
        return $fakePage->delete();
    }

    public function findById($id)
    {
        if($id instanceof FakePage){
            $id = $id->getKey();
        }

        return FakePage::where('id',$id)->first();
    }

}