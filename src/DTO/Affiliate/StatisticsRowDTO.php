<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Affiliate;

use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class StatisticsRowDTO
{
    use ArrayHelpers;

    /**
     * @param  array<string, mixed>  $breakdown
     * @param  array<string, mixed>  $metrics
     */
    public function __construct(
        public array $breakdown,
        public array $metrics,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        // Separate breakdown fields from metrics
        $breakdown = [];
        $metrics = [];

        foreach ($data as $key => $value) {
            // Common breakdown fields
            if (in_array($key, ['offer', 'source', 'goal', 'country', 'device_type', 'sub_id1', 'sub_id2', 'sub_id3', 'sub_id4', 'sub_id5'])) {
                $breakdown[$key] = $value;
            } else {
                // Everything else is a metric
                $metrics[$key] = $value;
            }
        }

        return new self(
            breakdown: $breakdown,
            metrics: $metrics,
        );
    }
}
