<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Endpoints\Affiliate\Billing;

use Illuminate\Support\Collection;
use JakubOrava\ScaleoIoClient\BaseScaleoClient;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\AffiliatePaymentMethodDTO;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\PaymentMethodDetailsDTO;
use JakubOrava\ScaleoIoClient\DTO\PaginatedResponse;
use JakubOrava\ScaleoIoClient\Exceptions\ApiErrorException;
use JakubOrava\ScaleoIoClient\Exceptions\AuthenticationException;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;
use JakubOrava\ScaleoIoClient\Exceptions\ValidationException;

class PaymentMethods
{
    public function __construct(
        private readonly BaseScaleoClient $client
    ) {}

    /**
     * List all payment methods
     *
     * @return Collection<int, AffiliatePaymentMethodDTO>
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function list(): Collection
    {
        $result = $this->client->get('api/v2/affiliate/aff-billing/payment-methods', []);
        /** @var array<string, mixed> $response */
        $response = $result['data'];

        $methodsData = $response['affiliates-payments-methods'] ?? [];
        if (! is_array($methodsData)) {
            throw new UnexpectedResponseException('Expected affiliates-payments-methods array in response');
        }

        $items = [];
        foreach ($methodsData as $method) {
            if (! is_array($method)) {
                throw new UnexpectedResponseException('Expected array for payment method item');
            }

            /** @var array<string, mixed> $method */
            $items[] = AffiliatePaymentMethodDTO::fromArray($method);
        }

        return new Collection($items);
    }

    /**
     * Get payment method details
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function get(string $currency): PaymentMethodDetailsDTO
    {
        $result = $this->client->post('api/v2/affiliate/aff-billing/payment-method', ['currency' => $currency], []);
        /** @var array<string, mixed> $response */
        $response = $result['data'];

        $detailsData = $response['affiliate-billing-details'] ?? null;
        if (! is_array($detailsData)) {
            throw new UnexpectedResponseException('Expected affiliate-billing-details object in response');
        }

        /** @var array<string, mixed> $detailsData */
        return PaymentMethodDetailsDTO::fromArray($detailsData);
    }

    /**
     * Create or update payment method
     *
     * @param  array<string, mixed>  $data
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function createOrUpdate(array $data): void
    {
        $this->client->put('api/v2/affiliate/aff-billing/payment-method', $data, []);
    }

    /**
     * Delete payment method
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function delete(int $paymentMethodId): void
    {
        $this->client->delete("api/v2/affiliate/aff-billing/payment-methods/$paymentMethodId", []);
    }

    /**
     * Get supported currencies for a base currency
     *
     * @return PaginatedResponse<array<string, mixed>>
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function supportedCurrencies(string $baseCurrency): PaginatedResponse
    {
        $result = $this->client->get("api/v2/affiliate/aff-billing/payment-methods/supported-currencies/$baseCurrency", []);
        /** @var array<string, mixed> $response */
        $response = $result['data'];

        // API returns various currency-related data, keep as raw array
        return new PaginatedResponse(
            items: new Collection([$response]),
            totalItems: 1,
            currentPage: 1,
            perPage: 1,
            totalPages: 1,
        );
    }
}
