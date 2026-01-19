<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Requests;

use Carbon\Carbon;

class ReferralsReportRequest
{
    protected ?string $rangeFrom = null;

    protected ?string $rangeTo = null;

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

        return $body;
    }
}
