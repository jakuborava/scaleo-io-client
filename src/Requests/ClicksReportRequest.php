<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Requests;

use Carbon\Carbon;

class ClicksReportRequest
{
    public const DEFAULT_COLUMNS = [
        'transaction_id',
        'added_timestamp',
        'payout',
        'sub_id1',
        'sub_id2',
        'sub_id3',
        'sub_id4',
        'sub_id5',
        'aff_param1',
        'aff_param2',
        'aff_param3',
        'aff_param4',
        'aff_param5',
        'aff_click_id',
        'deep_link_url',
        'source',
        'offer',
        'goal',
        'goal_type',
        'link',
        'creative',
        'language',
        'connection_type',
        'mobile_operator',
        'idfa',
        'gaid',
        'ip',
        'geo',
        'device_type',
        'device_brand',
        'device_model',
        'device_os',
        'device_os_version',
        'browser',
        'browser_version',
    ];

    protected string $columns;

    protected ?string $rangeFrom = null;

    protected ?string $rangeTo = null;

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

    /**
     * @param  array<int, string>  $columns
     */
    public function columns(array $columns): self
    {
        $this->columns = implode(',', $columns);

        return $this;
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

    public function sort(string $field, string $direction = 'desc'): self
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

        $body['columns'] = $this->columns;

        if (count($this->filters) > 0) {
            $body['filters'] = $this->filters;
        }

        return $body;
    }
}
