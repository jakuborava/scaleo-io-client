<?php

namespace JakubOrava\ScaleoIoClient\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use JakubOrava\ScaleoIoClient\ScaleoIoClientServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'JakubOrava\\ScaleoIoClient\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ScaleoIoClientServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
         foreach (\Illuminate\Support\Facades\File::allFiles(__DIR__ . '/../database/migrations') as $migration) {
            (include $migration->getRealPath())->up();
         }
         */
    }

    /**
     * Create a test client with test credentials
     */
    protected function createTestClient(): \JakubOrava\ScaleoIoClient\ScaleoIoClient
    {
        return new \JakubOrava\ScaleoIoClient\ScaleoIoClient(
            apiKey: 'test-api-key',
            baseUrl: 'https://test.scaletrk.com'
        );
    }
}
