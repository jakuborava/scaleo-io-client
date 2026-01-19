<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Affiliate;

use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class NetworkSummaryItemDTO
{
    use ArrayHelpers;

    public function __construct(
        public string $key,
        public PeriodDataDTO $current,
        public PeriodDataDTO $previous,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $current = $data['current'] ?? [];
        if (! is_array($current)) {
            throw new \InvalidArgumentException('Expected array for current');
        }

        $previous = $data['previous'] ?? [];
        if (! is_array($previous)) {
            throw new \InvalidArgumentException('Expected array for previous');
        }

        return new self(
            key: self::getString($data, 'key'),
            current: PeriodDataDTO::fromArray($current),
            previous: PeriodDataDTO::fromArray($previous),
        );
    }
}
