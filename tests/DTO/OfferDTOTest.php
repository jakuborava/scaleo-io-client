<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\LiveStatsDTO;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\OfferDTO;
use JakubOrava\ScaleoIoClient\DTO\Affiliate\VisibleTypeDTO;

describe('OfferDTO', function () {
    it('can parse visible_type_selected as collection of VisibleTypeDTO', function () {
        $data = [
            'id' => 1,
            'title' => 'Test Offer',
            'description' => null,
            'advertiser_id' => null,
            'image' => null,
            'gt_included_ids' => null,
            'gt_excluded_ids' => null,
            'gt_filter_ids' => null,
            'extended_targeting' => null,
            'traffic_types' => null,
            'is_featured' => 0,
            'timezone' => null,
            'currency' => 'USD',
            'aff_epc' => null,
            'ar' => null,
            'cr' => null,
            'visible_type' => 1,
            'questions' => null,
            'ask_approval_questions' => null,
            'tracking_domain_id' => null,
            'traffic_types_selected' => null,
            'tracking_domain' => null,
            'goals' => [],
            'targeting' => null,
            'creatives_count' => 0,
            'creatives' => [],
            'links_count' => 0,
            'links' => [],
            'categories' => [],
            'visible_type_selected' => [
                [
                    'id' => 1,
                    'title' => 'Public',
                    'requests' => [],
                    'availability' => 'available',
                ],
                [
                    'id' => 2,
                    'title' => 'By Request',
                    'requests' => [],
                    'availability' => 'limited',
                ],
            ],
        ];

        $dto = OfferDTO::fromArray($data);

        expect($dto->visibleTypeSelected)->toBeInstanceOf(Collection::class)
            ->and($dto->visibleTypeSelected)->toHaveCount(2)
            ->and($dto->visibleTypeSelected->first())->toBeInstanceOf(VisibleTypeDTO::class)
            ->and($dto->visibleTypeSelected->first()->id)->toBe(1)
            ->and($dto->visibleTypeSelected->first()->title)->toBe('Public')
            ->and($dto->visibleTypeSelected->last()->id)->toBe(2)
            ->and($dto->visibleTypeSelected->last()->title)->toBe('By Request');
    });

    it('can parse live_stats as LiveStatsDTO', function () {
        $data = [
            'id' => 1,
            'title' => 'Test Offer',
            'description' => null,
            'advertiser_id' => null,
            'image' => null,
            'gt_included_ids' => null,
            'gt_excluded_ids' => null,
            'gt_filter_ids' => null,
            'extended_targeting' => null,
            'traffic_types' => null,
            'is_featured' => 0,
            'timezone' => null,
            'currency' => 'USD',
            'aff_epc' => null,
            'ar' => null,
            'cr' => null,
            'visible_type' => 1,
            'questions' => null,
            'ask_approval_questions' => null,
            'tracking_domain_id' => null,
            'traffic_types_selected' => null,
            'tracking_domain' => null,
            'goals' => [],
            'targeting' => null,
            'creatives_count' => 0,
            'creatives' => [],
            'links_count' => 0,
            'links' => [],
            'categories' => [],
            'live_stats' => [
                'key' => 'cv_total',
                'current' => [
                    'ranges' => ['2022-08-23', '2022-08-24'],
                    'series' => [10, 20, 30],
                    'total' => 60,
                    'total_change' => 5,
                ],
                'previous' => [
                    'ranges' => ['2022-08-21', '2022-08-22'],
                    'series' => [8, 15, 23],
                    'total' => 46,
                    'total_change' => 3,
                ],
            ],
        ];

        $dto = OfferDTO::fromArray($data);

        expect($dto->liveStats)->toBeInstanceOf(LiveStatsDTO::class)
            ->and($dto->liveStats->key)->toBe('cv_total')
            ->and($dto->liveStats->current->total)->toBe(60)
            ->and($dto->liveStats->current->ranges)->toHaveCount(2)
            ->and($dto->liveStats->previous->total)->toBe(46);
    });

    it('handles missing visible_type_selected', function () {
        $data = [
            'id' => 1,
            'title' => 'Test Offer',
            'description' => null,
            'advertiser_id' => null,
            'image' => null,
            'gt_included_ids' => null,
            'gt_excluded_ids' => null,
            'gt_filter_ids' => null,
            'extended_targeting' => null,
            'traffic_types' => null,
            'is_featured' => 0,
            'timezone' => null,
            'currency' => 'USD',
            'aff_epc' => null,
            'ar' => null,
            'cr' => null,
            'visible_type' => 1,
            'questions' => null,
            'ask_approval_questions' => null,
            'tracking_domain_id' => null,
            'traffic_types_selected' => null,
            'tracking_domain' => null,
            'goals' => [],
            'targeting' => null,
            'creatives_count' => 0,
            'creatives' => [],
            'links_count' => 0,
            'links' => [],
            'categories' => [],
        ];

        $dto = OfferDTO::fromArray($data);

        expect($dto->visibleTypeSelected)->toBeInstanceOf(Collection::class)
            ->and($dto->visibleTypeSelected)->toBeEmpty();
    });

    it('handles null live_stats', function () {
        $data = [
            'id' => 1,
            'title' => 'Test Offer',
            'description' => null,
            'advertiser_id' => null,
            'image' => null,
            'gt_included_ids' => null,
            'gt_excluded_ids' => null,
            'gt_filter_ids' => null,
            'extended_targeting' => null,
            'traffic_types' => null,
            'is_featured' => 0,
            'timezone' => null,
            'currency' => 'USD',
            'aff_epc' => null,
            'ar' => null,
            'cr' => null,
            'visible_type' => 1,
            'questions' => null,
            'ask_approval_questions' => null,
            'tracking_domain_id' => null,
            'traffic_types_selected' => null,
            'tracking_domain' => null,
            'goals' => [],
            'targeting' => null,
            'creatives_count' => 0,
            'creatives' => [],
            'links_count' => 0,
            'links' => [],
            'categories' => [],
            'live_stats' => null,
        ];

        $dto = OfferDTO::fromArray($data);

        expect($dto->liveStats)->toBeNull();
    });
});
