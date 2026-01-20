<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Endpoints\Affiliate;

use Illuminate\Support\Collection;
use JakubOrava\ScaleoIoClient\BaseScaleoClient;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\NetworkSummaryItemDTO;
use JakubOrava\ScaleoIoClient\Exceptions\ApiErrorException;
use JakubOrava\ScaleoIoClient\Exceptions\AuthenticationException;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\Exceptions\ValidationException;
use JakubOrava\ScaleoIoClient\Requests\NetworkSummaryRequest;

class Dashboard
{
    public function __construct(
        private readonly BaseScaleoClient $client
    ) {}

    /**
     * Get network summary statistics
     *
     * @return Collection<int, NetworkSummaryItemDTO>
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function networkSummary(?NetworkSummaryRequest $request = null): Collection
    {
        $request = $request ?? new NetworkSummaryRequest;
        $queryParams = $request->toArray();

        $result = $this->client->post('api/v2/affiliate/dashboard/statistics/network-summary', [], $queryParams);
        /** @var array<int, mixed> $response */
        $response = $result['data'];

        if (! is_array($response)) {
            throw new UnexpectedResponseException('Expected array response for network summary');
        }

        $items = [];
        foreach ($response as $item) {
            if (! is_array($item)) {
                throw new UnexpectedResponseException('Expected array for network summary item');
            }

            /** @var array<string, mixed> $item */
            $items[] = NetworkSummaryItemDTO::fromArray($item);
        }

        return new Collection($items);
    }
}
