<?php

namespace App\Repositories;

use App\Repositories\Contracts\UserRepositoryContract;
use App\User;
use Laravel\Socialite\Contracts\User as SocialUser;
use App\UserIntegration;

class UserRepository implements UserRepositoryContract
{

    /**
     * get a list of users with pagination
     *
     * @param  integer $perPage results per page
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function all($perPage = 20)
    {
        return User::orderBy('name')->paginate($perPage);
    }

    /**
     * Store a new user, password will be encrypted
     * inside user model (bootable events).
     *
     * @param  array  $data user information
     * @return App\User
     */
    public function store(array $data = array())
    {

        $data = array_filter($data,function($item){
            return !is_null($item);
        });

        $user = User::create($data);

        return $this->findById($user);
    }

    /**
     * Update user info
     *
     * @param  User   $user
     * @param  array  $data
     * @return App\User
     */
    public function update(User $user,array $data = array())
    {
        $data = array_filter($data);

        if(isset($data['password'])){
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        return $this->findById($user);
    }

    /**
     * get user by id
     *
     * @param  mixed    $id user id
     * @return App\User
     */
    public function findById($id)
    {
        if($id instanceof User){
            $id = $id->getKey();
        }

        return User::with(['city'])->where('id',$id)->first();
    }


    /**
     * Create a new user social integration
     *
     * @param  String     $provider   provider name (ex: Facebook)
     * @param  User       $user
     * @param  SocialUser $socialUser
     * @return App\User
     */
    public function createSocialIntegration($provider, User $user, SocialUser $socialUser)
    {
        $user->socialIntegrations()->create([
            'remote_id' => $socialUser->getId(),
            'provider'  => $provider
        ]);
        return $user;
    }

    /**
     * Check if user has a social authentication
     *
     * @param  SocialUser $socialUser Social User Credentials
     * @return boolean
     */
    public function hasSocialIntegration(SocialUser $socialUser)
    {
        return UserIntegration::where('remote_id', $socialUser->getId())->exists();
    }

    /**
     * Check if user exists
     *
     * @param  String $email
     * @return bool
     */
    public function userExistsByEmail($email)
    {
        return User::select('id')->where('email',$email)->exists();
    }

    /**
     * Get user information using social information
     * @param  SocialUser $socialUser
     * @return User|null
     */
    public function getUserBySocialKeys(SocialUser $socialUser)
    {
        $integration = UserIntegration::with(['user'])
                        ->where('remote_id', $socialUser->getId())
                        ->first();

        return $integration != null ? $integration->user : null;
    }


}