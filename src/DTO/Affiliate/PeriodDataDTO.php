<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Affiliate;

use Illuminate\Support\Collection;
use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class PeriodDataDTO
{
    use ArrayHelpers;

    /**
     * @param  Collection<int, string>  $ranges
     * @param  Collection<int, int>  $series
     */
    public function __construct(
        public int $from,
        public int $to,
        public int $total,
        public int $totalPartial,
        public ?float $totalChange,
        public Collection $ranges,
        public Collection $series,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $ranges = $data['ranges'] ?? [];
        if (! is_array($ranges)) {
            throw new \InvalidArgumentException('Expected array for ranges');
        }

        $series = $data['series'] ?? [];
        if (! is_array($series)) {
            throw new \InvalidArgumentException('Expected array for series');
        }

        return new self(
            from: self::getInt($data, 'from'),
            to: self::getInt($data, 'to'),
            total: self::getInt($data, 'total'),
            totalPartial: self::getInt($data, 'total_partial'),
            totalChange: self::getFloatOrNull($data, 'total_change'),
            ranges: new Collection($ranges),
            series: new Collection($series),
        );
    }
}
