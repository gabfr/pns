<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Api\ApiBaseController;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Http\Requests\Users\RememberPasswordRequest;
use Illuminate\Mail\Message;
use App\Http\Requests\Users\ResetPasswordRequest;
use Illuminate\Support\Facades\Password;
use Log;

class PasswordController extends ApiBaseController
{

    protected $userRepo;

    public function __construct(
        UserRepositoryContract $userRepo
    ) {
        $this->userRepo = $userRepo;
    }

    /**
     * Send an email with link to reset password
     *
     * @param  RememberPasswordRequest $request
     * @return Dingo\Api\Http\Response
     */
    public function remember(RememberPasswordRequest $request)
    {
        $response = Password::sendResetLink(
            $request->only('email'), function (Message $message) {
                $message->subject('Redefinir senha');
            }
        );

        return $this->response->created();
    }

    /**
     * Reset user password
     *
     * @param  ResetPasswordRequest $request
     * @return Dingo\Api\Http\Response
     */
    public function reset(ResetPasswordRequest $request)
    {
        $credentials = $request->only('email', 'password', 'token');

        $credentials['password_confirmation'] = $credentials['password'];

        $response = Password::reset($credentials, function ($user, $password) {

            Log::info('Senha atualizada com sucesso');

            $user->forceFill([
                'password' => bcrypt($password),
                'remember_token' => str_random(60),
            ])->save();
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                return $this->response->noContent();
            default:
                return $this->response->error(trans($response), 400);
        }
    }
}
