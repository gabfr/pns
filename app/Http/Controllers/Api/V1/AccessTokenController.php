<?php

namespace App\Http\Controllers\Api\V1;

use JWTAuth;
use App\Http\Requests;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Api\ApiBaseController;

class AccessTokenController extends ApiBaseController
{

    /**
     * Create a new token based on the users credentials
     *
     * @param  Request $request user credentials
     * @return Dingo\Api\Http\Response
     */
    public function request(Request $request)
    {
        $credentials = $request->only('email','password');

        try{

            //  Invalid credentials
            if( ! $token = JWTAuth::attempt($credentials) ) {
                return $this->response->errorForbidden(trans('api.errors.invalid_credentials'));
            }

        }catch(JWTException $e){
            return $this->response->errorForbidden(trans('api.errors.could_not_create_access_token'));
        }

        // Return response 201
        return $this->response->created(null, $token);
    }
}
