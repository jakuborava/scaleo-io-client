<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\OfferDTO;
use JakubOrava\ScaleoIoClient\DTO\PaginatedResponse;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\Requests\OffersListRequest;
use JakubOrava\ScaleoIoClient\ScaleoIoClient;

beforeEach(function () {
    $client = new ScaleoIoClient(
        apiKey: 'test-api-key',
        baseUrl: 'https://test.scaletrk.com'
    );
    $this->offers = $client->affiliate()->offers();
});

describe('List Offers', function () {
    it('can list offers', function () {
        Http::fake([
            '*/api/v2/affiliate/offers*' => Http::response([
                'status' => 1,
                'info' => [
                    'offers' => [
                        [
                            'id' => 1,
                            'title' => 'Test Offer 1',
                            'description' => null,
                            'advertiser_id' => null,
                            'image' => null,
                            'gt_included_ids' => null,
                            'gt_excluded_ids' => null,
                            'gt_filter_ids' => null,
                            'extended_targeting' => null,
                            'traffic_types' => null,
                            'is_featured' => 0,
                            'timezone' => null,
                            'currency' => 'USD',
                            'aff_epc' => null,
                            'ar' => null,
                            'cr' => null,
                            'visible_type' => null,
                            'questions' => null,
                            'ask_approval_questions' => null,
                            'tracking_domain_id' => null,
                            'traffic_types_selected' => null,
                            'tracking_domain' => null,
                            'goals' => [],
                            'targeting' => null,
                            'creatives_count' => 0,
                            'creatives' => [],
                            'links_count' => 0,
                            'links' => [],
                            'categories' => [],
                        ],
                        [
                            'id' => 2,
                            'title' => 'Test Offer 2',
                            'description' => null,
                            'advertiser_id' => null,
                            'image' => null,
                            'gt_included_ids' => null,
                            'gt_excluded_ids' => null,
                            'gt_filter_ids' => null,
                            'extended_targeting' => null,
                            'traffic_types' => null,
                            'is_featured' => 0,
                            'timezone' => null,
                            'currency' => 'USD',
                            'aff_epc' => null,
                            'ar' => null,
                            'cr' => null,
                            'visible_type' => null,
                            'questions' => null,
                            'ask_approval_questions' => null,
                            'tracking_domain_id' => null,
                            'traffic_types_selected' => null,
                            'tracking_domain' => null,
                            'goals' => [],
                            'targeting' => null,
                            'creatives_count' => 0,
                            'creatives' => [],
                            'links_count' => 0,
                            'links' => [],
                            'categories' => [],
                        ],
                    ],
                ],
            ], 200, [
                'x-pagination-total-count' => ['2'],
                'x-pagination-current-page' => ['1'],
                'x-pagination-per-page' => ['20'],
                'x-pagination-page-count' => ['1'],
            ]),
        ]);

        $result = $this->offers->list();

        expect($result)->toBeInstanceOf(PaginatedResponse::class)
            ->and($result->items)->toHaveCount(2)
            ->and($result->totalItems)->toBe(2)
            ->and($result->currentPage)->toBe(1)
            ->and($result->perPage)->toBe(20)
            ->and($result->totalPages)->toBe(1)
            ->and($result->items->first())->toBeInstanceOf(OfferDTO::class)
            ->and($result->items->first()->id)->toBe(1)
            ->and($result->items->first()->title)->toBe('Test Offer 1');
    });

    it('can use OffersListRequest', function () {
        Http::fake([
            '*/api/v2/affiliate/offers*' => Http::response([
                'status' => 1,
                'info' => ['offers' => []],
            ], 200, [
                'x-pagination-total-count' => ['0'],
            ]),
        ]);

        $request = (new OffersListRequest)
            ->search('casino')
            ->countries([1, 2])
            ->onlyFeatured()
            ->page(1)
            ->perPage(20);

        $this->offers->list($request);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'search=casino') &&
                   str_contains($request->url(), 'countries=1%2C2') &&
                   str_contains($request->url(), 'onlyFeatured=yes') &&
                   str_contains($request->url(), 'page=1') &&
                   str_contains($request->url(), 'perPage=20');
        });
    });

    it('uses default request when none provided', function () {
        Http::fake([
            '*/api/v2/affiliate/offers*' => Http::response([
                'status' => 1,
                'info' => ['offers' => []],
            ], 200, [
                'x-pagination-total-count' => ['0'],
            ]),
        ]);

        $this->offers->list();

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'api/v2/affiliate/offers');
        });
    });
});

