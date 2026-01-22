<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Affiliate;

use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class LiveStatsDTO
{
    use ArrayHelpers;

    public function __construct(
        public string $key,
        public LiveStatsPeriodDTO $current,
        public LiveStatsPeriodDTO $previous,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $currentData = $data['current'] ?? [];
        if (! is_array($currentData)) {
            $currentData = [];
        }
        /** @var array<string, mixed> $currentData */
        $current = LiveStatsPeriodDTO::fromArray($currentData);

        $previousData = $data['previous'] ?? [];
        if (! is_array($previousData)) {
            $previousData = [];
        }
        /** @var array<string, mixed> $previousData */
        $previous = LiveStatsPeriodDTO::fromArray($previousData);

        return new self(
            key: self::getString($data, 'key'),
            current: $current,
            previous: $previous,
        );
    }
}
