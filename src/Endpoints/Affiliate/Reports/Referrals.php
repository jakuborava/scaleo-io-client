<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Reports;

use JakubOrava\ScaleoIoClient\BaseScaleoClient;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\ReferralDTO;
use JakubOrava\ScaleoIoClient\DTO\PaginatedResponse;
use JakubOrava\ScaleoIoClient\Exceptions\ApiErrorException;
use JakubOrava\ScaleoIoClient\Exceptions\AuthenticationException;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\Exceptions\ValidationException;
use JakubOrava\ScaleoIoClient\Requests\ReferralsReportRequest;

class Referrals
{
    public function __construct(
        private readonly BaseScaleoClient $client
    ) {}

    /**
     * Get referrals report
     *
     * @return PaginatedResponse<ReferralDTO>
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function list(ReferralsReportRequest $request): PaginatedResponse
    {
        $bodyParams = $request->toBodyArray();

        $result = $this->client->post('api/v2/affiliate/reports/referrals', $bodyParams, []);
        /** @var array<string, mixed> $response */
        $response = $result['data'];

        $reportData = $response['report'] ?? [];
        if (! is_array($reportData)) {
            throw new UnexpectedResponseException('Expected report array in response');
        }

        return PaginatedResponse::fromResponse(
            response: ['report' => $reportData],
            itemsKey: 'report',
            paginationHeaders: $result['headers'],
            mapper: fn (array $referral): ReferralDTO => ReferralDTO::fromArray($referral)
        );
    }
}
