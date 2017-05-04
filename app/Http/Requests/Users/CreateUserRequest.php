<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\ApiBaseRequest;
use Log;

class CreateUserRequest extends ApiBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //'cpf'      => 'required|unique:users,cpf', // @TODO validate cpf
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'city_id'  => 'sometimes|exists:cities,id',
        ];
    }
}
