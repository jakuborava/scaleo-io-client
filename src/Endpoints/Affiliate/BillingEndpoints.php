<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Endpoints\Affiliate;

use JakubOrava\ScaleoIoClient\BaseScaleoClient;
use JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Billing\Balances;
use JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Billing\Invoices;
use JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Billing\PaymentMethods;
use JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Billing\PaymentRequest;
use JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Billing\Preferences;

class BillingEndpoints
{
    public function __construct(
        private readonly BaseScaleoClient $client
    ) {}

    public function balances(): Balances
    {
        return new Balances($this->client);
    }

    public function paymentMethods(): PaymentMethods
    {
        return new PaymentMethods($this->client);
    }

    public function invoices(): Invoices
    {
        return new Invoices($this->client);
    }

    public function paymentRequest(): PaymentRequest
    {
        return new PaymentRequest($this->client);
    }

    public function preferences(): Preferences
    {
        return new Preferences($this->client);
    }
}
