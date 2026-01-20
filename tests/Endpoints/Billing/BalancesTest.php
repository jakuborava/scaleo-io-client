<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\BalanceDTO;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\ScaleoIoClient;

beforeEach(function () {
    $client = new ScaleoIoClient(
        apiKey: 'test-api-key',
        baseUrl: 'https://test.scaletrk.com'
    );
    $this->balances = $client->affiliate()->billing()->balances();
});

describe('Get Balance', function () {
    it('can get affiliate balance', function () {
        Http::fake([
            '*/api/v2/affiliate/aff-billing/balances*' => Http::response([
                'status' => 1,
                'info' => [
                    'billing-affiliate-balance' => [
                        'currency' => 'USD',
                        'approved_balance' => '1250.50',
                        'pending_balance' => '450.00',
                        'balance_due' => '1700.50',
                        'balance_due_by_currencies' => [],
                        'available_advance' => 0,
                        'referral_balance' => '0.00',
                    ],
                ],
            ], 200),
        ]);

        $result = $this->balances->get();

        expect($result)->toBeInstanceOf(BalanceDTO::class)
            ->and($result->approvedBalance)->toBe('1250.50')
            ->and($result->pendingBalance)->toBe('450.00')
            ->and($result->currency)->toBe('USD');
    });

    it('makes GET request to correct endpoint', function () {
        Http::fake([
            '*/api/v2/affiliate/aff-billing/balances*' => Http::response([
                'status' => 1,
                'info' => [
                    'billing-affiliate-balance' => [
                        'currency' => 'USD',
                        'approved_balance' => '0.00',
                        'pending_balance' => '0.00',
                        'balance_due' => '0.00',
                        'balance_due_by_currencies' => [],
                        'available_advance' => 0,
                        'referral_balance' => '0.00',
                    ],
                ],
            ], 200),
        ]);

        $this->balances->get();

        Http::assertSent(function ($request) {
            return $request->method() === 'GET' &&
                   str_contains($request->url(), 'api/v2/affiliate/aff-billing/balances');
        });
    });

    it('throws UnexpectedResponseException when balance is missing', function () {
        Http::fake([
            '*/api/v2/affiliate/aff-billing/balances*' => Http::response([
                'status' => 1,
                'info' => [],
            ], 200),
        ]);

        expect(fn () => $this->balances->get())
            ->toThrow(UnexpectedResponseException::class, 'Expected billing-affiliate-balance object in response');
    });

    it('throws UnexpectedResponseException when balance is not array', function () {
        Http::fake([
            '*/api/v2/affiliate/aff-billing/balances*' => Http::response([
                'status' => 1,
                'info' => [
                    'billing-affiliate-balance' => 'string',
                ],
            ], 200),
        ]);

        expect(fn () => $this->balances->get())
            ->toThrow(UnexpectedResponseException::class);
    });
});
