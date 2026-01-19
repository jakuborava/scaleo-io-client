<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient;

use JakubOrava\ScaleoIoClient\Endpoints\AffiliateEndpoints;

class ScaleoIoClient
{
    private BaseScaleoClient $client;

    public function __construct()
    {
        $this->client = new BaseScaleoClient;
    }

    public function affiliate(): AffiliateEndpoints
    {
        return new AffiliateEndpoints($this->client);
    }
}
