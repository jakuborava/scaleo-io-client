<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use JakubOrava\ScaleoIoClient\DTO\Common\GoalTypeDTO;
use JakubOrava\ScaleoIoClient\DTO\Common\ListResponse;
use JakubOrava\ScaleoIoClient\Requests\BaseRequest;
use JakubOrava\ScaleoIoClient\ScaleoIoClient;

beforeEach(function () {
    $client = new ScaleoIoClient(
        apiKey: 'test-api-key',
        baseUrl: 'https://test.scaletrk.com'
    );
    $this->goalTypes = $client->common()->goalTypes();
});

describe('Goal Types List', function () {
    it('can list goal types', function () {
        Http::fake([
            '*/api/v2/common/lists/goals-types*' => Http::response([
                'goals-types-list' => [
                    [
                        'id' => 1,
                        'title' => 'Sale',
                    ],
                    [
                        'id' => 2,
                        'title' => 'Lead',
                    ],
                    [
                        'id' => 3,
                        'title' => 'Click',
                    ],
                ],
            ]),
        ]);

        $result = $this->goalTypes->list();

        expect($result)->toBeInstanceOf(ListResponse::class)
            ->and($result->count)->toBe(3)
            ->and($result->results)->toHaveCount(3)
            ->and($result->results->first())->toBeInstanceOf(GoalTypeDTO::class)
            ->and($result->results->first()->id)->toBe(1)
            ->and($result->results->first()->title)->toBe('Sale')
            ->and($result->results->last()->id)->toBe(3)
            ->and($result->results->last()->title)->toBe('Click');
    });

    it('can use BaseRequest with pagination', function () {
        Http::fake(['*/api/v2/common/lists/goals-types*' => Http::response(['goals-types-list' => []])]);

        $request = (new BaseRequest)
            ->page(2)
            ->perPage(50);

        $this->goalTypes->list($request);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'page=2') &&
                str_contains($request->url(), 'perPage=50');
        });
    });

    it('makes GET request to correct endpoint', function () {
        Http::fake(['*/api/v2/common/lists/goals-types*' => Http::response(['goals-types-list' => []])]);

        $this->goalTypes->list();

        Http::assertSent(function ($request) {
            return $request->method() === 'GET' &&
                str_contains($request->url(), 'api/v2/common/lists/goals-types');
        });
    });

    it('handles empty results', function () {
        Http::fake(['*/api/v2/common/lists/goals-types*' => Http::response(['goals-types-list' => []])]);

        $result = $this->goalTypes->list();

        expect($result->count)->toBe(0)
            ->and($result->results)->toBeEmpty();
    });
});
