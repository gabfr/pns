<?php

namespace App\Http\Requests\Application;

use App\Http\Requests\ApiBaseRequest;
use Log;

class GcmUpdateApplicationRequest extends ApiBaseRequest
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
            'gcm_mode' => 'required|in:sandbox,production',
        	'gcm_api_key' => 'required'
        ];
    }
}
