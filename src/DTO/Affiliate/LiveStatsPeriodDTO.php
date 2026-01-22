<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Affiliate;

use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class LiveStatsPeriodDTO
{
    use ArrayHelpers;

    /**
     * @param  array<int, string>  $ranges
     * @param  array<int, int>  $series
     */
    public function __construct(
        public array $ranges,
        public array $series,
        public int $total,
        public int $totalChange,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $ranges = is_array($data['ranges'] ?? null) ? $data['ranges'] : [];
        $series = is_array($data['series'] ?? null) ? $data['series'] : [];

        return new self(
            ranges: $ranges,
            series: $series,
            total: self::getInt($data, 'total'),
            totalChange: self::getInt($data, 'total_change'),
        );
    }
}
