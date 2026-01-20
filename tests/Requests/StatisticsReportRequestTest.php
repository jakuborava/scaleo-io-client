<?php

declare(strict_types=1);

use Carbon\Carbon;
use JakubOrava\ScaleoIoClient\Requests\StatisticsReportRequest;

beforeEach(function () {
    $this->request = new StatisticsReportRequest;
});

describe('Date Range', function () {
    it('can set rangeFrom with Carbon', function () {
        $date = Carbon::parse('2024-01-01');
        $this->request->rangeFrom($date);

        expect($this->request->toBodyArray())->toHaveKey('rangeFrom', '2024-01-01');
    });

    it('can set rangeFrom with string', function () {
        $this->request->rangeFrom('2024-01-01');

        expect($this->request->toBodyArray())->toHaveKey('rangeFrom', '2024-01-01');
    });

    it('can set rangeTo with Carbon', function () {
        $date = Carbon::parse('2024-12-31');
        $this->request->rangeTo($date);

        expect($this->request->toBodyArray())->toHaveKey('rangeTo', '2024-12-31');
    });

    it('can set rangeTo with string', function () {
        $this->request->rangeTo('2024-12-31');

        expect($this->request->toBodyArray())->toHaveKey('rangeTo', '2024-12-31');
    });

    it('returns fluent interface', function () {
        $result = $this->request->rangeFrom('2024-01-01');

        expect($result)->toBeInstanceOf(StatisticsReportRequest::class);
    });
});

describe('Breakdown', function () {
    it('can set breakdown', function () {
        $this->request->breakdown('day');

        expect($this->request->toBodyArray())->toHaveKey('breakdown', 'day');
    });

    it('returns fluent interface', function () {
        $result = $this->request->breakdown('month');

        expect($result)->toBeInstanceOf(StatisticsReportRequest::class);
    });
});

describe('Columns', function () {
    it('can set columns as array', function () {
        $this->request->columns(['clicks', 'conversions', 'revenue']);

        expect($this->request->toBodyArray())->toHaveKey('columns', 'clicks,conversions,revenue');
    });

    it('handles single column', function () {
        $this->request->columns(['clicks']);

        expect($this->request->toBodyArray())->toHaveKey('columns', 'clicks');
    });

    it('returns fluent interface', function () {
        $result = $this->request->columns(['clicks']);

        expect($result)->toBeInstanceOf(StatisticsReportRequest::class);
    });
});

describe('Language', function () {
    it('can set language', function () {
        $this->request->lang('en');

        expect($this->request->toArray())->toHaveKey('lang', 'en');
    });

    it('returns fluent interface', function () {
        $result = $this->request->lang('en');

        expect($result)->toBeInstanceOf(StatisticsReportRequest::class);
    });
});

describe('Pagination', function () {
    it('can set page', function () {
        $this->request->page(2);

        expect($this->request->toArray())->toHaveKey('page', 2);
    });

    it('can set per page', function () {
        $this->request->perPage(50);

        expect($this->request->toArray())->toHaveKey('perPage', 50);
    });
});

describe('Sorting', function () {
    it('can set sort with default direction', function () {
        $this->request->sort('clicks');

        expect($this->request->toArray())
            ->toHaveKey('sortField', 'clicks')
            ->and($this->request->toArray())
            ->toHaveKey('sortDirection', 'asc');
    });

    it('can set sort with custom direction', function () {
        $this->request->sort('revenue', 'desc');

        expect($this->request->toArray())
            ->toHaveKey('sortField', 'revenue')
            ->and($this->request->toArray())
            ->toHaveKey('sortDirection', 'desc');
    });
});

describe('Filters', function () {
    it('can set filters', function () {
        $filters = [
            'offer_id' => [1, 2, 3],
            'status' => 'approved',
        ];

        $this->request->filters($filters);

        expect($this->request->toBodyArray())->toHaveKey('filters', $filters);
    });

    it('returns fluent interface', function () {
        $result = $this->request->filters(['test' => 'value']);

        expect($result)->toBeInstanceOf(StatisticsReportRequest::class);
    });

    it('excludes empty filters from body', function () {
        $this->request->filters([]);

        expect($this->request->toBodyArray())->not->toHaveKey('filters');
    });
});

describe('toArray vs toBodyArray', function () {
    it('toArray returns query parameters', function () {
        $this->request
            ->page(1)
            ->perPage(20)
            ->sort('clicks', 'desc')
            ->lang('en');

        $params = $this->request->toArray();

        expect($params)->toHaveKey('page', 1)
            ->and($params)->toHaveKey('perPage', 20)
            ->and($params)->toHaveKey('sortField', 'clicks')
            ->and($params)->toHaveKey('sortDirection', 'desc')
            ->and($params)->toHaveKey('lang', 'en')
            ->and($params)->not->toHaveKey('rangeFrom')
            ->and($params)->not->toHaveKey('rangeTo');
    });

    it('toBodyArray returns body parameters', function () {
        $this->request
            ->rangeFrom('2024-01-01')
            ->rangeTo('2024-12-31')
            ->breakdown('day')
            ->columns(['clicks', 'revenue'])
            ->filters(['offer_id' => [1]]);

        $body = $this->request->toBodyArray();

        expect($body)->toHaveKey('rangeFrom', '2024-01-01')
            ->and($body)->toHaveKey('rangeTo', '2024-12-31')
            ->and($body)->toHaveKey('breakdown', 'day')
            ->and($body)->toHaveKey('columns', 'clicks,revenue')
            ->and($body)->toHaveKey('filters')
            ->and($body)->not->toHaveKey('page')
            ->and($body)->not->toHaveKey('perPage');
    });
});

describe('Complete Request', function () {
    it('can build complete request with all parameters', function () {
        $this->request
            ->rangeFrom('2024-01-01')
            ->rangeTo('2024-12-31')
            ->breakdown('month')
            ->columns(['clicks', 'conversions', 'revenue'])
            ->filters(['offer_id' => [1, 2]])
            ->page(1)
            ->perPage(50)
            ->sort('revenue', 'desc')
            ->lang('en');

        $params = $this->request->toArray();
        $body = $this->request->toBodyArray();

        expect($params)->toHaveKeys(['page', 'perPage', 'sortField', 'sortDirection', 'lang'])
            ->and($body)->toHaveKeys(['rangeFrom', 'rangeTo', 'breakdown', 'columns', 'filters']);
    });
});
