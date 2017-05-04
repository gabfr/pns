<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use App\Transformers\BasicTransformer;

class ApiBaseController extends Controller
{
    use Helpers;

    /**
     * @return App\Transformers\BasicTransformer
     */
    protected function getBasicTransformer()
    {
        return new BasicTransformer;
    }
}
