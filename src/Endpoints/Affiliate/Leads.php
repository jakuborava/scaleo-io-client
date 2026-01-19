<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Endpoints\Affiliate;

use JakubOrava\ScaleoIoClient\BaseScaleoClient;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\LeadResponseDTO;
use JakubOrava\ScaleoIoClient\Exceptions\ApiErrorException;
use JakubOrava\ScaleoIoClient\Exceptions\AuthenticationException;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\Exceptions\ValidationException;

class Leads
{
    public function __construct(
        private readonly BaseScaleoClient $client
    ) {}

    /**
     * Record a lead
     *
     * @param  array<string, mixed>  $data
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function create(array $data): LeadResponseDTO
    {
        $result = $this->client->post('api/v2/affiliate/leads', $data, []);
        /** @var array<string, mixed> $response */
        $response = $result['data'];

        if (! is_array($response)) {
            throw new UnexpectedResponseException('Expected lead response object');
        }

        return LeadResponseDTO::fromArray($response);
    }

    /**
     * Record a lead by Offer ID
     *
     * @param  array<string, mixed>  $data
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function createByOfferId(array $data): LeadResponseDTO
    {
        $result = $this->client->post('api/v2/affiliate/leads-by-offer-id', $data, []);
        /** @var array<string, mixed> $response */
        $response = $result['data'];

        if (! is_array($response)) {
            throw new UnexpectedResponseException('Expected lead response object');
        }

        return LeadResponseDTO::fromArray($response);
    }

    /**
     * Record a lead by Offer ID (only with Success delivery)
     *
     * @param  array<string, mixed>  $data
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function createUpon(array $data): LeadResponseDTO
    {
        $result = $this->client->post('api/v2/affiliate/leads-upon', $data, []);
        /** @var array<string, mixed> $response */
        $response = $result['data'];

        if (! is_array($response)) {
            throw new UnexpectedResponseException('Expected lead response object');
        }

        return LeadResponseDTO::fromArray($response);
    }
}
