<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Affiliate;

use Illuminate\Support\Collection;
use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class OfferDTO
{
    use ArrayHelpers;

    /**
     * @param  Collection<int, GoalDTO>  $goals
     * @param  Collection<int, OfferLinkDTO>  $links
     * @param  Collection<int, CreativeDTO>  $creatives
     * @param  Collection<int, CategoryDTO>  $categories
     * @param  Collection<int, VisibleTypeDTO>  $visibleTypeSelected
     */
    public function __construct(
        public int $id,
        public string $title,
        public ?string $description,
        public ?int $advertiserId,
        public ?string $image,
        public ?string $gtIncludedIds,
        public ?string $gtExcludedIds,
        public ?string $gtFilterIds,
        public ?string $extendedTargeting,
        public ?string $trafficTypes,
        public ?int $isFeatured,
        public ?string $timezone,
        public ?string $currency,
        public ?string $affEpc,
        public ?string $ar,
        public ?string $cr,
        public ?int $visibleType,
        public ?string $questions,
        public ?int $askApprovalQuestions,
        public ?int $trackingDomainId,
        public ?string $trafficTypesSelected,
        public ?string $trackingDomain,
        public Collection $goals,
        public ?TargetingDTO $targeting,
        public int $creativesCount,
        public Collection $creatives,
        public int $linksCount,
        public Collection $links,
        public Collection $categories,
        public ?string $postbacks = null,
        public ?int $deepLinking = null,
        public Collection $visibleTypeSelected,
        public ?LiveStatsDTO $liveStats = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        /** @var Collection<int, GoalDTO> $goals */
        $goals = self::getCollection(
            $data,
            'goals',
            fn (array $goal): GoalDTO => GoalDTO::fromArray($goal)
        );

        $targetingData = $data['targeting'] ?? null;
        if ($targetingData !== null && ! is_array($targetingData)) {
            $targetingData = null;
        }
        /** @var array<string, mixed>|null $targetingData */
        $targeting = is_array($targetingData) ? TargetingDTO::fromArray($targetingData) : null;

        $creativesData = $data['creatives'] ?? [];
        if (! is_array($creativesData)) {
            $creativesData = [];
        }
        $creativesItems = [];
        foreach ($creativesData as $creative) {
            if (is_array($creative)) {
                /** @var array<string, mixed> $creative */
                $creativesItems[] = CreativeDTO::fromArray($creative);
            }
        }
        /** @var Collection<int, CreativeDTO> $creatives */
        $creatives = new Collection($creativesItems);

        $linksData = $data['links'] ?? [];
        if (! is_array($linksData)) {
            $linksData = [];
        }
        $linksItems = [];
        foreach ($linksData as $link) {
            if (is_array($link)) {
                /** @var array<string, mixed> $link */
                $linksItems[] = OfferLinkDTO::fromArray($link);
            }
        }
        /** @var Collection<int, OfferLinkDTO> $links */
        $links = new Collection($linksItems);

        $categoriesData = $data['categories'] ?? [];
        if (! is_array($categoriesData)) {
            $categoriesData = [];
        }
        $categoriesItems = [];
        foreach ($categoriesData as $category) {
            if (is_array($category)) {
                /** @var array<string, mixed> $category */
                $categoriesItems[] = CategoryDTO::fromArray($category);
            }
        }
        /** @var Collection<int, CategoryDTO> $categories */
        $categories = new Collection($categoriesItems);

        $visibleTypeSelectedData = $data['visible_type_selected'] ?? [];
        if (! is_array($visibleTypeSelectedData)) {
            $visibleTypeSelectedData = [];
        }
        $visibleTypeSelectedItems = [];
        foreach ($visibleTypeSelectedData as $visibleType) {
            if (is_array($visibleType)) {
                /** @var array<string, mixed> $visibleType */
                $visibleTypeSelectedItems[] = VisibleTypeDTO::fromArray($visibleType);
            }
        }
        /** @var Collection<int, VisibleTypeDTO> $visibleTypeSelected */
        $visibleTypeSelected = new Collection($visibleTypeSelectedItems);

        $liveStatsData = $data['live_stats'] ?? null;
        if ($liveStatsData !== null && ! is_array($liveStatsData)) {
            $liveStatsData = null;
        }
        /** @var array<string, mixed>|null $liveStatsData */
        $liveStats = is_array($liveStatsData) ? LiveStatsDTO::fromArray($liveStatsData) : null;

        return new self(
            id: self::getInt($data, 'id'),
            title: self::getString($data, 'title'),
            description: self::getStringOrNull($data, 'description'),
            advertiserId: self::getIntOrNull($data, 'advertiser_id'),
            image: self::getStringOrNull($data, 'image'),
            gtIncludedIds: self::getStringOrNull($data, 'gt_included_ids'),
            gtExcludedIds: self::getStringOrNull($data, 'gt_excluded_ids'),
            gtFilterIds: self::getStringOrNull($data, 'gt_filter_ids'),
            extendedTargeting: self::getStringOrNull($data, 'extended_targeting'),
            trafficTypes: self::getStringOrNull($data, 'traffic_types'),
            isFeatured: self::getIntOrNull($data, 'is_featured'),
            timezone: self::getStringOrNull($data, 'timezone'),
            currency: self::getStringOrNull($data, 'currency'),
            affEpc: self::getStringOrNull($data, 'aff_epc'),
            ar: self::getStringOrNull($data, 'ar'),
            cr: self::getStringOrNull($data, 'cr'),
            visibleType: self::getIntOrNull($data, 'visible_type'),
            questions: self::getStringOrNull($data, 'questions'),
            askApprovalQuestions: self::getIntOrNull($data, 'ask_approval_questions'),
            trackingDomainId: self::getIntOrNull($data, 'tracking_domain_id'),
            trafficTypesSelected: self::getStringOrNull($data, 'traffic_types_selected'),
            trackingDomain: self::getStringOrNull($data, 'tracking_domain'),
            goals: $goals,
            targeting: $targeting,
            creativesCount: self::getIntOrNull($data, 'creatives_count') ?? 0,
            creatives: $creatives,
            linksCount: self::getIntOrNull($data, 'links_count') ?? 0,
            links: $links,
            categories: $categories,
            postbacks: self::getStringOrNull($data, 'postbacks'),
            deepLinking: self::getIntOrNull($data, 'deep_linking'),
            visibleTypeSelected: $visibleTypeSelected,
            liveStats: $liveStats,
        );
    }
}
