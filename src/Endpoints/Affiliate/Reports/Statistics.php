<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Reports;

use Illuminate\Support\Collection;
use JakubOrava\ScaleoIoClient\BaseScaleoClient;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\BreakdownOptionDTO;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\ColumnOptionDTO;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\StatisticsReportDTO;
use JakubOrava\ScaleoIoClient\DTO\PaginatedResponse;
use JakubOrava\ScaleoIoClient\Exceptions\ApiErrorException;
use JakubOrava\ScaleoIoClient\Exceptions\AuthenticationException;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\Exceptions\ValidationException;
use JakubOrava\ScaleoIoClient\Requests\StatisticsReportRequest;

class Statistics
{
    public function __construct(
        private readonly BaseScaleoClient $client
    ) {}

    /**
     * Get statistics report
     *
     * @return PaginatedResponse<StatisticsReportDTO>
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function list(StatisticsReportRequest $request): PaginatedResponse
    {
        $queryParams = $request->toArray();
        $bodyParams = $request->toBodyArray();

        $result = $this->client->post('api/v2/affiliate/reports/statistics', $bodyParams, $queryParams);
        /** @var array<string, mixed> $response */
        $response = $result['data'];

        // Statistics returns a single report object, wrap it in an array for pagination
        $reportData = StatisticsReportDTO::fromArray($response);

        return new PaginatedResponse(
            items: new Collection([$reportData]),
            totalItems: 1,
            currentPage: $queryParams['page'] ?? null,
            perPage: $queryParams['perPage'] ?? null,
            totalPages: 1,
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
    public function options(): Collection
    {
        $result = $this->client->get('api/v2/affiliate/reports/statistics/options', []);
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

    /**
     * Get available breakdown options
     *
     * @return Collection<int, BreakdownOptionDTO>
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function breakdowns(): Collection
    {
        $result = $this->client->get('api/v2/affiliate/reports/statistics/breakdowns', []);
        /** @var array<string, mixed> $response */
        $response = $result['data'];

        $breakdownsData = $response['breakdowns-list'] ?? [];
        if (! is_array($breakdownsData)) {
            throw new UnexpectedResponseException('Expected breakdowns-list array in response');
        }

        $items = [];
        foreach ($breakdownsData as $breakdown) {
            if (! is_array($breakdown)) {
                throw new UnexpectedResponseException('Expected array for breakdown option item');
            }

            /** @var array<string, mixed> $breakdown */
            $items[] = BreakdownOptionDTO::fromArray($breakdown);
        }

        return new Collection($items);
    }
}
