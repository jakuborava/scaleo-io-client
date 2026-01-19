<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Affiliate;

use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class PostbackDTO
{
    use ArrayHelpers;

    /**
     * @param  array<string, mixed>|null  $offerSelected
     * @param  array<string, mixed>|null  $goalSelected
     */
    public function __construct(
        public int $id,
        public int $levelId,
        public int $offerId,
        public int $goalId,
        public int $conversionStatus,
        public int $type,
        public string $code,
        public int $status,
        public int $affiliateId,
        public int $created,
        public int $updated,
        public ?array $offerSelected,
        public ?array $goalSelected,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $offerSelected = $data['offer_selected'] ?? null;
        if ($offerSelected !== null && ! is_array($offerSelected)) {
            $offerSelected = null;
        }

        $goalSelected = $data['goal_selected'] ?? null;
        if ($goalSelected !== null && ! is_array($goalSelected)) {
            $goalSelected = null;
        }

        return new self(
            id: self::getInt($data, 'id'),
            levelId: self::getInt($data, 'level_id'),
            offerId: self::getInt($data, 'offer_id'),
            goalId: self::getInt($data, 'goal_id'),
            conversionStatus: self::getInt($data, 'conversion_status'),
            type: self::getInt($data, 'type'),
            code: self::getString($data, 'code'),
            status: self::getInt($data, 'status'),
            affiliateId: self::getInt($data, 'affiliate_id'),
            created: self::getInt($data, 'created'),
            updated: self::getInt($data, 'updated'),
            offerSelected: $offerSelected,
            goalSelected: $goalSelected,
        );
    }
}
