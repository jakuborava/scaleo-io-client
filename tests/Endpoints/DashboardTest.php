<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\NetworkSummaryItemDTO;
use JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Dashboard;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\Requests\NetworkSummaryRequest;
use JakubOrava\ScaleoIoClient\ScaleoIoClient;

beforeEach(function () {
    $client = new ScaleoIoClient(
        apiKey: 'test-api-key',
        baseUrl: 'https://test.scaletrk.com'
    );
    $this->dashboard = $client->affiliate()->dashboard();
});

describe('Network Summary', function () {
    it('can fetch network summary statistics', function () {
        Http::fake([
            '*/api/v2/affiliate/dashboard/statistics/network-summary*' => Http::response([
                'status' => 1,
                'code' => 200,
                'info' => [
                    [
                        'key' => 'clicks',
                        'current' => [
                            'from' => 1704067200,
                            'to' => 1704153600,
                            'total' => 1234,
                            'total_partial' => 1234,
                            'total_change' => 23.4,
                            'ranges' => [],
                            'series' => [],
                        ],
                        'previous' => [
                            'from' => 1703980800,
                            'to' => 1704067200,
                            'total' => 1000,
                            'total_partial' => 1000,
                            'total_change' => 0.0,
                            'ranges' => [],
                            'series' => [],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $result = $this->dashboard->networkSummary();

        expect($result)->toBeInstanceOf(Collection::class)
            ->and($result)->toHaveCount(1)
            ->and($result->first())->toBeInstanceOf(NetworkSummaryItemDTO::class)
            ->and($result->first()->key)->toBe('clicks')
            ->and($result->first()->current->total)->toBe(1234);
    });

    it('can use NetworkSummaryRequest', function () {
        Http::fake([
            '*/api/v2/affiliate/dashboard/statistics/network-summary*' => Http::response([
                'status' => 1,
                'info' => [],
            ], 200),
        ]);

        $req = (new NetworkSummaryRequest)->preset('last_7_days');
        $this->dashboard->networkSummary($req);

        Http::assertSent(function ($request) {
            return $request['preset'] === 'last_7_days';
        });
    });

    it('uses default request when none provided', function () {
        Http::fake([
            '*/api/v2/affiliate/dashboard/statistics/network-summary*' => Http::response([
                'status' => 1,
                'info' => [],
            ], 200),
        ]);

        $this->dashboard->networkSummary();

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'api/v2/affiliate/dashboard/statistics/network-summary');
        });
    });

    it('throws UnexpectedResponseException on invalid response', function () {
        Http::fake([
            '*/api/v2/affiliate/dashboard/statistics/network-summary*' => Http::response([
                'status' => 1,
                'info' => 'not an array',
            ], 200),
        ]);

        expect(fn () => $this->dashboard->networkSummary())
            ->toThrow(UnexpectedResponseException::class);
    });

    it('throws UnexpectedResponseException on invalid item', function () {
        Http::fake([
            '*/api/v2/affiliate/dashboard/statistics/network-summary*' => Http::response([
                'status' => 1,
                'info' => ['string instead of array'],
            ], 200),
        ]);

        expect(fn () => $this->dashboard->networkSummary())
            ->toThrow(UnexpectedResponseException::class);
    });
});

describe('API Interaction', function () {
    it('makes POST request to correct endpoint', function () {
        Http::fake([
            '*/api/v2/affiliate/dashboard/statistics/network-summary*' => Http::response([
                'status' => 1,
                'info' => [],
            ], 200),
        ]);

        $this->dashboard->networkSummary();

        Http::assertSent(function ($request) {
            return $request->method() === 'POST' &&
                   str_contains($request->url(), 'api/v2/affiliate/dashboard/statistics/network-summary');
        });
    });

    it('includes query parameters in request', function () {
        Http::fake([
            '*/api/v2/affiliate/dashboard/statistics/network-summary*' => Http::response([
                'status' => 1,
                'info' => [],
            ], 200),
        ]);

        $req = (new NetworkSummaryRequest)
            ->rangeFrom('2024-01-01')
            ->rangeTo('2024-12-31');

        $this->dashboard->networkSummary($req);

        Http::assertSent(function ($request) {
            return $request['rangeFrom'] === '2024-01-01' &&
                   $request['rangeTo'] === '2024-12-31';
        });
    });
});
