<?php

namespace JakubOrava\ScaleoIoClient;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use JakubOrava\ScaleoIoClient\Commands\ScaleoIoClientCommand;

class ScaleoIoClientServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('scaleo-io-client')
            ->hasConfigFile();
    }
}
