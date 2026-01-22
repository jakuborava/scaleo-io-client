<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Affiliate;

use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class VisibleTypeDTO
{
    use ArrayHelpers;

    /**
     * @param  array<string, mixed>  $requests
     */
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
        $requests = is_array($data['requests'] ?? null) ? $data['requests'] : [];

        /** @var array<string, mixed> $requests */

        return new self(
            id: self::getInt($data, 'id'),
            title: self::getString($data, 'title'),
            requests: $requests,
            availability: self::getString($data, 'availability'),
        );
    }
}
