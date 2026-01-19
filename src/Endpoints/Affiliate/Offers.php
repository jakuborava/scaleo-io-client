<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Endpoints\Affiliate;

use JakubOrava\ScaleoIoClient\BaseScaleoClient;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\OfferDTO;
use JakubOrava\ScaleoIoClient\DTO\PaginatedResponse;
use JakubOrava\ScaleoIoClient\Exceptions\ApiErrorException;
use JakubOrava\ScaleoIoClient\Exceptions\AuthenticationException;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\Exceptions\ValidationException;
use JakubOrava\ScaleoIoClient\Requests\OffersListRequest;

class Offers
{
    public function __construct(
        private readonly BaseScaleoClient $client
    ) {}

    /**
     * List all offers
     *
     * @return PaginatedResponse<OfferDTO>
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function list(?OffersListRequest $request = null): PaginatedResponse
    {
        $request = $request ?? new OffersListRequest;
        $queryParams = $request->toArray();

        $result = $this->client->get('api/v2/affiliate/offers', $queryParams);
        /** @var array<string, mixed> $response */
        $response = $result['data'];

        return PaginatedResponse::fromResponse(
            response: $response,
            itemsKey: 'offers',
            paginationHeaders: $result['headers'],
            mapper: fn (array $offer): OfferDTO => OfferDTO::fromArray($offer)
        );
    }

    /**
     * Get offer details by ID
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function get(int $offerId): OfferDTO
    {
        $result = $this->client->get("api/v2/affiliate/offers/$offerId", []);
        /** @var array<string, mixed> $response */
        $response = $result['data'];

        $offerData = $response['offer'] ?? null;
        if (! is_array($offerData)) {
            throw new UnexpectedResponseException('Expected offer object in response');
        }

        /** @var array<string, mixed> $offerData */
        return OfferDTO::fromArray($offerData);
    }

    /**
     * Download asset (XML feed, etc.)
     *
     * @throws AuthenticationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function downloadAsset(int $offerId, int $assetId): string
    {
        return $this->client->download("api/v2/affiliate/offers/$offerId/assets/$assetId/download", []);
    }
}
