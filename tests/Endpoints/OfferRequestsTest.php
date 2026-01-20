<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\OfferRequestDTO;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\ScaleoIoClient;

beforeEach(function () {
    $client = new ScaleoIoClient(
        apiKey: 'test-api-key',
        baseUrl: 'https://test.scaletrk.com'
    );
    $this->offerRequests = $client->affiliate()->offerRequests();
});

describe('Create Offer Request', function () {
    it('can create offer request', function () {
        Http::fake([
            '*/api/v2/affiliate/offers-requests*' => Http::response([
                'status' => 1,
                'info' => [],
            ], 200),
        ]);

        $this->offerRequests->create(123);

        Http::assertSent(function ($request) {
            return $request->method() === 'POST' &&
                   str_contains($request->url(), 'api/v2/affiliate/offers-requests') &&
                   $request['offer_id'] === 123;
        });
    });

    it('can create offer request with additional info', function () {
        Http::fake([
            '*/api/v2/affiliate/offers-requests*' => Http::response([
                'status' => 1,
                'info' => [],
            ], 200),
        ]);

        $this->offerRequests->create(123, 'Additional information');

        Http::assertSent(function ($request) {
            return $request['offer_id'] === 123 &&
                   $request['additional_info'] === 'Additional information';
        });
    });

    it('does not include additional_info when null', function () {
        Http::fake([
            '*/api/v2/affiliate/offers-requests*' => Http::response([
                'status' => 1,
                'info' => [],
            ], 200),
        ]);

        $this->offerRequests->create(123, null);

        Http::assertSent(function ($request) {
            return $request['offer_id'] === 123 &&
                   ! isset($request['additional_info']);
        });
    });
});

describe('Get Pending Requests', function () {
    it('can get pending offer requests', function () {
        Http::fake([
            '*/api/v2/affiliate/offers-requests/pending*' => Http::response([
                'status' => 1,
                'info' => [
                    'pending-requests' => [
                        [
                            'id' => 1,
                            'offer_id' => 123,
                            'offer_name' => 'Test Offer 1',
                            'affiliate_id' => 1,
                            'affiliate_name' => 'Test Affiliate',
                            'status' => 'pending',
                            'questions' => null,
                            'answer' => null,
                            'created' => 1704067200,
                            'updated' => 1704067200,
                        ],
                        [
                            'id' => 2,
                            'offer_id' => 456,
                            'offer_name' => 'Test Offer 2',
                            'affiliate_id' => 1,
                            'affiliate_name' => 'Test Affiliate',
                            'status' => 'pending',
                            'questions' => null,
                            'answer' => null,
                            'created' => 1704153600,
                            'updated' => 1704153600,
                        ],
                    ],
                ],
            ], 200),
        ]);

        $result = $this->offerRequests->pending();

        expect($result)->toBeInstanceOf(Collection::class)
            ->and($result)->toHaveCount(2)
            ->and($result->first())->toBeInstanceOf(OfferRequestDTO::class)
            ->and($result->first()->id)->toBe(1)
            ->and($result->first()->offerId)->toBe(123);
    });

    it('makes GET request to correct endpoint', function () {
        Http::fake([
            '*/api/v2/affiliate/offers-requests/pending*' => Http::response([
                'status' => 1,
                'info' => [
                    'pending-requests' => [],
                ],
            ], 200),
        ]);

        $this->offerRequests->pending();

        Http::assertSent(function ($request) {
            return $request->method() === 'GET' &&
                   str_contains($request->url(), 'api/v2/affiliate/offers-requests/pending');
        });
    });

    it('throws UnexpectedResponseException when pending-requests is not array', function () {
        Http::fake([
            '*/api/v2/affiliate/offers-requests/pending*' => Http::response([
                'status' => 1,
                'info' => [
                    'pending-requests' => 'string',
                ],
            ], 200),
        ]);

        expect(fn () => $this->offerRequests->pending())
            ->toThrow(UnexpectedResponseException::class);
    });

    it('throws UnexpectedResponseException when item is not array', function () {
        Http::fake([
            '*/api/v2/affiliate/offers-requests/pending*' => Http::response([
                'status' => 1,
                'info' => [
                    'pending-requests' => ['string instead of array'],
                ],
            ], 200),
        ]);

        expect(fn () => $this->offerRequests->pending())
            ->toThrow(UnexpectedResponseException::class);
    });

    it('returns empty collection when no pending requests', function () {
        Http::fake([
            '*/api/v2/affiliate/offers-requests/pending*' => Http::response([
                'status' => 1,
                'info' => [
                    'pending-requests' => [],
                ],
            ], 200),
        ]);

        $result = $this->offerRequests->pending();

        expect($result)->toBeInstanceOf(Collection::class)
            ->and($result)->toHaveCount(0);
    });
});
