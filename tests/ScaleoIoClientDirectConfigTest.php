<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use JakubOrava\ScaleoIoClient\Endpoints\AffiliateEndpoints;
use JakubOrava\ScaleoIoClient\ScaleoIoClient;

describe('Direct Configuration', function () {
    it('can be instantiated with API key and base URL', function () {
        $client = new ScaleoIoClient(
            apiKey: 'my-custom-key',
            baseUrl: 'https://custom.scaletrk.com'
        );

        expect($client)->toBeInstanceOf(ScaleoIoClient::class);
    });

    it('passes credentials to underlying BaseScaleoClient', function () {
        Http::fake([
            'https://custom.scaletrk.com/api/v2/affiliate/profile*' => Http::response([
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

        $client = new ScaleoIoClient(
            apiKey: 'my-custom-key',
            baseUrl: 'https://custom.scaletrk.com'
        );

        $client->affiliate()->profile()->get();

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'api-key=my-custom-key') &&
                   str_contains($request->url(), 'https://custom.scaletrk.com');
        });
    });

    it('provides affiliate endpoints with custom configuration', function () {
        $client = new ScaleoIoClient(
            apiKey: 'my-custom-key',
            baseUrl: 'https://custom.scaletrk.com'
        );

        $affiliate = $client->affiliate();

        expect($affiliate)->toBeInstanceOf(AffiliateEndpoints::class);
    });

    it('falls back to config when no parameters provided', function () {
        config()->set('scaleo-io-client.api_key', 'config-api-key');
        config()->set('scaleo-io-client.base_url', 'https://config.scaletrk.com');

        Http::fake([
            'https://config.scaletrk.com/api/v2/affiliate/profile*' => Http::response([
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

        $client = new ScaleoIoClient;

        $client->affiliate()->profile()->get();

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'api-key=config-api-key') &&
                   str_contains($request->url(), 'https://config.scaletrk.com');
        });
    });
});
