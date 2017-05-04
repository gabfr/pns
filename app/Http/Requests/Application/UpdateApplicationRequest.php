<?php

namespace App\Http\Requests\Application;

use App\Http\Requests\ApiBaseRequest;
use Log;

class UpdateApplicationRequest extends ApiBaseRequest
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
    	$application = $this->route('application');
        return [
            'name'     => 'sometimes',
            'slug'    => "sometimes|unique:applications,slug,{$application->slug},slug"
        ];
    }
}
