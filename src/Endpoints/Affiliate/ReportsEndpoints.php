<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Endpoints\Affiliate;

use JakubOrava\ScaleoIoClient\BaseScaleoClient;
use JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Reports\Clicks;
use JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Reports\Conversions;
use JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Reports\Referrals;
use JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Reports\Statistics;

class ReportsEndpoints
{
    public function __construct(
        private readonly BaseScaleoClient $client
    ) {}

    public function statistics(): Statistics
    {
        return new Statistics($this->client);
    }

    public function conversions(): Conversions
    {
        return new Conversions($this->client);
    }

    public function clicks(): Clicks
    {
        return new Clicks($this->client);
    }

    public function referrals(): Referrals
    {
        return new Referrals($this->client);
    }
}
