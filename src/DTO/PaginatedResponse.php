<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO;

use Illuminate\Support\Collection;

/**
 * @template T
 */
readonly class PaginatedResponse
{
    /**
     * @param  Collection<int, T>  $items
     */
    public function __construct(
        public Collection $items,
        public int $totalItems,
        public ?int $currentPage = null,
        public ?int $perPage = null,
        public ?int $totalPages = null,
    ) {}

    public function hasMorePages(): bool
    {
        if ($this->currentPage === null || $this->totalPages === null) {
            return false;
        }

        return $this->currentPage < $this->totalPages;
    }

    /**
     * Create a paginated response from API response data with pagination headers
     *
     * @template TItem
     *
     * @param  array<string, mixed>  $response
     * @param  array<string, mixed>  $paginationHeaders
     * @param  callable(array<string, mixed>): TItem  $mapper
     * @return self<TItem>
     */
    public static function fromResponse(array $response, string $itemsKey, array $paginationHeaders, callable $mapper): self
    {
        /** @var array<mixed> $itemsData */
        $itemsData = $response[$itemsKey] ?? [];

        /** @var Collection<int, TItem> $items */
        $items = new Collection($itemsData)
            ->map(function (mixed $item) use ($mapper): mixed {
                if (! is_array($item)) {
                    throw new \InvalidArgumentException('Expected array for item in response');
                }

                /** @var array<string, mixed> $item */
                return $mapper($item);
            });

        $totalItems = isset($paginationHeaders['x-pagination-total-count'][0])
            ? (int) $paginationHeaders['x-pagination-total-count'][0]
            : 0;

        $currentPage = isset($paginationHeaders['x-pagination-current-page'][0])
            ? (int) $paginationHeaders['x-pagination-current-page'][0]
            : null;

        $perPage = isset($paginationHeaders['x-pagination-per-page'][0])
            ? (int) $paginationHeaders['x-pagination-per-page'][0]
            : null;

        $totalPages = isset($paginationHeaders['x-pagination-page-count'][0])
            ? (int) $paginationHeaders['x-pagination-page-count'][0]
            : null;

        return new self(
            items: $items,
            totalItems: $totalItems,
            currentPage: $currentPage,
            perPage: $perPage,
            totalPages: $totalPages,
        );
    }
}
