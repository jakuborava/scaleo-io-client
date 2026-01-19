<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Endpoints\Affiliate;

use JakubOrava\ScaleoIoClient\BaseScaleoClient;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\TraderDTO;
use JakubOrava\ScaleoIoClient\DTO\PaginatedResponse;
use JakubOrava\ScaleoIoClient\Exceptions\ApiErrorException;
use JakubOrava\ScaleoIoClient\Exceptions\AuthenticationException;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\Exceptions\ValidationException;
use JakubOrava\ScaleoIoClient\Requests\BaseRequest;

class Traders
{
    public function __construct(
        private readonly BaseScaleoClient $client
    ) {}

    /**
     * List all traders
     *
     * @return PaginatedResponse<TraderDTO>
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function list(?BaseRequest $request = null): PaginatedResponse
    {
        $request = $request ?? new BaseRequest;
        $queryParams = $request->toArray();

        $result = $this->client->get('api/v2/affiliate/traders', $queryParams);
        /** @var array<string, mixed> $response */
        $response = $result['data'];

        // Traders data is directly in response as an array
        if (! is_array($response)) {
            throw new UnexpectedResponseException('Expected traders array in response');
        }

        $items = [];
        foreach ($response as $trader) {
            if (! is_array($trader)) {
                throw new UnexpectedResponseException('Expected array for trader item');
            }

            /** @var array<string, mixed> $trader */
            $items[] = TraderDTO::fromArray($trader);
        }

        return PaginatedResponse::fromResponse(
            response: ['traders' => $items],
            itemsKey: 'traders',
            paginationHeaders: $result['headers'],
            mapper: fn (TraderDTO $trader): TraderDTO => $trader
        );
    }
}
