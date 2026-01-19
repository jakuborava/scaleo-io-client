<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Endpoints\Affiliate;

use Illuminate\Support\Collection;
use JakubOrava\ScaleoIoClient\BaseScaleoClient;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\OfferRequestDTO;
use JakubOrava\ScaleoIoClient\Exceptions\ApiErrorException;
use JakubOrava\ScaleoIoClient\Exceptions\AuthenticationException;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\Exceptions\ValidationException;

class OfferRequests
{
    public function __construct(
        private readonly BaseScaleoClient $client
    ) {}

    /**
     * Create an offer request
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function create(int $offerId, ?string $additionalInfo = null): void
    {
        $data = [
            'offer_id' => $offerId,
        ];

        if ($additionalInfo !== null) {
            $data['additional_info'] = $additionalInfo;
        }

        $this->client->post('api/v2/affiliate/offers-requests', $data);
    }

    /**
     * Get pending offer requests
     *
     * @return Collection<int, OfferRequestDTO>
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function pending(): Collection
    {
        $result = $this->client->get('api/v2/affiliate/offers-requests/pending', []);
        /** @var array<string, mixed> $response */
        $response = $result['data'];

        $requestsData = $response['pending-requests'] ?? [];
        if (! is_array($requestsData)) {
            throw new UnexpectedResponseException('Expected pending-requests array in response');
        }

        $items = [];
        foreach ($requestsData as $item) {
            if (! is_array($item)) {
                throw new UnexpectedResponseException('Expected array for offer request item');
            }

            /** @var array<string, mixed> $item */
            $items[] = OfferRequestDTO::fromArray($item);
        }

        return new Collection($items);
    }
}
