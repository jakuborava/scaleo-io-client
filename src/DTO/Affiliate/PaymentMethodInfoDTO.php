<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Affiliate;

use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class PaymentMethodInfoDTO
{
    use ArrayHelpers;

    public function __construct(
        public int $id,
        public string $title,
        public int $status,
        public string $supportedCurrencies,
        public string $paymentThreshold,
        public string $paymentThresholdCurrency,
        public string $paymentCommission,
        public string $paymentMethodLogo,
        public int $sort,
        public int $created,
        public int $updated,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: self::getInt($data, 'id'),
            title: self::getString($data, 'title'),
            status: self::getInt($data, 'status'),
            supportedCurrencies: self::getString($data, 'supported_currencies'),
            paymentThreshold: self::getString($data, 'payment_threshold'),
            paymentThresholdCurrency: self::getString($data, 'payment_threshold_currency'),
            paymentCommission: self::getString($data, 'payment_commission'),
            paymentMethodLogo: self::getString($data, 'payment_method_logo'),
            sort: self::getInt($data, 'sort'),
            created: self::getInt($data, 'created'),
            updated: self::getInt($data, 'updated'),
        );
    }
}
