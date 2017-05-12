<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\ApiBaseRequest;
use Log;

class UpdateUserRequest extends ApiBaseRequest
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
        $user = $this->route('user');
        return [
            //'cpf'      => 'required|unique:users,cpf', // @TODO validate cpf
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email,' . $user->getKey() . ',id',
            'password' => 'sometimes|min:6',
            'is_super' => 'required|boolean',
            'city_id'  => 'sometimes|exists:cities,id',
        ];
    }
}
