<?php

namespace App\Http\Requests\Application;

use App\Http\Requests\ApiBaseRequest;
use Log;

class ApnsUpdateApplicationRequest extends ApiBaseRequest
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
        	'apns_certificate_password' => 'sometimes',
        	'apns_certificate_sandbox' => 'sometimes|file',
        	'apns_certificate_production' => 'sometimes|file',
        	'apns_root_certificate' => 'sometimes|file',
            'apns_mode' => 'sometimes|in:sandbox,production'
        ];
    }
}
