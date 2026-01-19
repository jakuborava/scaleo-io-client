<?php

namespace JakubOrava\ScaleoIoClient\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JakubOrava\ScaleoIoClient\ScaleoIoClient
 */
class ScaleoIoClient extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \JakubOrava\ScaleoIoClient\ScaleoIoClient::class;
    }
}
