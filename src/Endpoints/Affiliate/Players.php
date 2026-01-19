<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Endpoints\Affiliate;

use JakubOrava\ScaleoIoClient\BaseScaleoClient;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\PlayerDTO;
use JakubOrava\ScaleoIoClient\DTO\PaginatedResponse;
use JakubOrava\ScaleoIoClient\Exceptions\ApiErrorException;
use JakubOrava\ScaleoIoClient\Exceptions\AuthenticationException;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\Exceptions\ValidationException;
use JakubOrava\ScaleoIoClient\Requests\BaseRequest;

class Players
{
    public function __construct(
        private readonly BaseScaleoClient $client
    ) {}

    /**
     * List all players
     *
     * @return PaginatedResponse<PlayerDTO>
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

        $result = $this->client->get('api/v2/affiliate/players', $queryParams);
        /** @var array<string, mixed> $response */
        $response = $result['data'];

        // Players data is directly in response as an array
        if (! is_array($response)) {
            throw new UnexpectedResponseException('Expected players array in response');
        }

        $items = [];
        foreach ($response as $player) {
            if (! is_array($player)) {
                throw new UnexpectedResponseException('Expected array for player item');
            }

            /** @var array<string, mixed> $player */
            $items[] = PlayerDTO::fromArray($player);
        }

        return PaginatedResponse::fromResponse(
            response: ['players' => $items],
            itemsKey: 'players',
            paginationHeaders: $result['headers'],
            mapper: fn (PlayerDTO $player): PlayerDTO => $player
        );
    }
}
