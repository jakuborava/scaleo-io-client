<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Reports;

use Illuminate\Support\Collection;
use JakubOrava\ScaleoIoClient\BaseScaleoClient;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\ColumnOptionDTO;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\ConversionDTO;
use JakubOrava\ScaleoIoClient\DTO\PaginatedResponse;
use JakubOrava\ScaleoIoClient\Exceptions\ApiErrorException;
use JakubOrava\ScaleoIoClient\Exceptions\AuthenticationException;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\Exceptions\ValidationException;
use JakubOrava\ScaleoIoClient\Requests\ConversionsReportRequest;

class Conversions
{
    public function __construct(
        private readonly BaseScaleoClient $client
    ) {}

    /**
     * Get conversions report
     *
     * @return PaginatedResponse<ConversionDTO>
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function list(ConversionsReportRequest $request): PaginatedResponse
    {
        $queryParams = $request->toArray();
        $bodyParams = $request->toBodyArray();

        $result = $this->client->post('api/v2/affiliate/reports/conversions', $bodyParams, $queryParams);
        /** @var array<string, mixed> $response */
        $response = $result['data'];

        $transactionsData = $response['transactions'] ?? [];
        if (! is_array($transactionsData)) {
            throw new UnexpectedResponseException('Expected transactions array in response');
        }

        return PaginatedResponse::fromResponse(
            response: ['transactions' => $transactionsData],
            itemsKey: 'transactions',
            paginationHeaders: $result['headers'],
            mapper: fn (array $conversion): ConversionDTO => ConversionDTO::fromArray($conversion)
        );
    }

    /**
     * Get available column options
     *
     * @return Collection<int, ColumnOptionDTO>
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function options(?string $filters = null): Collection
    {
        $queryParams = [];
        if ($filters !== null) {
            $queryParams['filters'] = $filters;
        }

        $result = $this->client->get('api/v2/affiliate/reports/conversions/options', $queryParams);
        /** @var array<string, mixed> $response */
        $response = $result['data'];

        $columnsData = $response['columns-list'] ?? [];
        if (! is_array($columnsData)) {
            throw new UnexpectedResponseException('Expected columns-list array in response');
        }

        $items = [];
        foreach ($columnsData as $column) {
            if (! is_array($column)) {
                throw new UnexpectedResponseException('Expected array for column option item');
            }

            /** @var array<string, mixed> $column */
            $items[] = ColumnOptionDTO::fromArray($column);
        }

        return new Collection($items);
    }
}
