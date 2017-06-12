<?php

namespace App\Repositories;

use App\Repositories\Contracts\FakePagesRepositoryContract;
use App\FakePage;

class FakePagesRepository implements FakePagesRepositoryContract
{
    public function all($user, $perPage = 20)
    {
        $fakePages = null;
        if (!$user) {
            $fakePages = FakePage::onlyActive()->orderBy('name');
        } else {
            $fakePages = FakePage::orderBy('name');
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