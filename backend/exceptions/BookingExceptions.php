<?php

namespace App\Exceptions;

use Exception;

class BookingException extends Exception
{
    protected string $errorCode;

    public function __construct(string $message, string $errorCode, int $httpCode = 400)
    {
        parent::__construct($message, $httpCode);
        $this->errorCode = $errorCode;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}
