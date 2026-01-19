<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Billing;

use JakubOrava\ScaleoIoClient\BaseScaleoClient;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\BillingPreferencesDTO;
use JakubOrava\ScaleoIoClient\Exceptions\ApiErrorException;
use JakubOrava\ScaleoIoClient\Exceptions\AuthenticationException;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\Exceptions\ValidationException;

class Preferences
{
    public function __construct(
        private readonly BaseScaleoClient $client
    ) {}

    /**
     * Get billing preferences
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function get(): BillingPreferencesDTO
    {
        $result = $this->client->get('api/v2/affiliate/aff-billing/preferences', []);
        /** @var array<string, mixed> $response */
        $response = $result['data'];

        $preferencesData = $response['affiliate-billing-details'] ?? null;
        if (! is_array($preferencesData)) {
            throw new UnexpectedResponseException('Expected affiliate-billing-details object in response');
        }

        /** @var array<string, mixed> $preferencesData */
        return BillingPreferencesDTO::fromArray($preferencesData);
    }
}
