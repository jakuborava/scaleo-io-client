<?php

declare(strict_types=1);

use JakubOrava\ScaleoIoClient\DTO\Affiliate\OfferLinkDTO;

describe('OfferLinkDTO', function () {
    it('can parse rules field as array', function () {
        $data = [
            'id' => 1,
            'title' => 'Test Link',
            'type' => 1,
            'url' => 'https://example.com',
            'preview' => 'https://example.com/preview',
            'visible_to_all_affiliates' => 1,
            'visible_to_specific_affiliates_only' => null,
            'geo_allowed' => null,
            'geo_denied' => null,
            'percent' => null,
            'status' => 1,
            'offer_id' => 100,
            'created' => 1640000000,
            'updated' => 1640000000,
            'type_selected' => null,
            'rules' => [
                [
                    ['title', 'offer_id', 'status'],
                    'required',
                ],
                [
                    ['type', 'percent', 'status'],
                    'integer',
                ],
            ],
        ];

        $dto = OfferLinkDTO::fromArray($data);

        expect($dto->id)->toBe(1)
            ->and($dto->title)->toBe('Test Link')
            ->and($dto->rules)->toBeArray()
            ->and($dto->rules)->toHaveCount(2);
    });

    it('handles empty rules array', function () {
        $data = [
            'id' => 1,
            'title' => 'Test Link',
            'type' => 1,
            'url' => 'https://example.com',
            'preview' => 'https://example.com/preview',
            'visible_to_all_affiliates' => 1,
            'visible_to_specific_affiliates_only' => null,
            'geo_allowed' => null,
            'geo_denied' => null,
            'percent' => null,
            'status' => 1,
            'offer_id' => 100,
            'created' => null,
            'updated' => null,
            'type_selected' => null,
            'rules' => [],
        ];

        $dto = OfferLinkDTO::fromArray($data);

        expect($dto->rules)->toBeArray()
            ->and($dto->rules)->toBeEmpty();
    });

    it('handles missing rules field', function () {
        $data = [
            'id' => 1,
            'title' => 'Test Link',
            'type' => 1,
            'url' => 'https://example.com',
            'preview' => 'https://example.com/preview',
            'visible_to_all_affiliates' => 1,
            'visible_to_specific_affiliates_only' => null,
            'geo_allowed' => null,
            'geo_denied' => null,
            'percent' => null,
            'status' => 1,
            'offer_id' => 100,
            'created' => null,
            'updated' => null,
            'type_selected' => null,
        ];

        $dto = OfferLinkDTO::fromArray($data);

        expect($dto->rules)->toBeArray()
            ->and($dto->rules)->toBeEmpty();
    });
});
