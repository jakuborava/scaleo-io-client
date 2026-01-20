<?php

declare(strict_types=1);

use JakubOrava\ScaleoIoClient\Exceptions\ApiErrorException;
use JakubOrava\ScaleoIoClient\Exceptions\AuthenticationException;
use JakubOrava\ScaleoIoClient\Exceptions\ScaleoIoClientException;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\Exceptions\ValidationException;

describe('ScaleoIoClientException', function () {
    it('can be instantiated', function () {
        $exception = new ScaleoIoClientException('Test exception');

        expect($exception)->toBeInstanceOf(ScaleoIoClientException::class)
            ->and($exception)->toBeInstanceOf(\Exception::class)
            ->and($exception->getMessage())->toBe('Test exception');
    });
});

describe('AuthenticationException', function () {
    it('extends ScaleoIoClientException', function () {
        $exception = new AuthenticationException('Auth failed');

        expect($exception)->toBeInstanceOf(AuthenticationException::class)
            ->and($exception)->toBeInstanceOf(ScaleoIoClientException::class)
            ->and($exception->getMessage())->toBe('Auth failed');
    });
});

describe('ValidationException', function () {
    it('extends ScaleoIoClientException', function () {
        $exception = new ValidationException('Validation failed');

        expect($exception)->toBeInstanceOf(ValidationException::class)
            ->and($exception)->toBeInstanceOf(ScaleoIoClientException::class)
            ->and($exception->getMessage())->toBe('Validation failed');
    });
});

describe('UnexpectedResponseException', function () {
    it('extends ScaleoIoClientException', function () {
        $exception = new UnexpectedResponseException('Unexpected response');

        expect($exception)->toBeInstanceOf(UnexpectedResponseException::class)
            ->and($exception)->toBeInstanceOf(ScaleoIoClientException::class)
            ->and($exception->getMessage())->toBe('Unexpected response');
    });
});

describe('ApiErrorException', function () {
    it('extends ScaleoIoClientException', function () {
        $exception = new ApiErrorException('API error', 500);

        expect($exception)->toBeInstanceOf(ApiErrorException::class)
            ->and($exception)->toBeInstanceOf(ScaleoIoClientException::class)
            ->and($exception->getMessage())->toBe('API error');
    });

    it('stores status code', function () {
        $exception = new ApiErrorException('API error', 404);

        expect($exception->statusCode)->toBe(404);
    });

    it('accepts previous exception', function () {
        $previous = new \Exception('Previous exception');
        $exception = new ApiErrorException('API error', 500, $previous);

        expect($exception->getPrevious())->toBe($previous);
    });
});
