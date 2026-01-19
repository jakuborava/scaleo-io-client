<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Affiliate;

use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class PaymentRequestResponseDTO
{
    use ArrayHelpers;

    public function __construct(
        public int $invoiceId,
        public string $invoiceNumber,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            invoiceId: self::getInt($data, 'invoice_id'),
            invoiceNumber: self::getString($data, 'invoice_number'),
        );
    }
}
