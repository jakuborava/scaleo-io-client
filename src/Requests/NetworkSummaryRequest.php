<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\Requests;

use Carbon\Carbon;

class NetworkSummaryRequest
{
    protected ?string $rangeFrom = null;

    protected ?string $rangeTo = null;

    protected ?string $preset = null;

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

    public function preset(string $preset): self
    {
        $this->preset = $preset;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $params = [];

        if ($this->rangeFrom !== null) {
            $params['rangeFrom'] = $this->rangeFrom;
        }

        if ($this->rangeTo !== null) {
            $params['rangeTo'] = $this->rangeTo;
        }

        if ($this->preset !== null) {
            $params['preset'] = $this->preset;
        }

        return $params;
    }
}
