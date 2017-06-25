<?php

namespace App\Http\Requests\CloudObjects;

use App\Http\Requests\ApiBaseRequest;
use Log;

class CreateCloudObjectRequest extends ApiBaseRequest
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
            'slug'     => 'required|unique:cloud_objects,slug',
            'name'     => 'required',
            'object' => 'required|file'
        ];
    }
}
