<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Requests;

abstract class BaseRequest
{
    protected ?int $page = null;

    protected ?int $perPage = null;

    protected ?string $sortField = null;

    protected ?string $sortDirection = null;

    /**
     * @var array<int, string>|null
     */
    protected ?array $fields = null;

    /**
     * @var array<string, mixed>
     */
    protected array $customParams = [];

    public function page(int $page): static
    {
        $this->page = $page;

        return $this;
    }

    public function perPage(int $perPage): static
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function sort(string $field, string $direction = 'asc'): static
    {
        $this->sortField = $field;
        $this->sortDirection = $direction;

        return $this;
    }

    /**
     * @param  array<int, string>  $fields
     */
    public function fields(array $fields): static
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * @param  mixed  $value
     */
    public function addParam(string $key, $value): static
    {
        $this->customParams[$key] = $value;

        return $this;
    }

    /**
     * Build the query parameters array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $params = [];

        if ($this->page !== null) {
            $params['page'] = $this->page;
        }

        if ($this->perPage !== null) {
            $params['perPage'] = $this->perPage;
        }

        if ($this->sortField !== null) {
            $params['sortField'] = $this->sortField;
        }

        if ($this->sortDirection !== null) {
            $params['sortDirection'] = $this->sortDirection;
        }

        if ($this->fields !== null && count($this->fields) > 0) {
            $params['fields'] = implode(',', $this->fields);
        }

        // Merge custom parameters
        foreach ($this->customParams as $key => $value) {
            if ($value !== null) {
                $params[$key] = $value;
            }
        }

        return $params;
    }
}
