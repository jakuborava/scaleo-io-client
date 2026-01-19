<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Billing;

use JakubOrava\ScaleoIoClient\BaseScaleoClient;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\PaymentRequestResponseDTO;
use JakubOrava\ScaleoIoClient\Exceptions\ApiErrorException;
use JakubOrava\ScaleoIoClient\Exceptions\AuthenticationException;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\Exceptions\ValidationException;

class PaymentRequest
{
    public function __construct(
        private readonly BaseScaleoClient $client
    ) {}

    /**
     * Send a payment request
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function create(string $currency, ?string $attachmentFile = null, ?float $amount = null): PaymentRequestResponseDTO
    {
        $data = [
            'currency' => $currency,
        ];

        if ($attachmentFile !== null) {
            $data['attachment_file'] = $attachmentFile;
        }

        if ($amount !== null) {
            $data['amount'] = $amount;
        }

        $result = $this->client->put('api/v2/affiliate/aff-billing/payment-request', $data, []);
        /** @var array<string, mixed> $response */
        $response = $result['data'];

        if (! is_array($response)) {
            throw new UnexpectedResponseException('Expected payment request response object');
        }

        return PaymentRequestResponseDTO::fromArray($response);
    }
}
