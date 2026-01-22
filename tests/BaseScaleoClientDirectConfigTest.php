<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use JakubOrava\ScaleoIoClient\BaseScaleoClient;

describe('Direct Configuration', function () {
    it('can be instantiated with API key and base URL', function () {
        $client = new BaseScaleoClient(
            apiKey: 'my-custom-key',
            baseUrl: 'https://custom.scaletrk.com'
        );

        expect($client)->toBeInstanceOf(BaseScaleoClient::class);
    });

    it('uses provided API key in requests', function () {
        Http::fake([
            'https://custom.scaletrk.com/api/test*' => Http::response([
                'status' => 1,
                'info' => ['data' => 'test'],
            ], 200),
        ]);

        $client = new BaseScaleoClient(
            apiKey: 'my-custom-key',
            baseUrl: 'https://custom.scaletrk.com'
        );

        $client->get('/api/test');

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'api-key=my-custom-key');
        });
    });

    it('uses provided base URL in requests', function () {
        Http::fake([
            'https://custom.scaletrk.com/api/test*' => Http::response([
                'status' => 1,
                'info' => ['data' => 'test'],
            ], 200),
        ]);

        $client = new BaseScaleoClient(
            apiKey: 'my-custom-key',
            baseUrl: 'https://custom.scaletrk.com'
        );

        $client->get('/api/test');

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'https://custom.scaletrk.com/api/test');
        });
    });

    it('falls back to config when no parameters provided', function () {
        config()->set('scaleo-io-client.api_key', 'config-api-key');
        config()->set('scaleo-io-client.base_url', 'https://config.scaletrk.com');

        Http::fake([
            'https://config.scaletrk.com/api/test*' => Http::response([
                'status' => 1,
                'info' => ['data' => 'test'],
            ], 200),
        ]);

        $client = new BaseScaleoClient;

        $client->get('/api/test');

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'api-key=config-api-key') &&
                   str_contains($request->url(), 'https://config.scaletrk.com/api/test');
        });
    });

    it('prioritizes constructor parameters over config', function () {
        config()->set('scaleo-io-client.api_key', 'config-api-key');
        config()->set('scaleo-io-client.base_url', 'https://config.scaletrk.com');

        Http::fake([
            'https://direct.scaletrk.com/api/test*' => Http::response([
                'status' => 1,
                'info' => ['data' => 'test'],
            ], 200),
        ]);

        $client = new BaseScaleoClient(
            apiKey: 'direct-api-key',
            baseUrl: 'https://direct.scaletrk.com'
        );

        $client->get('/api/test');

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'api-key=direct-api-key') &&
                   str_contains($request->url(), 'https://direct.scaletrk.com/api/test');
        });
    });
});
