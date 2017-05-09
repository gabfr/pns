<?php

namespace App\Http\Requests\Notification;

use App\Http\Requests\ApiBaseRequest;
use Log;

class UpdateNotificationRequest extends ApiBaseRequest
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
            'title'     => 'sometimes',
            'alert_message'    => 'sometimes',
            'icon' => 'required',
            'url' => 'required'
        ];
    }
}
