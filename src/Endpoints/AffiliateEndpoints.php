<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Endpoints;

use JakubOrava\ScaleoIoClient\BaseScaleoClient;
use JakubOrava\ScaleoIoClient\Endpoints\Affiliate\BillingEndpoints;
use JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Dashboard;
use JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Leads;
use JakubOrava\ScaleoIoClient\Endpoints\Affiliate\OfferRequests;
use JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Offers;
use JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Players;
use JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Postbacks;
use JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Profile;
use JakubOrava\ScaleoIoClient\Endpoints\Affiliate\ReportsEndpoints;
use JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Traders;

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

    public function offerRequests(): OfferRequests
    {
        return new OfferRequests($this->client);
    }

    public function reports(): ReportsEndpoints
    {
        return new ReportsEndpoints($this->client);
    }

    public function billing(): BillingEndpoints
    {
        return new BillingEndpoints($this->client);
    }

    public function profile(): Profile
    {
        return new Profile($this->client);
    }

    public function postbacks(): Postbacks
    {
        return new Postbacks($this->client);
    }

    public function leads(): Leads
    {
        return new Leads($this->client);
    }

    public function players(): Players
    {
        return new Players($this->client);
    }

    public function traders(): Traders
    {
        return new Traders($this->client);
    }
}
