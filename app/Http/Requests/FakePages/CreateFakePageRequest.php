<?php

namespace App\Http\Requests\FakePages;

use App\Http\Requests\ApiBaseRequest;
use Log;

class CreateFakePageRequest extends ApiBaseRequest
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
            'name'     => 'required',
            'content_url'    => 'required',
            'is_active' => 'required|boolean',
            'application_id' => 'required|exists:applications,id'
        ];
    }
}
