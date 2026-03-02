<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Requests;

use Carbon\Carbon;

class StatisticsReportRequest
{
    public const DEFAULT_COLUMNS = [
        'impressions',
        'ctr',
        'clicks',
        'unique_clicks',
        'duplicate_clicks',
        'invalid_clicks',
        'clicks_payout',
        'cv_approved',
        'cr',
        'approved_payout',
        'cv_total',
        'tr',
        'total_payout',
        'cv_pending',
        'pr',
        'pending_payout',
        'cv_rejected',
        'rr',
        'rejected_payout',
        'epm',
        'epc',
        'epa',
    ];

    protected string $columns;

    protected ?string $rangeFrom = null;

    protected ?string $rangeTo = null;

    protected ?string $breakdown = null;

    protected ?string $lang = null;

    protected ?int $page = null;

    protected ?int $perPage = null;

    protected ?string $sortField = null;

    protected ?string $sortDirection = null;

    /**
     * @var array<string, mixed>
     */
    protected array $filters = [];

    public function __construct()
    {
        $this->columns = implode(',', self::DEFAULT_COLUMNS);
    }

    public function rangeFrom(Carbon|string $date): self
    {
        $this->rangeFrom = $date instanceof Carbon ? $date->format('Y-m-d') : $date;

        return $this;
    }

    public function rangeTo(Carbon|string $date): self
    {
        $this->rangeTo = $date instanceof Carbon ? $date->format('Y-m-d') : $date;

        return $this;
    }

    public function breakdown(string $breakdown): self
    {
        $this->breakdown = $breakdown;

        return $this;
    }

    /**
     * @param  array<int, string>  $columns
     */
    public function columns(array $columns): self
    {
        $this->columns = implode(',', $columns);

        return $this;
    }

    public function lang(string $lang): self
    {
        $this->lang = $lang;

        return $this;
    }

    public function page(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function perPage(int $perPage): self
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function sort(string $field, string $direction = 'asc'): self
    {
        $this->sortField = $field;
        $this->sortDirection = $direction;

        return $this;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function filters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $params = [];

        if ($this->lang !== null) {
            $params['lang'] = $this->lang;
        }

        if ($this->sortField !== null) {
            $params['sortField'] = $this->sortField;
        }

        if ($this->sortDirection !== null) {
            $params['sortDirection'] = $this->sortDirection;
        }

        if ($this->perPage !== null) {
            $params['perPage'] = $this->perPage;
        }

        if ($this->page !== null) {
            $params['page'] = $this->page;
        }

        return $params;
    }

    /**
     * @return array<string, mixed>
     */
    public function toBodyArray(): array
    {
        $body = [];

        if ($this->rangeFrom !== null) {
            $body['rangeFrom'] = $this->rangeFrom;
        }

        if ($this->rangeTo !== null) {
            $body['rangeTo'] = $this->rangeTo;
        }

        if ($this->breakdown !== null) {
            $body['breakdown'] = $this->breakdown;
        }

        $body['columns'] = $this->columns;

        if (count($this->filters) > 0) {
            $body['filters'] = $this->filters;
        }

        return $body;
    }
}
