<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Affiliate;

use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class ColumnOptionDTO
{
    use ArrayHelpers;

    public function __construct(
        public string $group,
        public string $label,
        public string $key,
        public int $sort,
        public int $groupSort,
        public int $reportSort,
        public int $default,
        public ?string $parent,
        public int $level,
        public int $column,
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
            reportSort: self::getInt($data, 'reportSort'),
            default: self::getInt($data, 'default'),
            parent: self::getStringOrNull($data, 'parent'),
            level: self::getInt($data, 'level'),
            column: self::getInt($data, 'column'),
        );
    }
}
