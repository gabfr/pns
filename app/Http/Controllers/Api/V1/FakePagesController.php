<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\FakePages\CreateFakePageRequest;
use App\Http\Requests\FakePages\UpdateFakePageRequest;
use App\Repositories\Contracts\FakePagesRepositoryContract;

use App\FakePage;
use App\User;
use App\Application;

use App\Http\Controllers\Api\ApiBaseController;

class FakePagesController extends ApiBaseController
{
	protected $fakePagesRepo;

    public function __construct(FakePagesRepositoryContract $fakePagesRepo)
    {
    	$this->fakePagesRepo = $fakePagesRepo;
    }

    public function index(Request $request, Application $application = null)
    {
    	if ( ($perPage = $request->get('per_page', 20)) > 100 ) {
    		$perPage = 100;
    	}

        if (!$application->exists)
            $application = null;

    	$user = app('Dingo\Api\Auth\Auth')->user();

    	return $this->response->paginator(
    		$this->fakePagesRepo->all($user, $application, $perPage), 
    		$this->getBasicTransformer()
		);
    }

    public function show(FakePage $fakePage, Request $request)
    {
    	return $this->response->item($fakePage, $this->getBasicTransformer());
    }

    public function store(CreateFakePageRequest $request)
    {
    	$data = $request->only('name', 'content_url', 'application_id', 'is_active');
    	$fakePage = $this->fakePagesRepo->store($data);

    	return $this->response->item($fakePage, $this->getBasicTransformer());
    }

    public function update(FakePage $fakePage, UpdateFakePageRequest $request)
    {
    	$data = $request->only('name', 'content_url', 'application_id', 'is_active');

    	$fakePage = $this->fakePagesRepo->update($fakePage, $data);

    	return $this->response->item($fakePage, $this->getBasicTransformer());
    }

    public function delete(FakePage $fakePage, Request $request)
    {
    	$this->fakePagesRepo->delete($fakePage);

    	return $this->response->noContent();
    }
}
