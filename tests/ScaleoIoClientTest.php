<?php

declare(strict_types=1);

use JakubOrava\ScaleoIoClient\Endpoints\AffiliateEndpoints;
use JakubOrava\ScaleoIoClient\ScaleoIoClient;

beforeEach(function () {
    $this->client = new ScaleoIoClient(
        apiKey: 'test-api-key',
        baseUrl: 'https://test.scaletrk.com'
    );
});

it('can be instantiated', function () {
    expect($this->client)->toBeInstanceOf(ScaleoIoClient::class);
});

it('provides affiliate endpoints', function () {
    $affiliate = $this->client->affiliate();

    expect($affiliate)->toBeInstanceOf(AffiliateEndpoints::class);
});

it('returns the same affiliate endpoint instance', function () {
    $affiliate1 = $this->client->affiliate();
    $affiliate2 = $this->client->affiliate();

    expect($affiliate1)->not->toBe($affiliate2); // Each call creates a new instance
});