describe('Get Offer', function () {
    it('can get offer by ID', function () {
        Http::fake([
            '*/api/v2/affiliate/offers/123*' => Http::response([
                'status' => 1,
                'info' => [
                    'offer' => [
                        'id' => 123,
                        'title' => 'Test Offer',
                        'description' => 'Test description',
                        'advertiser_id' => null,
                        'image' => null,
                        'gt_included_ids' => null,
                        'gt_excluded_ids' => null,
                        'gt_filter_ids' => null,
                        'extended_targeting' => null,
                        'traffic_types' => null,
                        'is_featured' => 0,
                        'timezone' => null,
                        'currency' => 'USD',
                        'aff_epc' => null,
                        'ar' => null,
                        'cr' => null,
                        'visible_type' => null,
                        'questions' => null,
                        'ask_approval_questions' => null,
                        'tracking_domain_id' => null,
                        'traffic_types_selected' => null,
                        'tracking_domain' => null,
                        'goals' => [],
                        'targeting' => null,
                        'creatives_count' => 0,
                        'creatives' => [],
                        'links_count' => 0,
                        'links' => [],
                        'categories' => [],
                    ],
                ],
            ], 200),
        ]);

        $result = $this->offers->get(123);

        expect($result)->toBeInstanceOf(OfferDTO::class)
            ->and($result->id)->toBe(123)
            ->and($result->title)->toBe('Test Offer')
            ->and($result->description)->toBe('Test description')
            ->and($result->currency)->toBe('USD');
    });

    it('makes GET request to correct endpoint', function () {
        Http::fake([
            '*/api/v2/affiliate/offers/456*' => Http::response([
                'status' => 1,
                'info' => [
                    'offer' => [
                        'id' => 456,
                        'title' => 'Test',
                        'description' => null,
                        'advertiser_id' => null,
                        'image' => null,
                        'gt_included_ids' => null,
                        'gt_excluded_ids' => null,
                        'gt_filter_ids' => null,
                        'extended_targeting' => null,
                        'traffic_types' => null,
                        'is_featured' => 0,
                        'timezone' => null,
                        'currency' => null,
                        'aff_epc' => null,
                        'ar' => null,
                        'cr' => null,
                        'visible_type' => null,
                        'questions' => null,
                        'ask_approval_questions' => null,
                        'tracking_domain_id' => null,
                        'traffic_types_selected' => null,
                        'tracking_domain' => null,
                        'goals' => [],
                        'targeting' => null,
                        'creatives_count' => 0,
                        'creatives' => [],
                        'links_count' => 0,
                        'links' => [],
                        'categories' => [],
                    ],
                ],
            ], 200),
        ]);

        $this->offers->get(456);

        Http::assertSent(function ($request) {
            return $request->method() === 'GET' &&
                   str_contains($request->url(), 'api/v2/affiliate/offers/456');
        });
    });

    it('throws UnexpectedResponseException when offer is missing', function () {
        Http::fake([
            '*/api/v2/affiliate/offers/123*' => Http::response([
                'status' => 1,
                'info' => [],
            ], 200),
        ]);

        expect(fn () => $this->offers->get(123))
            ->toThrow(UnexpectedResponseException::class, 'Expected offer object in response');
    });

    it('throws UnexpectedResponseException when offer is not array', function () {
        Http::fake([
            '*/api/v2/affiliate/offers/123*' => Http::response([
                'status' => 1,
                'info' => [
                    'offer' => 'string',
                ],
            ], 200),
        ]);

        expect(fn () => $this->offers->get(123))
            ->toThrow(UnexpectedResponseException::class);
    });
});

describe('Download Asset', function () {
    it('can download offer asset', function () {
        $binaryContent = 'ZIP file content';

        Http::fake([
            '*/api/v2/affiliate/offers/123/assets/456/download*' => Http::response($binaryContent, 200),
        ]);

        $result = $this->offers->downloadAsset(123, 456);

        expect($result)->toBe($binaryContent);
    });

    it('makes GET request to correct endpoint', function () {
        Http::fake([
            '*/api/v2/affiliate/offers/123/assets/456/download*' => Http::response('content', 200),
        ]);

        $this->offers->downloadAsset(123, 456);

        Http::assertSent(function ($request) {
            return $request->method() === 'GET' &&
                   str_contains($request->url(), 'api/v2/affiliate/offers/123/assets/456/download');
        });
    });
});

describe('PaginatedResponse', function () {
    it('handles hasMorePages correctly', function () {
        Http::fake([
            '*/api/v2/affiliate/offers*' => Http::response([
                'status' => 1,
                'info' => ['offers' => []],
            ], 200, [
                'x-pagination-total-count' => ['100'],
                'x-pagination-current-page' => ['1'],
                'x-pagination-per-page' => ['20'],
                'x-pagination-page-count' => ['5'],
            ]),
        ]);

        $result = $this->offers->list();

        expect($result->hasMorePages())->toBeTrue();
    });

    it('hasMorePages returns false on last page', function () {
        Http::fake([
            '*/api/v2/affiliate/offers*' => Http::response([
                'status' => 1,
                'info' => ['offers' => []],
            ], 200, [
                'x-pagination-total-count' => ['100'],
                'x-pagination-current-page' => ['5'],
                'x-pagination-per-page' => ['20'],
                'x-pagination-page-count' => ['5'],
            ]),
        ]);

        $result = $this->offers->list();

        expect($result->hasMorePages())->toBeFalse();
    });
});
