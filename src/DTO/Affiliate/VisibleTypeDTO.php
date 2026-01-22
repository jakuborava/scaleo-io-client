<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Affiliate;

use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class VisibleTypeDTO
{
    use ArrayHelpers;

    public function __construct(
        public int $id,
        public string $title,
        public array $requests,
        public string $availability,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: self::getInt($data, 'id'),
            title: self::getString($data, 'title'),
            requests: is_array($data['requests'] ?? null) ? $data['requests'] : [],
            availability: self::getString($data, 'availability'),
        );
    }
}
