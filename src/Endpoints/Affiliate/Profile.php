<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Endpoints\Affiliate;

use JakubOrava\ScaleoIoClient\BaseScaleoClient;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\ProfileDTO;
use JakubOrava\ScaleoIoClient\Exceptions\ApiErrorException;
use JakubOrava\ScaleoIoClient\Exceptions\AuthenticationException;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\Exceptions\ValidationException;

class Profile
{
    public function __construct(
        private readonly BaseScaleoClient $client
    ) {}

    /**
     * Get affiliate profile
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function get(): ProfileDTO
    {
        $result = $this->client->get('api/v2/affiliate/profile', []);
        /** @var array<string, mixed> $response */
        $response = $result['data'];

        $profileData = $response['profile'] ?? null;
        if (! is_array($profileData)) {
            throw new UnexpectedResponseException('Expected profile object in response');
        }

        /** @var array<string, mixed> $profileData */
        return ProfileDTO::fromArray($profileData);
    }
}
