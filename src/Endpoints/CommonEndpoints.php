<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Endpoints;

use JakubOrava\ScaleoIoClient\BaseScaleoClient;
use JakubOrava\ScaleoIoClient\Endpoints\Common\Countries;
use JakubOrava\ScaleoIoClient\Endpoints\Common\GoalTypes;
use JakubOrava\ScaleoIoClient\Endpoints\Common\Tags;

class CommonEndpoints
{
    public function __construct(
        private readonly BaseScaleoClient $client
    ) {}

    public function countries(): Countries
    {
        return new Countries($this->client);
    }

    public function tags(): Tags
    {
        return new Tags($this->client);
    }

    public function goalTypes(): GoalTypes
    {
        return new GoalTypes($this->client);
    }
}
