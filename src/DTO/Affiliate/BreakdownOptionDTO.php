<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Affiliate;

use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class BreakdownOptionDTO
{
    use ArrayHelpers;

    public function __construct(
        public string $group,
        public string $label,
        public string $key,
        public int $sort,
        public int $groupSort,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            group: self::getString($data, 'group'),
            label: self::getString($data, 'label'),
            key: self::getString($data, 'key'),
            sort: self::getInt($data, 'sort'),
            groupSort: self::getInt($data, 'groupSort'),
        );
    }
}
