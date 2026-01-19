<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Affiliate;

use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class GoalDTO
{
    use ArrayHelpers;

    public function __construct(
        public int $goalId,
        public string $goalName,
        public int $goalType,
        public string $goalTypeName,
        public int $trackingMethod,
        public int $conversionStatus,
        public int $multipleConversions,
        public int $hasCaps,
        public string $caps,
        public string $alias,
        public int $isDefault,
        public string $payoutForAffiliate,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            goalId: self::getInt($data, 'goal_id'),
            goalName: self::getString($data, 'goal_name'),
            goalType: self::getInt($data, 'goal_type'),
            goalTypeName: self::getString($data, 'goal_type_name'),
            trackingMethod: self::getInt($data, 'tracking_method'),
            conversionStatus: self::getInt($data, 'conversion_status'),
            multipleConversions: self::getInt($data, 'multiple_conversions'),
            hasCaps: self::getInt($data, 'has_caps'),
            caps: self::getString($data, 'caps'),
            alias: self::getString($data, 'alias'),
            isDefault: self::getInt($data, 'is_default'),
            payoutForAffiliate: self::getString($data, 'payout_for_affiliate'),
        );
    }
}
