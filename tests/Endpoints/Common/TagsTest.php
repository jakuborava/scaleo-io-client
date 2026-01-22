<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use JakubOrava\ScaleoIoClient\DTO\Common\ListResponse;
use JakubOrava\ScaleoIoClient\DTO\Common\TagDTO;
use JakubOrava\ScaleoIoClient\Requests\BaseRequest;
use JakubOrava\ScaleoIoClient\ScaleoIoClient;

beforeEach(function () {
    $client = new ScaleoIoClient(
        apiKey: 'test-api-key',
        baseUrl: 'https://test.scaletrk.com'
    );
    $this->tags = $client->common()->tags();
});

describe('Tags List', function () {
    it('can list tags', function () {
        Http::fake([
            '*/api/v2/common/lists/tags*' => Http::response([
                'tags-list' => [
                    [
                        'id' => 1,
                        'title' => 'Exclusive',
                        'sort' => 0
                    ],
                    [
                        'id' => 2,
                        'title' => 'Featured',
                        'sort' => 0
                    ],
                ],
            ]),
        ]);

        $result = $this->tags->list();

        expect($result)->toBeInstanceOf(ListResponse::class)
            ->and($result->count)->toBe(2)
            ->and($result->results)->toHaveCount(2)
            ->and($result->results->first())->toBeInstanceOf(TagDTO::class)
            ->and($result->results->first()->id)->toBe(1)
            ->and($result->results->first()->title)->toBe('Exclusive')
            ->and($result->results->first()->sort)->toBe(0)
            ->and($result->results->last()->id)->toBe(2)
            ->and($result->results->last()->title)->toBe('Featured')
            ->and($result->results->last()->sort)->toBe(0);
    });

    it('can use BaseRequest with pagination', function () {
        Http::fake(['*/api/v2/common/lists/tags*' => Http::response(['tags-list' => []])]);

        $request = (new BaseRequest)
            ->page(2)
            ->perPage(50);

        $this->tags->list($request);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'page=2') &&
                   str_contains($request->url(), 'perPage=50');
        });
    });

    it('makes GET request to correct endpoint', function () {
        Http::fake(['*/api/v2/common/lists/tags*' => Http::response(['tags-list' => []])]);

        $this->tags->list();

        Http::assertSent(function ($request) {
            return $request->method() === 'GET' &&
                   str_contains($request->url(), 'api/v2/common/lists/tags');
        });
    });

    it('handles empty results', function () {
        Http::fake(['*/api/v2/common/lists/tags*' => Http::response(['tags-list' => []])]);

        $result = $this->tags->list();

        expect($result->count)->toBe(0)
            ->and($result->results)->toBeEmpty();
    });
});
