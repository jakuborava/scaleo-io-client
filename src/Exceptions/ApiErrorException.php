<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Exceptions;

class ApiErrorException extends ScaleoIoClientException
{
    public function __construct(
        string $message,
        public readonly int $statusCode,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $statusCode, $previous);
    }
}
