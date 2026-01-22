<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Common;

use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class CountryDTO
{
    use ArrayHelpers;

    public function __construct(
        public int $id,
        public string $title,
        public string $countryCode
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: self::getInt($data, 'id'),
            title: self::getString($data, 'title'),
            countryCode: self::getString($data, 'country_code'),
        );
    }
}
