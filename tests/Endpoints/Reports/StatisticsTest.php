<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\BreakdownOptionDTO;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\ColumnOptionDTO;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\StatisticsReportDTO;
use JakubOrava\ScaleoIoClient\DTO\PaginatedResponse;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\Requests\StatisticsReportRequest;
use JakubOrava\ScaleoIoClient\ScaleoIoClient;

beforeEach(function () {
    $client = new ScaleoIoClient(
        apiKey: 'test-api-key',
        baseUrl: 'https://test.scaletrk.com'
    );
    $this->statistics = $client->affiliate()->reports()->statistics();
});

describe('List Statistics Report', function () {
    it('can get statistics report', function () {
        Http::fake([
            '*/api/v2/affiliate/reports/statistics*' => Http::response([
                'status' => 1,
                'info' => [
                    'rows' => [
                        [
                            'offer_id' => 123,
                            'statistics' => [
                                'clicks' => 100,
                                'conversions' => 10,
                                'revenue' => 150.00,
                            ],
                        ],
                    ],
                    'totals' => [
                        'clicks' => 100,
                        'conversions' => 10,
                        'revenue' => 150.00,
                    ],
                ],
            ], 200),
        ]);

        $request = (new StatisticsReportRequest)
            ->rangeFrom('2024-01-01')
            ->rangeTo('2024-12-31')
            ->columns(['clicks', 'conversions', 'revenue']);

        $result = $this->statistics->list($request);

        expect($result)->toBeInstanceOf(PaginatedResponse::class)
            ->and($result->items)->toHaveCount(1)
            ->and($result->items->first())->toBeInstanceOf(StatisticsReportDTO::class);
    });

    it('makes POST request with correct parameters', function () {
        Http::fake([
            '*/api/v2/affiliate/reports/statistics*' => Http::response([
                'status' => 1,
                'info' => ['rows' => [], 'totals' => []],
            ], 200),
        ]);

        $req = (new StatisticsReportRequest)
            ->rangeFrom('2024-01-01')
            ->rangeTo('2024-12-31')
            ->breakdown('day')
            ->columns(['clicks', 'revenue'])
            ->page(1)
            ->perPage(50);

        $this->statistics->list($req);

        Http::assertSent(function ($request) {
            return $request->method() === 'POST' &&
                   str_contains($request->url(), 'api/v2/affiliate/reports/statistics') &&
                   $request['page'] === 1 &&
                   $request['perPage'] === 50 &&
                   $request['rangeFrom'] === '2024-01-01' &&
                   $request['rangeTo'] === '2024-12-31' &&
                   $request['breakdown'] === 'day' &&
                   $request['columns'] === 'clicks,revenue';
        });
    });

    it('separates query params and body params', function () {
        Http::fake([
            '*/api/v2/affiliate/reports/statistics*' => Http::response([
                'status' => 1,
                'info' => ['rows' => [], 'totals' => []],
            ], 200),
        ]);

        $req = (new StatisticsReportRequest)
            ->rangeFrom('2024-01-01')
            ->rangeTo('2024-12-31')
            ->lang('en')
            ->page(1);

        $this->statistics->list($req);

        Http::assertSent(function ($request) {
            // Both query and body params are in the request body for POST
            return $request['lang'] === 'en' &&
                   $request['page'] === 1 &&
                   $request['rangeFrom'] === '2024-01-01' &&
                   $request['rangeTo'] === '2024-12-31';
        });
    });
});

