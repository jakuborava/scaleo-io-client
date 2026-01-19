<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Endpoints;

use JakubOrava\ScaleoIoClient\BaseScaleoClient;
use JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Dashboard;
use JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Offers;

class AffiliateEndpoints
{
    public function __construct(
        private readonly BaseScaleoClient $client
    ) {}

    public function dashboard(): Dashboard
    {
        return new Dashboard($this->client);
    }

    public function offers(): Offers
    {
        return new Offers($this->client);
    }

    // TODO: Add endpoint methods as they are implemented
    // public function offerRequests(): OfferRequests
    // public function reports(): ReportsEndpoints
    // public function billing(): BillingEndpoints
    // public function profile(): Profile
    // public function postbacks(): Postbacks
    // public function leads(): Leads
    // public function players(): Players
    // public function traders(): Traders
}
