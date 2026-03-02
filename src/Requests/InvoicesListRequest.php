<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Requests;

class InvoicesListRequest extends BaseRequest
{
    public const DEFAULT_COLUMNS = [
        'invoice_number',
        'date',
        'affiliate',
        'payment_method',
        'status',
        'amount',
        'period',
        'attachment',
        'internal_notes',
    ];

    public function __construct()
    {
        $this->addParam('columns', implode(',', self::DEFAULT_COLUMNS));
    }

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
