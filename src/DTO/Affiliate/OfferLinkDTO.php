<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Affiliate;

use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class OfferLinkDTO
{
    use ArrayHelpers;

    public function __construct(
        public int $id,
        public string $title,
        public int $type,
        public string $url,
        public string $preview,
        public int $visibleToAllAffiliates,
        public ?string $visibleToSpecificAffiliatesOnly,
        public ?string $geoAllowed,
        public ?string $geoDenied,
        public ?int $percent,
        public int $status,
        public int $offerId,
        public ?int $created,
        public ?int $updated,
        public ?string $typeSelected,
        public array $rules,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $rules = $data['rules'] ?? [];
        if (! is_array($rules)) {
            $rules = [];
        }

        return new self(
            id: self::getInt($data, 'id'),
            title: self::getString($data, 'title'),
            type: self::getInt($data, 'type'),
            url: self::getString($data, 'url'),
            preview: self::getString($data, 'preview'),
            visibleToAllAffiliates: self::getInt($data, 'visible_to_all_affiliates'),
            visibleToSpecificAffiliatesOnly: self::getStringOrNull($data, 'visible_to_specific_affiliates_only'),
            geoAllowed: self::getStringOrNull($data, 'geo_allowed'),
            geoDenied: self::getStringOrNull($data, 'geo_denied'),
            percent: self::getIntOrNull($data, 'percent'),
            status: self::getInt($data, 'status'),
            offerId: self::getInt($data, 'offer_id'),
            created: self::getIntOrNull($data, 'created'),
            updated: self::getIntOrNull($data, 'updated'),
            typeSelected: self::getStringOrNull($data, 'type_selected'),
            rules: $rules,
        );
    }
}
