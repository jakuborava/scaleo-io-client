<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Endpoints\Common;

use JakubOrava\ScaleoIoClient\BaseScaleoClient;
use JakubOrava\ScaleoIoClient\DTO\Common\GoalTypeDTO;
use JakubOrava\ScaleoIoClient\DTO\Common\ListResponse;
use JakubOrava\ScaleoIoClient\Exceptions\ApiErrorException;
use JakubOrava\ScaleoIoClient\Exceptions\AuthenticationException;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\Exceptions\ValidationException;
use JakubOrava\ScaleoIoClient\Requests\BaseRequest;

class GoalTypes
{
    public function __construct(
        private readonly BaseScaleoClient $client
    ) {}

    /**
     * Get list of goal types
     *
     * @return ListResponse<GoalTypeDTO>
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function list(?BaseRequest $request = null): ListResponse
    {
        $request = $request ?? new BaseRequest;
        $queryParams = $request->toArray();

        $result = $this->client->get('api/v2/common/lists/goals-types', $queryParams);
        /** @var array<string, mixed> $response */
        $response = $result['data'];

        return ListResponse::fromResponse(
            response: $response,
            key: 'goals-types-list',
            mapper: fn (array $goalType): GoalTypeDTO => GoalTypeDTO::fromArray($goalType)
        );
    }
}
