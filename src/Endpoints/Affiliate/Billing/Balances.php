<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Billing;

use JakubOrava\ScaleoIoClient\BaseScaleoClient;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\BalanceDTO;
use JakubOrava\ScaleoIoClient\Exceptions\ApiErrorException;
use JakubOrava\ScaleoIoClient\Exceptions\AuthenticationException;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\Exceptions\ValidationException;

class Balances
{
    public function __construct(
        private readonly BaseScaleoClient $client
    ) {}

    /**
     * Get affiliate's balance
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function get(): BalanceDTO
    {
        $result = $this->client->get('api/v2/affiliate/aff-billing/balances', []);
        /** @var array<string, mixed> $response */
        $response = $result['data'];

        $balanceData = $response['billing-affiliate-balance'] ?? null;
        if (! is_array($balanceData)) {
            throw new UnexpectedResponseException('Expected billing-affiliate-balance object in response');
        }

        /** @var array<string, mixed> $balanceData */
        return BalanceDTO::fromArray($balanceData);
    }
}
