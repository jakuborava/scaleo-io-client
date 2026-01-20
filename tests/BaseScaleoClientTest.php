<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use JakubOrava\ScaleoIoClient\BaseScaleoClient;
use JakubOrava\ScaleoIoClient\Exceptions\ApiErrorException;
use JakubOrava\ScaleoIoClient\Exceptions\AuthenticationException;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\Exceptions\ValidationException;

beforeEach(function () {
    $this->client = new BaseScaleoClient(
        apiKey: 'test-api-key',
        baseUrl: 'https://test.scaletrk.com'
    );
});

describe('HTTP Methods', function () {
    it('can make GET requests', function () {
        Http::fake([
            'https://test.scaletrk.com/api/test*' => Http::response([
                'status' => 1,
                'code' => 200,
                'info' => ['data' => 'test'],
            ], 200),
        ]);

        $result = $this->client->get('/api/test', ['param' => 'value']);

        expect($result)->toBeArray()
            ->and($result['data'])->toBe(['data' => 'test'])
            ->and($result['headers'])->toBeArray();

        Http::assertSent(function ($request) {
            return $request->url() === 'https://test.scaletrk.com/api/test?param=value&api-key=test-api-key' &&
                   $request->method() === 'GET';
        });
    });

    it('can make POST requests', function () {
        Http::fake([
            'https://test.scaletrk.com/api/test*' => Http::response([
                'status' => 1,
                'code' => 200,
                'info' => ['created' => true],
            ], 200),
        ]);

        $result = $this->client->post('/api/test', ['name' => 'John']);

        expect($result)->toBeArray()
            ->and($result['data'])->toBe(['created' => true]);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'https://test.scaletrk.com/api/test') &&
                   $request->method() === 'POST' &&
                   $request['name'] === 'John' &&
                   $request['api-key'] === 'test-api-key';
        });
    });

    it('can make PATCH requests', function () {
        Http::fake([
            'https://test.scaletrk.com/api/test*' => Http::response([
                'status' => 1,
                'code' => 200,
                'info' => ['updated' => true],
            ], 200),
        ]);

        $result = $this->client->patch('/api/test', ['name' => 'Jane']);

        expect($result)->toBeArray()
            ->and($result['data'])->toBe(['updated' => true]);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'https://test.scaletrk.com/api/test') &&
                   $request->method() === 'PATCH';
        });
    });

    it('can make PUT requests', function () {
        Http::fake([
            'https://test.scaletrk.com/api/test*' => Http::response([
                'status' => 1,
                'code' => 200,
                'info' => ['replaced' => true],
            ], 200),
        ]);

        $result = $this->client->put('/api/test', ['name' => 'Bob']);

        expect($result)->toBeArray()
            ->and($result['data'])->toBe(['replaced' => true]);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'https://test.scaletrk.com/api/test') &&
                   $request->method() === 'PUT';
        });
    });

    it('can make DELETE requests', function () {
        Http::fake([
            'https://test.scaletrk.com/api/test/123*' => Http::response([
                'status' => 1,
                'code' => 200,
                'info' => ['deleted' => true],
            ], 200),
        ]);

        $result = $this->client->delete('/api/test/123');

        expect($result)->toBeArray()
            ->and($result['data'])->toBe(['deleted' => true]);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'https://test.scaletrk.com/api/test/123') &&
                   $request->method() === 'DELETE';
        });
    });
});

describe('Response Handling', function () {
    it('unwraps Scaleo response structure', function () {
        Http::fake([
            'https://test.scaletrk.com/api/test*' => Http::response([
                'status' => 1,
                'code' => 200,
                'name' => 'Success',
                'message' => 'OK',
                'info' => ['key' => 'value'],
            ], 200),
        ]);

        $result = $this->client->get('/api/test');

        expect($result['data'])->toBe(['key' => 'value']);
    });

    it('handles responses without info wrapper', function () {
        Http::fake([
            'https://test.scaletrk.com/api/test*' => Http::response([
                'key' => 'value',
            ], 200),
        ]);

        $result = $this->client->get('/api/test');

        expect($result['data'])->toBe(['key' => 'value']);
    });

    it('includes response headers', function () {
        Http::fake([
            'https://test.scaletrk.com/api/test*' => Http::response(
                ['status' => 1, 'info' => ['data' => 'test']],
                200,
                ['X-Custom-Header' => 'test-value']
            ),
        ]);

        $result = $this->client->get('/api/test');

        expect($result['headers'])->toBeArray()
            ->and($result['headers'])->toHaveKey('X-Custom-Header');
    });
});

describe('Authentication', function () {
    it('includes API key in all requests', function () {
        Http::fake([
            'https://test.scaletrk.com/api/test*' => Http::response([
                'status' => 1,
                'info' => [],
            ], 200),
        ]);

        $this->client->get('/api/test');

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'api-key=test-api-key');
        });
    });

    it('throws AuthenticationException on 401 response', function () {
        Http::fake([
            'https://test.scaletrk.com/api/test*' => Http::response([], 401),
        ]);

        expect(fn () => $this->client->get('/api/test'))
            ->toThrow(AuthenticationException::class, 'Authentication failed. Please check your API key.');
    });
});

