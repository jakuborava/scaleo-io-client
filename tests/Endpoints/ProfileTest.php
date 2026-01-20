<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\ProfileDTO;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\ScaleoIoClient;

beforeEach(function () {
    $client = new ScaleoIoClient(
        apiKey: 'test-api-key',
        baseUrl: 'https://test.scaletrk.com'
    );
    $this->profile = $client->affiliate()->profile();
});

describe('Get Profile', function () {
    it('can get affiliate profile', function () {
        Http::fake([
            '*/api/v2/affiliate/profile*' => Http::response([
                'status' => 1,
                'info' => [
                    'profile' => [
                        'id' => 123,
                        'email' => 'affiliate@example.com',
                        'firstname' => 'John',
                        'lastname' => 'Doe',
                        'image' => null,
                        'contacts' => null,
                        'status' => 1,
                        'api_status' => 1,
                        'api_key' => 'test-key',
                        'number_format_id' => 1,
                        'date_format_id' => 1,
                        'phone' => null,
                        'timezone' => 'UTC',
                        'role' => 'affiliate',
                        'company_name' => 'Test Company',
                        'payment_details' => null,
                        'custom_fields' => null,
                        'country' => null,
                        'region' => null,
                        'city' => null,
                        'address' => null,
                        'postal_code' => null,
                        'managers_assigned' => null,
                    ],
                ],
            ], 200),
        ]);

        $result = $this->profile->get();

        expect($result)->toBeInstanceOf(ProfileDTO::class)
            ->and($result->id)->toBe(123)
            ->and($result->email)->toBe('affiliate@example.com')
            ->and($result->firstname)->toBe('John')
            ->and($result->lastname)->toBe('Doe');
    });

    it('makes GET request to correct endpoint', function () {
        Http::fake([
            '*/api/v2/affiliate/profile*' => Http::response([
                'status' => 1,
                'info' => [
                    'profile' => [
                        'id' => 1,
                        'email' => 'test@test.com',
                        'firstname' => 'Test',
                        'lastname' => 'User',
                        'image' => null,
                        'contacts' => null,
                        'status' => 1,
                        'api_status' => 1,
                        'api_key' => null,
                        'number_format_id' => 1,
                        'date_format_id' => 1,
                        'phone' => null,
                        'timezone' => 'UTC',
                        'role' => 'affiliate',
                        'company_name' => null,
                        'payment_details' => null,
                        'custom_fields' => null,
                        'country' => null,
                        'region' => null,
                        'city' => null,
                        'address' => null,
                        'postal_code' => null,
                        'managers_assigned' => null,
                    ],
                ],
            ], 200),
        ]);

        $this->profile->get();

        Http::assertSent(function ($request) {
            return $request->method() === 'GET' &&
                   str_contains($request->url(), 'api/v2/affiliate/profile');
        });
    });

    it('throws UnexpectedResponseException when profile is missing', function () {
        Http::fake([
            '*/api/v2/affiliate/profile*' => Http::response([
                'status' => 1,
                'info' => [],
            ], 200),
        ]);

        expect(fn () => $this->profile->get())
            ->toThrow(UnexpectedResponseException::class, 'Expected profile object in response');
    });

    it('throws UnexpectedResponseException when profile is not array', function () {
        Http::fake([
            '*/api/v2/affiliate/profile*' => Http::response([
                'status' => 1,
                'info' => [
                    'profile' => 'string',
                ],
            ], 200),
        ]);

        expect(fn () => $this->profile->get())
            ->toThrow(UnexpectedResponseException::class);
    });
});
