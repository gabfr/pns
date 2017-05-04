<?php

namespace App\Transformers;

use Dingo\Api\Http\Request;
use Dingo\Api\Transformer\Binding;
use League\Fractal\TransformerAbstract;

class BasicTransformer extends TransformerAbstract
{
    public function transform($response)
    {
        return $response->toArray();
    }
}