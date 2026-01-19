<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Affiliate;

use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class LeadResponseDTO
{
    use ArrayHelpers;

    public function __construct(
        public string $status,
        public string $message,
        public string $leadId,
        public string $transactionId,
        public string $convStatus,
        public int $affiliateId,
        public int $offerId,
        public int $goalId,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            status: self::getString($data, 'status'),
            message: self::getString($data, 'message'),
            leadId: self::getString($data, 'lead_id'),
            transactionId: self::getString($data, 'transaction_id'),
            convStatus: self::getString($data, 'conv_status'),
            affiliateId: self::getInt($data, 'affiliate_id'),
            offerId: self::getInt($data, 'offer_id'),
            goalId: self::getInt($data, 'goal_id'),
        );
    }
}
