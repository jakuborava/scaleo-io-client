<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Endpoints\Affiliate;

use JakubOrava\ScaleoIoClient\BaseScaleoClient;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\PostbackDTO;
use JakubOrava\ScaleoIoClient\DTO\PaginatedResponse;
use JakubOrava\ScaleoIoClient\Exceptions\ApiErrorException;
use JakubOrava\ScaleoIoClient\Exceptions\AuthenticationException;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\Exceptions\ValidationException;
use JakubOrava\ScaleoIoClient\Requests\BaseRequest;

class Postbacks
{
    public function __construct(
        private readonly BaseScaleoClient $client
    ) {}

    /**
     * List all postbacks
     *
     * @return PaginatedResponse<PostbackDTO>
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

        $result = $this->client->get('api/v2/affiliate/postbacks', $queryParams);
        /** @var array<string, mixed> $response */
        $response = $result['data'];

        return PaginatedResponse::fromResponse(
            response: $response,
            itemsKey: 'postbacks',
            paginationHeaders: $result['headers'],
            mapper: fn (array $postback): PostbackDTO => PostbackDTO::fromArray($postback)
        );
    }
}