describe('Error Handling', function () {
    it('throws ValidationException on 422 response', function () {
        Http::fake([
            'https://test.scaletrk.com/api/test*' => Http::response([
                'message' => 'Invalid input',
            ], 422),
        ]);

        expect(fn () => $this->client->post('/api/test', ['invalid' => 'data']))
            ->toThrow(ValidationException::class);
    });

    it('throws ApiErrorException on 403 response', function () {
        Http::fake([
            'https://test.scaletrk.com/api/test*' => Http::response([
                'message' => 'Forbidden',
            ], 403),
        ]);

        expect(fn () => $this->client->get('/api/test'))
            ->toThrow(ApiErrorException::class, 'Access forbidden');
    });

    it('throws ApiErrorException on 404 response', function () {
        Http::fake([
            'https://test.scaletrk.com/api/test*' => Http::response([], 404),
        ]);

        expect(fn () => $this->client->get('/api/test'))
            ->toThrow(ApiErrorException::class, 'Resource not found');
    });

    it('throws ApiErrorException on other client errors', function () {
        Http::fake([
            'https://test.scaletrk.com/api/test*' => Http::response([
                'message' => 'Bad Request',
            ], 400),
        ]);

        expect(fn () => $this->client->get('/api/test'))
            ->toThrow(ApiErrorException::class);
    });

    it('throws ApiErrorException on server errors', function () {
        Http::fake([
            'https://test.scaletrk.com/api/test*' => Http::response([], 500),
        ]);

        expect(fn () => $this->client->get('/api/test'))
            ->toThrow(ApiErrorException::class, 'Server error occurred');
    });

    it('extracts error message from response', function () {
        Http::fake([
            'https://test.scaletrk.com/api/test*' => Http::response([
                'message' => 'Custom error message',
            ], 400),
        ]);

        try {
            $this->client->get('/api/test');
            expect(false)->toBeTrue(); // Should not reach here
        } catch (ApiErrorException $e) {
            expect($e->getMessage())->toContain('Custom error message');
        }
    });

    it('extracts error from error field', function () {
        Http::fake([
            'https://test.scaletrk.com/api/test*' => Http::response([
                'error' => 'Error field message',
            ], 400),
        ]);

        try {
            $this->client->get('/api/test');
            expect(false)->toBeTrue(); // Should not reach here
        } catch (ApiErrorException $e) {
            expect($e->getMessage())->toContain('Error field message');
        }
    });

    it('throws UnexpectedResponseException when response is not an array', function () {
        Http::fake([
            'https://test.scaletrk.com/api/test*' => Http::response('string response', 200),
        ]);

        expect(fn () => $this->client->get('/api/test'))
            ->toThrow(UnexpectedResponseException::class, 'Response is not an array');
    });

    it('throws UnexpectedResponseException when info is not an array', function () {
        Http::fake([
            'https://test.scaletrk.com/api/test*' => Http::response([
                'status' => 1,
                'info' => 'string',
            ], 200),
        ]);

        expect(fn () => $this->client->get('/api/test'))
            ->toThrow(UnexpectedResponseException::class, 'Response info is not an array');
    });
});

describe('Download Method', function () {
    it('can download binary files', function () {
        $binaryContent = 'PDF binary content';

        Http::fake([
            'https://test.scaletrk.com/api/download*' => Http::response($binaryContent, 200),
        ]);

        $result = $this->client->download('/api/download');

        expect($result)->toBe($binaryContent);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'api-key=test-api-key') &&
                   $request->method() === 'GET';
        });
    });

    it('throws AuthenticationException on download 401', function () {
        Http::fake([
            'https://test.scaletrk.com/api/download*' => Http::response([], 401),
        ]);

        expect(fn () => $this->client->download('/api/download'))
            ->toThrow(AuthenticationException::class);
    });

    it('throws ApiErrorException on download failure', function () {
        Http::fake([
            'https://test.scaletrk.com/api/download*' => Http::response([], 500),
        ]);

        expect(fn () => $this->client->download('/api/download'))
            ->toThrow(ApiErrorException::class);
    });
});

describe('URL Building', function () {
    it('builds URLs correctly', function () {
        Http::fake([
            'https://test.scaletrk.com/api/v1/test*' => Http::response([
                'status' => 1,
                'info' => [],
            ], 200),
        ]);

        $this->client->get('/api/v1/test');

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'https://test.scaletrk.com/api/v1/test');
        });
    });

    it('strips leading slash from path', function () {
        Http::fake([
            'https://test.scaletrk.com/api/test*' => Http::response([
                'status' => 1,
                'info' => [],
            ], 200),
        ]);

        $this->client->get('api/test');

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'https://test.scaletrk.com/api/test');
        });
    });

    it('handles base URL with trailing slash', function () {
        Http::fake([
            'https://test.scaletrk.com/api/test*' => Http::response([
                'status' => 1,
                'info' => [],
            ], 200),
        ]);

        $client = new BaseScaleoClient(
            apiKey: 'test-api-key',
            baseUrl: 'https://test.scaletrk.com/'
        );
        $client->get('/api/test');

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'https://test.scaletrk.com/api/test');
        });
    });
});

describe('HTTP Client Configuration', function () {
    it('sets correct headers', function () {
        Http::fake([
            'https://test.scaletrk.com/api/test*' => Http::response([
                'status' => 1,
                'info' => [],
            ], 200),
        ]);

        $this->client->get('/api/test');

        Http::assertSent(function ($request) {
            return $request->hasHeader('Accept', 'application/json') &&
                   $request->hasHeader('Content-Type', 'application/json');
        });
    });
});
