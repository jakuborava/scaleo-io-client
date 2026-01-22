<?php

declare(strict_types=1);

use JakubOrava\ScaleoIoClient\DTO\Affiliate\LiveStatsDTO;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\LiveStatsPeriodDTO;

describe('LiveStatsPeriodDTO', function () {
    it('can create from array', function () {
        $data = [
            'ranges' => ['2022-08-23', '2022-08-24'],
            'series' => [10, 20, 30],
            'total' => 60,
            'total_change' => 5,
        ];

        $dto = LiveStatsPeriodDTO::fromArray($data);

        expect($dto->ranges)->toBeArray()
            ->and($dto->ranges)->toHaveCount(2)
            ->and($dto->ranges[0])->toBe('2022-08-23')
            ->and($dto->series)->toBeArray()
            ->and($dto->series)->toHaveCount(3)
            ->and($dto->total)->toBe(60)
            ->and($dto->totalChange)->toBe(5);
    });

    it('handles empty arrays', function () {
        $data = [
            'ranges' => [],
            'series' => [],
            'total' => 0,
            'total_change' => 0,
        ];

        $dto = LiveStatsPeriodDTO::fromArray($data);

        expect($dto->ranges)->toBeArray()
            ->and($dto->ranges)->toBeEmpty()
            ->and($dto->series)->toBeArray()
            ->and($dto->series)->toBeEmpty()
            ->and($dto->total)->toBe(0)
            ->and($dto->totalChange)->toBe(0);
    });
});

describe('LiveStatsDTO', function () {
    it('can create from array', function () {
        $data = [
            'key' => 'cv_total',
            'current' => [
                'ranges' => ['2022-08-23', '2022-08-24'],
                'series' => [10, 20],
                'total' => 30,
                'total_change' => 5,
            ],
            'previous' => [
                'ranges' => ['2022-08-21', '2022-08-22'],
                'series' => [8, 15],
                'total' => 23,
                'total_change' => 3,
            ],
        ];

        $dto = LiveStatsDTO::fromArray($data);

        expect($dto->key)->toBe('cv_total')
            ->and($dto->current)->toBeInstanceOf(LiveStatsPeriodDTO::class)
            ->and($dto->current->total)->toBe(30)
            ->and($dto->current->ranges)->toHaveCount(2)
            ->and($dto->previous)->toBeInstanceOf(LiveStatsPeriodDTO::class)
            ->and($dto->previous->total)->toBe(23)
            ->and($dto->previous->ranges)->toHaveCount(2);
    });

    it('handles missing current and previous data', function () {
        $data = [
            'key' => 'cv_total',
        ];

        $dto = LiveStatsDTO::fromArray($data);

        expect($dto->key)->toBe('cv_total')
            ->and($dto->current)->toBeInstanceOf(LiveStatsPeriodDTO::class)
            ->and($dto->previous)->toBeInstanceOf(LiveStatsPeriodDTO::class);
    });
});
