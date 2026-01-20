<?php

declare(strict_types=1);

use JakubOrava\ScaleoIoClient\Requests\OffersListRequest;

beforeEach(function () {
    $this->request = new OffersListRequest;
});

it('extends BaseRequest', function () {
    expect($this->request)->toBeInstanceOf(OffersListRequest::class);
});

describe('Search', function () {
    it('can set search parameter', function () {
        $this->request->search('casino');

        expect($this->request->toArray())->toHaveKey('search', 'casino');
    });

    it('returns fluent interface', function () {
        $result = $this->request->search('test');

        expect($result)->toBeInstanceOf(OffersListRequest::class);
    });
});

describe('Countries Filter', function () {
    it('can set countries parameter', function () {
        $this->request->countries([1, 2, 3]);

        expect($this->request->toArray())->toHaveKey('countries', '1,2,3');
    });

    it('handles single country', function () {
        $this->request->countries([1]);

        expect($this->request->toArray())->toHaveKey('countries', '1');
    });

    it('returns fluent interface', function () {
        $result = $this->request->countries([1, 2]);

        expect($result)->toBeInstanceOf(OffersListRequest::class);
    });
});

describe('Categories Filter', function () {
    it('can set categories parameter', function () {
        $this->request->categories([5, 10, 15]);

        expect($this->request->toArray())->toHaveKey('categories', '5,10,15');
    });

    it('returns fluent interface', function () {
        $result = $this->request->categories([1]);

        expect($result)->toBeInstanceOf(OffersListRequest::class);
    });
});

describe('Tags Filter', function () {
    it('can set tags parameter', function () {
        $this->request->tags([1, 2, 3]);

        expect($this->request->toArray())->toHaveKey('tags', '1,2,3');
    });

    it('returns fluent interface', function () {
        $result = $this->request->tags([1]);

        expect($result)->toBeInstanceOf(OffersListRequest::class);
    });
});

describe('Goals Types Filter', function () {
    it('can set goals types parameter', function () {
        $this->request->goalsTypes(5);

        expect($this->request->toArray())->toHaveKey('goalsTypes', 5);
    });

    it('returns fluent interface', function () {
        $result = $this->request->goalsTypes(1);

        expect($result)->toBeInstanceOf(OffersListRequest::class);
    });
});

describe('Only Featured', function () {
    it('sets onlyFeatured to yes by default', function () {
        $this->request->onlyFeatured();

        expect($this->request->toArray())->toHaveKey('onlyFeatured', 'yes');
    });

    it('can set onlyFeatured to no', function () {
        $this->request->onlyFeatured(false);

        expect($this->request->toArray())->toHaveKey('onlyFeatured', 'no');
    });

    it('returns fluent interface', function () {
        $result = $this->request->onlyFeatured();

        expect($result)->toBeInstanceOf(OffersListRequest::class);
    });
});

describe('Only New', function () {
    it('sets onlyNew to yes by default', function () {
        $this->request->onlyNew();

        expect($this->request->toArray())->toHaveKey('onlyNew', 'yes');
    });

    it('can set onlyNew to no', function () {
        $this->request->onlyNew(false);

        expect($this->request->toArray())->toHaveKey('onlyNew', 'no');
    });

    it('returns fluent interface', function () {
        $result = $this->request->onlyNew();

        expect($result)->toBeInstanceOf(OffersListRequest::class);
    });
});

describe('Complex Filters', function () {
    it('can combine multiple filters', function () {
        $this->request
            ->search('gaming')
            ->countries([1, 2])
            ->categories([5])
            ->onlyFeatured()
            ->page(1)
            ->perPage(20);

        $result = $this->request->toArray();

        expect($result)->toHaveKey('search', 'gaming')
            ->and($result)->toHaveKey('countries', '1,2')
            ->and($result)->toHaveKey('categories', '5')
            ->and($result)->toHaveKey('onlyFeatured', 'yes')
            ->and($result)->toHaveKey('page', 1)
            ->and($result)->toHaveKey('perPage', 20);
    });
});
