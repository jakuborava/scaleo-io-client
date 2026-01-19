<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Affiliate;

use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class AffiliatePaymentMethodDTO
{
    use ArrayHelpers;

    public function __construct(
        public int $id,
        public int $affiliateId,
        public PaymentMethodInfoDTO $paymentMethod,
        public string $paymentMethodInfo,
        public string $paymentMethodThresholdConverted,
        public string $currency,
        public int $approvedBalance,
        public int $balanceDue,
        public string $nextIntervalDate,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $paymentMethodData = $data['payment_method'] ?? [];
        if (! is_array($paymentMethodData)) {
            throw new \InvalidArgumentException('Expected payment_method array');
        }

        /** @var array<string, mixed> $paymentMethodData */
        return new self(
            id: self::getInt($data, 'id'),
            affiliateId: self::getInt($data, 'affiliate_id'),
            paymentMethod: PaymentMethodInfoDTO::fromArray($paymentMethodData),
            paymentMethodInfo: self::getString($data, 'payment_method_info'),
            paymentMethodThresholdConverted: self::getString($data, 'payment_method_threshold_converted'),
            currency: self::getString($data, 'currency'),
            approvedBalance: self::getInt($data, 'approved_balance'),
            balanceDue: self::getInt($data, 'balance_due'),
            nextIntervalDate: self::getString($data, 'next_interval_date'),
        );
    }
}
