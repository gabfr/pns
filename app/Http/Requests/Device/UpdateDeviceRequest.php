<?php

namespace App\Http\Requests\Device;

use App\Http\Requests\ApiBaseRequest;
use Log;

class UpdateDeviceRequest extends ApiBaseRequest
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
            'platform'     => 'required',
            'device_token'    => 'required',
            'device_id' => 'required'
        ];
    }
}
