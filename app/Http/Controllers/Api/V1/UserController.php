<?php

namespace App\Http\Controllers\Api\V1;

use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Controllers\Api\ApiBaseController;
use App\Repositories\Contracts\UserRepositoryContract;

class UserController extends ApiBaseController
{

    /**
     * User repository instance
     *
     * @var App\Repositories\UserRepository
     */
    protected $userRepo;

    /**
     * User controller, list, show, create and remove users
     *
     * @param UserRepositoryContract $userRepo user repository
     */
    public function __construct(
        UserRepositoryContract $userRepo
    ){
        $this->userRepo = $userRepo;
    }


    /**
     * Get current user information
     *
     * @param  Request $request
     * @return Dingo\Api\Http\Response
     */
    public function me(Request $request)
    {
        $user = $request->user();

        $user->load(['city']);

        return $this->response->item($user, $this->getBasicTransformer());
    }

    /**
     * Create a new user
     *
     * @param  CreateUserRequest $request
     * @return Dingo\Api\Http\Response
     */
    public function store(CreateUserRequest $request)
    {

        $data = $request->only('name','email','password', 'is_super' );

        $user = $this->userRepo->store($data);

        return $this->response->item(
            $user,$this->getBasicTransformer()
        );
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        $data = $request->only('name', 'email', 'password', 'is_super');

        $user = $this->userRepo->update($user, $data);

        return $this->response->item(
            $user, $this->getBasicTransformer()
        );
    }

    public function delete(User $user, Request $request)
    {
        $this->userRepo->delete($user);

        return $this->response->noContent();
    }

    /**
     * get a list of users. Avaliable only for staff members
     *
     * @param  Request $request
     * @return Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        if( ( $perPage = $request->get('per_page',20) ) > 100 ) {
            $perPage = 100;
        }

        $users = $this->userRepo->all($perPage);

        return $this->response->paginator(
            $users, $this->getBasicTransformer()
        );
    }

    /**
     * show a specific user by id
     *
     * @param  User
     * @return Dingo\Api\Http\Response
     */
    public function show(User $user)
    {
        return $this->response->item(
            $user, $this->getBasicTransformer()
        );
    }


}
