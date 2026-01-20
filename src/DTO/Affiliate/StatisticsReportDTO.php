<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Affiliate;

use Illuminate\Support\Collection;
use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class StatisticsReportDTO
{
    use ArrayHelpers;

    /**
     * @param  Collection<int, StatisticsRowDTO>  $rows
     * @param  array<string, mixed>  $totals
     */
    public function __construct(
        public string $timezone,
        public Collection $rows,
        public array $totals,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $timezone = $data['timezone'] ?? 'UTC';
        if (! is_string($timezone)) {
            $timezone = 'UTC';
        }

        $rowsData = $data['rows'] ?? [];
        if (! is_array($rowsData)) {
            $rowsData = [];
        }

        $rowsArray = $rowsData['rows'] ?? [];
        if (! is_array($rowsArray)) {
            $rowsArray = [];
        }

        $items = [];
        foreach ($rowsArray as $row) {
            if (! is_array($row)) {
                continue;
            }

            /** @var array<string, mixed> $row */
            $items[] = StatisticsRowDTO::fromArray($row);
        }

        $totals = $rowsData['totals'] ?? [];
        if (! is_array($totals)) {
            $totals = [];
        }
        /** @var array<string, mixed> $totals */

        return new self(
            timezone: $timezone,
            rows: new Collection($items),
            totals: $totals,
        );
    }
}
