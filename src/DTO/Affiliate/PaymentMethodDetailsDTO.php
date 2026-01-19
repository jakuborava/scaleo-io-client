<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Affiliate;

use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class PaymentMethodDetailsDTO
{
    use ArrayHelpers;

    public function __construct(
        public int $id,
        public string $currency,
        public int $paymentMethodId,
        public string $paymentMethodInfo,
        public string $title,
        public string $paymentThreshold,
        public string $paymentCommission,
        public string $paymentMethodLogo,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: self::getInt($data, 'id'),
            currency: self::getString($data, 'currency'),
            paymentMethodId: self::getInt($data, 'payment_method_id'),
            paymentMethodInfo: self::getString($data, 'payment_method_info'),
            title: self::getString($data, 'title'),
            paymentThreshold: self::getString($data, 'payment_threshold'),
            paymentCommission: self::getString($data, 'payment_commission'),
            paymentMethodLogo: self::getString($data, 'payment_method_logo'),
        );
    }
}
