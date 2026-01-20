<?php

declare(strict_types=1);

use JakubOrava\ScaleoIoClient\Requests\BaseRequest;

beforeEach(function () {
    $this->request = new BaseRequest;
});

describe('Pagination', function () {
    it('can set page', function () {
        $result = $this->request->page(2);

        expect($result)->toBeInstanceOf(BaseRequest::class)
            ->and($this->request->toArray())->toBe(['page' => 2]);
    });

    it('can set per page', function () {
        $result = $this->request->perPage(50);

        expect($result)->toBeInstanceOf(BaseRequest::class)
            ->and($this->request->toArray())->toBe(['perPage' => 50]);
    });

    it('can chain pagination methods', function () {
        $this->request->page(3)->perPage(25);

        expect($this->request->toArray())->toBe([
            'page' => 3,
            'perPage' => 25,
        ]);
    });
});

describe('Sorting', function () {
    it('can set sort with default direction', function () {
        $this->request->sort('name');

        expect($this->request->toArray())->toBe([
            'sortField' => 'name',
            'sortDirection' => 'asc',
        ]);
    });

    it('can set sort with custom direction', function () {
        $this->request->sort('created_at', 'desc');

        expect($this->request->toArray())->toBe([
            'sortField' => 'created_at',
            'sortDirection' => 'desc',
        ]);
    });

    it('returns fluent interface', function () {
        $result = $this->request->sort('name');

        expect($result)->toBeInstanceOf(BaseRequest::class);
    });
});

describe('Fields Selection', function () {
    it('can set fields', function () {
        $this->request->fields(['id', 'name', 'email']);

        expect($this->request->toArray())->toBe([
            'fields' => 'id,name,email',
        ]);
    });

    it('returns fluent interface', function () {
        $result = $this->request->fields(['id', 'name']);

        expect($result)->toBeInstanceOf(BaseRequest::class);
    });

    it('handles empty fields array', function () {
        $this->request->fields([]);

        expect($this->request->toArray())->toBe([]);
    });
});

describe('Custom Parameters', function () {
    it('can add custom parameter', function () {
        $this->request->addParam('custom', 'value');

        expect($this->request->toArray())->toBe([
            'custom' => 'value',
        ]);
    });

    it('can add multiple custom parameters', function () {
        $this->request
            ->addParam('param1', 'value1')
            ->addParam('param2', 'value2');

        expect($this->request->toArray())->toHaveKey('param1', 'value1')
            ->and($this->request->toArray())->toHaveKey('param2', 'value2');
    });

    it('returns fluent interface', function () {
        $result = $this->request->addParam('test', 'value');

        expect($result)->toBeInstanceOf(BaseRequest::class);
    });

    it('filters out null custom parameters', function () {
        $this->request->addParam('nullParam', null);

        expect($this->request->toArray())->toBe([]);
    });
});

describe('toArray Method', function () {
    it('returns empty array when no parameters set', function () {
        expect($this->request->toArray())->toBe([]);
    });

    it('combines all parameters', function () {
        $this->request
            ->page(1)
            ->perPage(20)
            ->sort('name', 'asc')
            ->fields(['id', 'name'])
            ->addParam('search', 'test');

        $expected = [
            'page' => 1,
            'perPage' => 20,
            'sortField' => 'name',
            'sortDirection' => 'asc',
            'fields' => 'id,name',
            'search' => 'test',
        ];

        expect($this->request->toArray())->toBe($expected);
    });

    it('only includes set parameters', function () {
        $this->request->page(1);

        expect($this->request->toArray())->toBe(['page' => 1]);
    });
});

describe('Fluent Interface', function () {
    it('allows method chaining', function () {
        $result = $this->request
            ->page(1)
            ->perPage(10)
            ->sort('id', 'desc')
            ->fields(['id', 'name'])
            ->addParam('custom', 'value');

        expect($result)->toBeInstanceOf(BaseRequest::class)
            ->and($this->request->toArray())->toHaveKey('page')
            ->and($this->request->toArray())->toHaveKey('perPage')
            ->and($this->request->toArray())->toHaveKey('sortField')
            ->and($this->request->toArray())->toHaveKey('custom');
    });
});
