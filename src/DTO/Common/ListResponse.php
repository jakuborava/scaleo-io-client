<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Common;

use Illuminate\Support\Collection;

/**
 * @template T
 */
readonly class ListResponse
{
    /**
     * @param  Collection<int, T>  $results
     */
    public function __construct(
        public int $count,
        public Collection $results,
    ) {}

    /**
     * Create a list response from API response data
     *
     * @template TItem
     *
     * @param  array<string, mixed>  $response
     * @param  callable(array<string, mixed>): TItem  $mapper
     * @return self<TItem>
     */
    public static function fromResponse(array $response, string $key, callable $mapper): self
    {
        /** @var array<mixed> $resultsData */
        $resultsData = $response[$key] ?? [];

        $count = count($resultsData);

        /** @var Collection<int, TItem> $results */
        $results = new Collection($resultsData)
            ->map(function (mixed $item) use ($mapper): mixed {
                if (! is_array($item)) {
                    throw new \InvalidArgumentException('Expected array for item in response');
                }

                /** @var array<string, mixed> $item */
                return $mapper($item);
            });

        return new self(
            count: $count,
            results: $results,
        );
    }
}
