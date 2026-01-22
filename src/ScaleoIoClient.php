<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient;

use JakubOrava\ScaleoIoClient\Endpoints\AffiliateEndpoints;
use JakubOrava\ScaleoIoClient\Endpoints\CommonEndpoints;

class ScaleoIoClient
{
    private BaseScaleoClient $client;

    public function __construct(?string $apiKey = null, ?string $baseUrl = null)
    {
        $this->client = new BaseScaleoClient($apiKey, $baseUrl);
    }

    public function affiliate(): AffiliateEndpoints
    {
        return new AffiliateEndpoints($this->client);
    }

    public function common(): CommonEndpoints
    {
        return new CommonEndpoints($this->client);
    }
}
