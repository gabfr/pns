<?php

namespace App\Exceptions;

use Log;
use Exception;


class GatewayException extends Exception {

	protected $responsePayload = null;

    public function __construct($message, $code = 0, Exception $previous = null, $responsePayload = null) {
        $this->responsePayload = $responsePayload;
    
        parent::__construct($message, $code, $previous);
    }

    public function getResponsePayload()
    {
    	return $this->responsePayload;
    }

    public function setResponsePayload($responsePayload)
    {
    	Log::info('[GatewayException] Response Payload: ' . print_r($responsePayload, true));
    	$this->responsePayload = $responsePayload;
        return $this;
    }
}