<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Affiliate;

use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class TargetingDTO
{
    use ArrayHelpers;

    public function __construct(
        public ?string $geoAllowed,
        public ?string $geoDenied,
        public ?string $connectionTypeAllowed = null,
        public ?string $deviceTypeDenied = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $geo = $data['geo'] ?? [];
        if (! is_array($geo)) {
            $geo = [];
        }

        $connectionType = $data['connectionType'] ?? [];
        if (! is_array($connectionType)) {
            $connectionType = [];
        }

        $deviceType = $data['deviceType'] ?? [];
        if (! is_array($deviceType)) {
            $deviceType = [];
        }

        return new self(
            geoAllowed: isset($geo['allowed']) && is_string($geo['allowed']) ? $geo['allowed'] : null,
            geoDenied: isset($geo['denied']) && is_string($geo['denied']) ? $geo['denied'] : null,
            connectionTypeAllowed: isset($connectionType['allowed']) && is_string($connectionType['allowed']) ? $connectionType['allowed'] : null,
            deviceTypeDenied: isset($deviceType['denied']) && is_string($deviceType['denied']) ? $deviceType['denied'] : null,
        );
    }
}
