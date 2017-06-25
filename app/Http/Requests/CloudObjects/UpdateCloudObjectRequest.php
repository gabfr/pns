<?php

namespace App\Http\Requests\CloudObjects;

use App\Http\Requests\ApiBaseRequest;
use Log;

class UpdateCloudObjectRequest extends ApiBaseRequest
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
        $cloudObject = $this->route('cloudObject');
        return [
            'slug'     => 'sometimes|unique:cloud_objects,slug,' . $cloudObject->getKey(),
            'name'     => 'sometimes',
            'object' => 'sometimes'
        ];
    }
}
