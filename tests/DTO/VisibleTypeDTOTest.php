<?php

declare(strict_types=1);

use JakubOrava\ScaleoIoClient\DTO\Affiliate\VisibleTypeDTO;

describe('VisibleTypeDTO', function () {
    it('can create from array', function () {
        $data = [
            'id' => 1,
            'title' => 'Public',
            'requests' => [],
            'availability' => 'available',
        ];

        $dto = VisibleTypeDTO::fromArray($data);

        expect($dto->id)->toBe(1)
            ->and($dto->title)->toBe('Public')
            ->and($dto->requests)->toBeArray()
            ->and($dto->requests)->toBeEmpty()
            ->and($dto->availability)->toBe('available');
    });

    it('can handle non-empty requests array', function () {
        $data = [
            'id' => 2,
            'title' => 'By Request',
            'requests' => [
                ['affiliate_id' => 1, 'status' => 'approved'],
                ['affiliate_id' => 2, 'status' => 'pending'],
            ],
            'availability' => 'limited',
        ];

        $dto = VisibleTypeDTO::fromArray($data);

        expect($dto->id)->toBe(2)
            ->and($dto->title)->toBe('By Request')
            ->and($dto->requests)->toBeArray()
            ->and($dto->requests)->toHaveCount(2)
            ->and($dto->availability)->toBe('limited');
    });

    it('handles missing requests field', function () {
        $data = [
            'id' => 1,
            'title' => 'Public',
            'availability' => 'available',
        ];

        $dto = VisibleTypeDTO::fromArray($data);

        expect($dto->requests)->toBeArray()
            ->and($dto->requests)->toBeEmpty();
    });
});
