<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Requests;

class InvoicesListRequest extends BaseRequest
{
    /**
     * @param  array<int, string>  $columns
     */
    public function columns(array $columns): self
    {
        return $this->addParam('columns', implode(',', $columns));
    }

    public function statuses(string $statuses): self
    {
        return $this->addParam('statuses', $statuses);
    }
}
