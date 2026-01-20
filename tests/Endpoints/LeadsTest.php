<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\LeadResponseDTO;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\ScaleoIoClient;

beforeEach(function () {
    $client = new ScaleoIoClient(
        apiKey: 'test-api-key',
        baseUrl: 'https://test.scaletrk.com'
    );
    $this->leads = $client->affiliate()->leads();
});

describe('Create Lead', function () {
    it('can create a lead', function () {
        Http::fake([
            '*/api/v2/affiliate/leads' => Http::response([
                'status' => 1,
                'info' => [
                    'status' => 'success',
                    'message' => 'Lead created',
                    'lead_id' => 'lead123',
                    'transaction_id' => 'trans456',
                    'conv_status' => 'approved',
                    'affiliate_id' => 789,
                    'offer_id' => 456,
                    'goal_id' => 1,
                ],
            ], 200),
        ]);

        $leadData = [
            'offer_hash' => 'xyz789',
            'click_id' => 'abc123',
            'email' => 'customer@example.com',
        ];

        $result = $this->leads->create($leadData);

        expect($result)->toBeInstanceOf(LeadResponseDTO::class)
            ->and($result->status)->toBe('success')
            ->and($result->message)->toBe('Lead created')
            ->and($result->leadId)->toBe('lead123');
    });

    it('makes POST request to correct endpoint', function () {
        Http::fake([
            '*/api/v2/affiliate/leads' => Http::response([
                'status' => 1,
                'info' => [
                    'status' => 'success',
                    'message' => 'Lead created',
                    'lead_id' => 'lead123',
                    'transaction_id' => 'trans456',
                    'conv_status' => 'approved',
                    'affiliate_id' => 1,
                    'offer_id' => 1,
                    'goal_id' => 1,
                ],
            ], 200),
        ]);

        $this->leads->create(['offer_hash' => 'test']);

        Http::assertSent(function ($request) {
            return $request->method() === 'POST' &&
                   str_contains($request->url(), 'api/v2/affiliate/leads') &&
                   ! str_contains($request->url(), 'by-offer-id') &&
                   ! str_contains($request->url(), 'upon');
        });
    });

    it('sends lead data in request body', function () {
        Http::fake([
            '*/api/v2/affiliate/leads' => Http::response([
                'status' => 1,
                'info' => [
                    'status' => 'success',
                    'message' => 'Lead created',
                    'lead_id' => 'lead123',
                    'transaction_id' => 'trans456',
                    'conv_status' => 'approved',
                    'affiliate_id' => 1,
                    'offer_id' => 1,
                    'goal_id' => 1,
                ],
            ], 200),
        ]);

        $leadData = [
            'offer_hash' => 'xyz789',
            'click_id' => 'abc123',
            'email' => 'customer@example.com',
        ];

        $this->leads->create($leadData);

        Http::assertSent(function ($request) use ($leadData) {
            return $request['offer_hash'] === $leadData['offer_hash'] &&
                   $request['click_id'] === $leadData['click_id'] &&
                   $request['email'] === $leadData['email'];
        });
    });

    it('throws UnexpectedResponseException on invalid response', function () {
        Http::fake([
            '*/api/v2/affiliate/leads' => Http::response([
                'status' => 1,
                'info' => 'string',
            ], 200),
        ]);

        expect(fn () => $this->leads->create(['offer_hash' => 'test']))
            ->toThrow(UnexpectedResponseException::class);
    });
});

describe('Create Lead by Offer ID', function () {
    it('can create a lead by offer ID', function () {
        Http::fake([
            '*/api/v2/affiliate/leads-by-offer-id*' => Http::response([
                'status' => 1,
                'info' => [
                    'status' => 'success',
                    'message' => 'Lead created by offer ID',
                    'lead_id' => 'lead456',
                    'transaction_id' => 'trans789',
                    'conv_status' => 'pending',
                    'affiliate_id' => 789,
                    'offer_id' => 123,
                    'goal_id' => 2,
                ],
            ], 200),
        ]);

        $leadData = [
            'offer_id' => 123,
            'click_id' => 'def456',
            'email' => 'customer@example.com',
        ];

        $result = $this->leads->createByOfferId($leadData);

        expect($result)->toBeInstanceOf(LeadResponseDTO::class)
            ->and($result->status)->toBe('success')
            ->and($result->leadId)->toBe('lead456')
            ->and($result->convStatus)->toBe('pending');
    });

    it('makes POST request to correct endpoint', function () {
        Http::fake([
            '*/api/v2/affiliate/leads-by-offer-id*' => Http::response([
                'status' => 1,
                'info' => [
                    'status' => 'success',
                    'message' => 'Lead created',
                    'lead_id' => 'lead123',
                    'transaction_id' => 'trans456',
                    'conv_status' => 'approved',
                    'affiliate_id' => 1,
                    'offer_id' => 123,
                    'goal_id' => 1,
                ],
            ], 200),
        ]);

        $this->leads->createByOfferId(['offer_id' => 123]);

        Http::assertSent(function ($request) {
            return $request->method() === 'POST' &&
                   str_contains($request->url(), 'api/v2/affiliate/leads-by-offer-id');
        });
    });

    it('throws UnexpectedResponseException on invalid response', function () {
        Http::fake([
            '*/api/v2/affiliate/leads-by-offer-id*' => Http::response([
                'status' => 1,
                'info' => 'string',
            ], 200),
        ]);

        expect(fn () => $this->leads->createByOfferId(['offer_id' => 123]))
            ->toThrow(UnexpectedResponseException::class);
    });
});

describe('Create Lead Upon', function () {
    it('can create a lead upon (success delivery)', function () {
        Http::fake([
            '*/api/v2/affiliate/leads-upon*' => Http::response([
                'status' => 1,
                'info' => [
                    'status' => 'success',
                    'message' => 'Lead created upon success',
                    'lead_id' => 'lead789',
                    'transaction_id' => 'trans999',
                    'conv_status' => 'approved',
                    'affiliate_id' => 999,
                    'offer_id' => 789,
                    'goal_id' => 3,
                ],
            ], 200),
        ]);

        $leadData = [
            'offer_hash' => 'xyz123',
            'click_id' => 'ghi789',
            'email' => 'customer@example.com',
        ];

        $result = $this->leads->createUpon($leadData);

        expect($result)->toBeInstanceOf(LeadResponseDTO::class)
            ->and($result->status)->toBe('success')
            ->and($result->leadId)->toBe('lead789')
            ->and($result->convStatus)->toBe('approved');
    });

    it('makes POST request to correct endpoint', function () {
        Http::fake([
            '*/api/v2/affiliate/leads-upon*' => Http::response([
                'status' => 1,
                'info' => [
                    'status' => 'success',
                    'message' => 'Lead created',
                    'lead_id' => 'lead123',
                    'transaction_id' => 'trans456',
                    'conv_status' => 'approved',
                    'affiliate_id' => 1,
                    'offer_id' => 1,
                    'goal_id' => 1,
                ],
            ], 200),
        ]);

        $this->leads->createUpon(['offer_hash' => 'test']);

        Http::assertSent(function ($request) {
            return $request->method() === 'POST' &&
                   str_contains($request->url(), 'api/v2/affiliate/leads-upon');
        });
    });

    it('throws UnexpectedResponseException on invalid response', function () {
        Http::fake([
            '*/api/v2/affiliate/leads-upon*' => Http::response([
                'status' => 1,
                'info' => 'string',
            ], 200),
        ]);

        expect(fn () => $this->leads->createUpon(['offer_hash' => 'test']))
            ->toThrow(UnexpectedResponseException::class);
    });
});