describe('Get Column Options', function () {
    it('can get column options', function () {
        Http::fake([
            '*/api/v2/affiliate/reports/statistics/options*' => Http::response([
                'status' => 1,
                'info' => [
                    'columns-list' => [
                        [
                            'group' => 'statistics',
                            'label' => 'Clicks',
                            'key' => 'clicks',
                            'sort' => 1,
                            'groupSort' => 1,
                            'reportSort' => 1,
                            'default' => 1,
                            'parent' => null,
                            'level' => 0,
                            'column' => 1,
                        ],
                        [
                            'group' => 'statistics',
                            'label' => 'Conversions',
                            'key' => 'conversions',
                            'sort' => 2,
                            'groupSort' => 1,
                            'reportSort' => 2,
                            'default' => 1,
                            'parent' => null,
                            'level' => 0,
                            'column' => 1,
                        ],
                    ],
                ],
            ], 200),
        ]);

        $result = $this->statistics->options();

        expect($result)->toBeInstanceOf(Collection::class)
            ->and($result)->toHaveCount(2)
            ->and($result->first())->toBeInstanceOf(ColumnOptionDTO::class)
            ->and($result->first()->key)->toBe('clicks')
            ->and($result->first()->label)->toBe('Clicks');
    });

    it('makes GET request to correct endpoint', function () {
        Http::fake([
            '*/api/v2/affiliate/reports/statistics/options*' => Http::response([
                'status' => 1,
                'info' => ['columns-list' => []],
            ], 200),
        ]);

        $this->statistics->options();

        Http::assertSent(function ($request) {
            return $request->method() === 'GET' &&
                   str_contains($request->url(), 'api/v2/affiliate/reports/statistics/options');
        });
    });

    it('throws UnexpectedResponseException when columns-list is not array', function () {
        Http::fake([
            '*/api/v2/affiliate/reports/statistics/options*' => Http::response([
                'status' => 1,
                'info' => ['columns-list' => 'string'],
            ], 200),
        ]);

        expect(fn () => $this->statistics->options())
            ->toThrow(UnexpectedResponseException::class);
    });

    it('throws UnexpectedResponseException when column item is not array', function () {
        Http::fake([
            '*/api/v2/affiliate/reports/statistics/options*' => Http::response([
                'status' => 1,
                'info' => ['columns-list' => ['string']],
            ], 200),
        ]);

        expect(fn () => $this->statistics->options())
            ->toThrow(UnexpectedResponseException::class);
    });
});

describe('Get Breakdown Options', function () {
    it('can get breakdown options', function () {
        Http::fake([
            '*/api/v2/affiliate/reports/statistics/breakdowns*' => Http::response([
                'status' => 1,
                'info' => [
                    'breakdowns-list' => [
                        [
                            'group' => 'time',
                            'label' => 'Day',
                            'key' => 'day',
                            'sort' => 1,
                            'groupSort' => 1,
                        ],
                        [
                            'group' => 'time',
                            'label' => 'Month',
                            'key' => 'month',
                            'sort' => 2,
                            'groupSort' => 1,
                        ],
                    ],
                ],
            ], 200),
        ]);

        $result = $this->statistics->breakdowns();

        expect($result)->toBeInstanceOf(Collection::class)
            ->and($result)->toHaveCount(2)
            ->and($result->first())->toBeInstanceOf(BreakdownOptionDTO::class)
            ->and($result->first()->key)->toBe('day')
            ->and($result->first()->label)->toBe('Day');
    });

    it('makes GET request to correct endpoint', function () {
        Http::fake([
            '*/api/v2/affiliate/reports/statistics/breakdowns*' => Http::response([
                'status' => 1,
                'info' => ['breakdowns-list' => []],
            ], 200),
        ]);

        $this->statistics->breakdowns();

        Http::assertSent(function ($request) {
            return $request->method() === 'GET' &&
                   str_contains($request->url(), 'api/v2/affiliate/reports/statistics/breakdowns');
        });
    });

    it('throws UnexpectedResponseException when breakdowns-list is not array', function () {
        Http::fake([
            '*/api/v2/affiliate/reports/statistics/breakdowns*' => Http::response([
                'status' => 1,
                'info' => ['breakdowns-list' => 'string'],
            ], 200),
        ]);

        expect(fn () => $this->statistics->breakdowns())
            ->toThrow(UnexpectedResponseException::class);
    });

    it('throws UnexpectedResponseException when breakdown item is not array', function () {
        Http::fake([
            '*/api/v2/affiliate/reports/statistics/breakdowns*' => Http::response([
                'status' => 1,
                'info' => ['breakdowns-list' => ['string']],
            ], 200),
        ]);

        expect(fn () => $this->statistics->breakdowns())
            ->toThrow(UnexpectedResponseException::class);
    });
});
