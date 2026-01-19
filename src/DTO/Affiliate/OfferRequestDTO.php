<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Affiliate;

use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class OfferRequestDTO
{
    use ArrayHelpers;

    public function __construct(
        public int $id,
        public int $offerId,
        public string $offerName,
        public int $affiliateId,
        public string $affiliateName,
        public string $status,
        public ?string $questions,
        public ?string $answer,
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
            offerId: self::getInt($data, 'offer_id'),
            offerName: self::getString($data, 'offer_name'),
            affiliateId: self::getInt($data, 'affiliate_id'),
            affiliateName: self::getString($data, 'affiliate_name'),
            status: self::getString($data, 'status'),
            questions: self::getStringOrNull($data, 'questions'),
            answer: self::getStringOrNull($data, 'answer'),
            created: self::getInt($data, 'created'),
            updated: self::getInt($data, 'updated'),
        );
    }
}
