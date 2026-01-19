<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Affiliate;

use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class CreativeDTO
{
    use ArrayHelpers;

    public function __construct(
        public int $id,
        public string $title,
        public ?string $description,
        public int $type,
        public ?string $image,
        public ?int $imageWidth,
        public ?int $imageHeight,
        public ?int $imageSize,
        public ?int $offerUrlId,
        public ?int $countImpressions,
        public ?string $htmlCode,
        public ?string $plainText,
        public ?string $xmlFeedUrl,
        public ?string $xmlFeedUrlTag,
        public ?int $status,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: self::getInt($data, 'id'),
            title: self::getString($data, 'title'),
            description: self::getStringOrNull($data, 'description'),
            type: self::getInt($data, 'type'),
            image: self::getStringOrNull($data, 'image'),
            imageWidth: self::getIntOrNull($data, 'image_width'),
            imageHeight: self::getIntOrNull($data, 'image_height'),
            imageSize: self::getIntOrNull($data, 'image_size'),
            offerUrlId: self::getIntOrNull($data, 'offer_url_id'),
            countImpressions: self::getIntOrNull($data, 'count_impressions'),
            htmlCode: self::getStringOrNull($data, 'html_code'),
            plainText: self::getStringOrNull($data, 'plain_text'),
            xmlFeedUrl: self::getStringOrNull($data, 'xml_feed_url'),
            xmlFeedUrlTag: self::getStringOrNull($data, 'xml_feed_url_tag'),
            status: self::getIntOrNull($data, 'status'),
        );
    }
}
