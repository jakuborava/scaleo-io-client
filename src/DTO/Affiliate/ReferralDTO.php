<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Affiliate;

use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class ReferralDTO
{
    use ArrayHelpers;

    public function __construct(
        public string $referral,
        public float $rate,
        public float $referralCommission,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            referral: self::getString($data, 'referral'),
            rate: self::getFloat($data, 'rate'),
            referralCommission: self::getFloat($data, 'referral_commission'),
        );
    }
}
