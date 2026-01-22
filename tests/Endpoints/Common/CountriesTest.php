<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use JakubOrava\ScaleoIoClient\DTO\Common\CountryDTO;
use JakubOrava\ScaleoIoClient\DTO\Common\ListResponse;
use JakubOrava\ScaleoIoClient\Requests\BaseRequest;
use JakubOrava\ScaleoIoClient\ScaleoIoClient;

beforeEach(function () {
    $client = new ScaleoIoClient(
        apiKey: 'test-api-key',
        baseUrl: 'https://test.scaletrk.com'
    );
    $this->countries = $client->common()->countries();
});

describe('Countries List', function () {
    it('can list countries', function () {
        Http::fake([
            '*/api/v2/common/lists/countries*' => Http::response([
                'status' => 1,
                'info' => [
                    'count' => 3,
                ],
                'results' => [
                    [
                        'id' => 'CZ',
                        'title' => 'Czech Republic',
                    ],
                    [
                        'id' => 'US',
                        'title' => 'United States',
                    ],
                    [
                        'id' => 'GB',
                        'title' => 'United Kingdom',
                    ],
                ],
            ], 200),
        ]);

        $result = $this->countries->list();

        expect($result)->toBeInstanceOf(ListResponse::class)
            ->and($result->count)->toBe(3)
            ->and($result->results)->toHaveCount(3)
            ->and($result->results->first())->toBeInstanceOf(CountryDTO::class)
            ->and($result->results->first()->id)->toBe('CZ')
            ->and($result->results->first()->title)->toBe('Czech Republic')
            ->and($result->results->last()->id)->toBe('GB')
            ->and($result->results->last()->title)->toBe('United Kingdom');
    });

    it('can use BaseRequest with pagination', function () {
        Http::fake([
            '*/api/v2/common/lists/countries*' => Http::response([
                'status' => 1,
                'info' => ['count' => 0],
                'results' => [],
            ], 200),
        ]);

        $request = (new BaseRequest)
            ->page(2)
            ->perPage(50);

        $this->countries->list($request);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'page=2') &&
                   str_contains($request->url(), 'perPage=50');
        });
    });

    it('makes GET request to correct endpoint', function () {
        Http::fake([
            '*/api/v2/common/lists/countries*' => Http::response([
                'status' => 1,
                'info' => ['count' => 0],
                'results' => [],
            ], 200),
        ]);

        $this->countries->list();

        Http::assertSent(function ($request) {
            return $request->method() === 'GET' &&
                   str_contains($request->url(), 'api/v2/common/lists/countries');
        });
    });

    it('handles empty results', function () {
        Http::fake([
            '*/api/v2/common/lists/countries*' => Http::response([
                'status' => 1,
                'info' => ['count' => 0],
                'results' => [],
            ], 200),
        ]);

        $result = $this->countries->list();

        expect($result->count)->toBe(0)
            ->and($result->results)->toBeEmpty();
    });
});
