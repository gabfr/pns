<?php

namespace App\Http\Controllers\Api\V1;

use App\Repositories\Contracts\UserRepositoryContract;
use Laravel\Socialite\Contracts\User as SocialUser;
use App\Http\Controllers\Api\ApiBaseController;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use App\Http\Requests;
use Socialite;
use JWTAuth;


class SocialAuthentication extends ApiBaseController
{

    protected $userRepo;

    public function __construct(
        UserRepositoryContract $userRepo
    )
    {
        $this->userRepo = $userRepo;
    }

    public function getUserFromToken($driver,Request $request)
    {
        if( ! ( $token = $request->get('token') )){
            return $this->response->error('Token inválido',400);
        }

        $user = Socialite::driver($driver)->userFromToken($token);

        return $this->prepareSocialUser($user, $driver);
    }

    public function requestUrl($driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    /**
     * Social integration callback
     *
     * @param  String   $driver  Driver name
     * @param  Request  $request
     * @return Dingo\Api\Http\Response
     */
    public function callback($driver,Request $request)
    {

        $socialUser = Socialite::driver($driver)->user();

        return $this->prepareSocialUser($socialUser);
    }

    protected function prepareSocialUser(SocialUser $socialUser, $driver)
    {
        // Check if user has social integration
        if( ! $this->userRepo->hasSocialIntegration($socialUser) ) {

            // Create a local user
            if( $this->createLocalUser($socialUser, $driver) === false ){
                return $this->response->errorForbidden(
                    'Email já está cadastrado. Faça o login e sincronize sua conta'
                );
            }
        }

        // Get local user by social id
        $localUser = $this->userRepo->getUserBySocialKeys($socialUser);

        try{

            //  Invalid credentials
            if( ! ( $token = JWTAuth::fromUser($localUser) ) ){
                return $this->response->errorForbidden(trans('api.errors.invalid_credentials'));
            }

        }catch(JWTException $e){
            return $this->response->errorForbidden(trans('api.errors.could_not_create_access_token'));
        }

        // Return response 201
        return $this->response->created(null,$token);
    }

    /**
     * Create a local user if does not exists
     *
     * @param  SocialUser $user
     * @return boolean|App\User
     */
    private function createLocalUser(SocialUser $socialUser, $driver)
    {
        // If email already exists, user need to authenticate first
        // then syncronize accounts
        if( $this->userRepo->userExistsByEmail($socialUser->getEmail())) {
            return false;
        }

        /**
         * Social users doesn't have a password defined. In that case,
         * we create a new user with a random password.
         *
         * For "basic" authentication, user must change the current password.
         * 1) Login with social credentials, update profile
         * 2) Remember password
         */
        return $this->userRepo->createSocialIntegration($driver,
            $this->userRepo->store([
                'name'     => $socialUser->getName(),
                'email'    => $socialUser->getEmail(),
                'password' => str_random(20)
            ]),
            $socialUser
        );
    }
}
