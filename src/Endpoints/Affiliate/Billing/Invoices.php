<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Billing;

use JakubOrava\ScaleoIoClient\BaseScaleoClient;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\InvoiceDTO;
use JakubOrava\ScaleoIoClient\DTO\PaginatedResponse;
use JakubOrava\ScaleoIoClient\Exceptions\ApiErrorException;
use JakubOrava\ScaleoIoClient\Exceptions\AuthenticationException;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\Exceptions\ValidationException;
use JakubOrava\ScaleoIoClient\Requests\InvoicesListRequest;

class Invoices
{
    public function __construct(
        private readonly BaseScaleoClient $client
    ) {}

    /**
     * List all invoices
     *
     * @return PaginatedResponse<InvoiceDTO>
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function list(?InvoicesListRequest $request = null): PaginatedResponse
    {
        $request = $request ?? new InvoicesListRequest;
        $queryParams = $request->toArray();

        $result = $this->client->get('api/v2/affiliate/aff-billing/invoices', $queryParams);
        /** @var array<string, mixed> $response */
        $response = $result['data'];

        return PaginatedResponse::fromResponse(
            response: $response,
            itemsKey: 'invoices',
            paginationHeaders: $result['headers'],
            mapper: fn (array $invoice): InvoiceDTO => InvoiceDTO::fromArray($invoice)
        );
    }

    /**
     * Get invoice details
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function get(int $invoiceId): InvoiceDTO
    {
        $result = $this->client->get("api/v2/affiliate/aff-billing/invoices/$invoiceId", []);
        /** @var array<string, mixed> $response */
        $response = $result['data'];

        $invoiceData = $response['invoice'] ?? null;
        if (! is_array($invoiceData)) {
            throw new UnexpectedResponseException('Expected invoice object in response');
        }

        /** @var array<string, mixed> $invoiceData */
        return InvoiceDTO::fromArray($invoiceData);
    }

    /**
     * Download invoice PDF
     *
     * @throws AuthenticationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function downloadPdf(int $invoiceId): string
    {
        return $this->client->download("api/v2/affiliate/aff-billing/invoices/download-pdf/$invoiceId", []);
    }
}
