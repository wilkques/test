<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedException extends Exception
{
    protected $code = 401;
    protected $resData = [];

    public function __construct($message = '', $resData = [], $logChannel = 'authlog')
    {
        $this->message = $message;
        $this->logChannel = $logChannel;
        $this->resData = $resData;
    }

    public function render()
    {
        return response()->json([
            'error' => $this->getMessage()
        ] + $this->resData, $this->getCode());
    }
}
