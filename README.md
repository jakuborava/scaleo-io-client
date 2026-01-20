# Scaleo.io API Client for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jakuborava/scaleo-io-client.svg?style=flat-square)](https://packagist.org/packages/jakuborava/scaleo-io-client)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jakuborava/scaleo-io-client/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jakuborava/scaleo-io-client/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jakuborava/scaleo-io-client/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jakuborava/scaleo-io-client/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jakuborava/scaleo-io-client.svg?style=flat-square)](https://packagist.org/packages/jakuborava/scaleo-io-client)

A comprehensive PHP client for the Scaleo.io Affiliate Network API. This package provides a clean, fluent interface for interacting with all Scaleo.io API endpoints including dashboard statistics, offers, reports, billing, and more.

## Installation

You can install the package via composer:

```bash
composer require jakuborava/scaleo-io-client
```

## Configuration

You can configure the API client in two ways:

### Option 1: Environment Variables (Recommended for Laravel applications)

Set your API credentials in `.env`:

```env
SCALEO_API_KEY=your_api_key_here
SCALEO_BASE_URL=https://your-domain.scaletrk.com
```

Then instantiate the client without parameters:

```php
use JakubOrava\ScaleoIoClient\ScaleoIoClient;

$client = new ScaleoIoClient();
```

**Best for:** Laravel applications where credentials are managed through environment configuration.

### Option 2: Direct Configuration (Recommended for standalone usage)

Pass credentials directly to the constructor:

```php
use JakubOrava\ScaleoIoClient\ScaleoIoClient;

$client = new ScaleoIoClient(
    apiKey: 'your_api_key_here',
    baseUrl: 'https://your-domain.scaletrk.com'
);
```

**Best for:**
- Standalone PHP applications
- Multiple API accounts in the same application
- Testing with different credentials
- Dynamic credential management

### Mixed Configuration

You can also mix both approaches - constructor parameters take priority over environment variables:

```php
// Use environment variables as defaults, but override API key
$client = new ScaleoIoClient(
    apiKey: 'different-api-key',
    baseUrl: null // Will use SCALEO_BASE_URL from environment
);
```

## Usage

### Basic Setup

```php
use JakubOrava\ScaleoIoClient\ScaleoIoClient;

// Using environment variables
$client = new ScaleoIoClient();

// OR using direct configuration
$client = new ScaleoIoClient(
    apiKey: 'your_api_key_here',
    baseUrl: 'https://your-domain.scaletrk.com'
);
```

### Dashboard

Get network summary statistics:

```php
use JakubOrava\ScaleoIoClient\Requests\NetworkSummaryRequest;
use Carbon\Carbon;

// Get network summary for last 7 days
$summary = $client->affiliate()->dashboard()->networkSummary(
    NetworkSummaryRequest::create()
        ->preset('last_7_days')
);

foreach ($summary as $metric) {
    echo $metric->label . ': ' . $metric->total . PHP_EOL;
}
```

### Offers

List and manage offers:

```php
use JakubOrava\ScaleoIoClient\Requests\OffersListRequest;

// List offers with filters
$offers = $client->affiliate()->offers()->list(
    OffersListRequest::create()
        ->search('casino')
        ->countries(['US', 'GB'])
        ->categories([1, 2])
        ->onlyFeatured()
        ->page(1)
        ->perPage(20)
);

foreach ($offers->items as $offer) {
    echo $offer->title . ' - ' . $offer->status . PHP_EOL;
}

// Get single offer
$offer = $client->affiliate()->offers()->get(123);
echo "Payout: {$offer->defaultPayout} {$offer->defaultPayoutCurrency}" . PHP_EOL;

// Download offer assets
$zipPath = $client->affiliate()->offers()->downloadAsset(
    offerId: 123,
    assetId: 456
);
```

### Offer Requests

Manage offer requests:

```php
// List offer requests
$requests = $client->affiliate()->offerRequests()->list();

// List pending requests
$pending = $client->affiliate()->offerRequests()->pending();

// Create new request
$response = $client->affiliate()->offerRequests()->create([
    'offer_id' => 123,
    'message' => 'I would like to promote this offer',
]);
```

### Reports

#### Statistics Report

```php
use JakubOrava\ScaleoIoClient\Requests\StatisticsReportRequest;
use Carbon\Carbon;

$report = $client->affiliate()->reports()->statistics()->list(
    StatisticsReportRequest::create()
        ->rangeFrom(Carbon::now()->subDays(7))
        ->rangeTo(Carbon::now())
        ->columns(['offer_id', 'clicks', 'conversions', 'revenue'])
        ->breakdowns(['day'])
        ->page(1)
        ->perPage(50)
);

foreach ($report->items as $row) {
    $stats = $row->statistics;
    echo "Date: {$row->day_timestamp}" . PHP_EOL;
    echo "Clicks: {$stats->clicks}" . PHP_EOL;
    echo "Conversions: {$stats->conversions}" . PHP_EOL;
    echo "Revenue: {$stats->revenue}" . PHP_EOL;
}

// Get available columns and breakdowns
$columns = $client->affiliate()->reports()->statistics()->options();
$breakdowns = $client->affiliate()->reports()->statistics()->breakdowns();
```

#### Conversions Report

```php
use JakubOrava\ScaleoIoClient\Requests\ConversionsReportRequest;

$conversions = $client->affiliate()->reports()->conversions()->list(
    ConversionsReportRequest::create()
        ->rangeFrom(Carbon::now()->subDays(7))
        ->rangeTo(Carbon::now())
        ->columns(['offer_id', 'status', 'payout'])
);
```

#### Clicks Report

```php
use JakubOrava\ScaleoIoClient\Requests\ClicksReportRequest;

$clicks = $client->affiliate()->reports()->clicks()->list(
    ClicksReportRequest::create()
        ->rangeFrom(Carbon::now()->subDays(1))
        ->rangeTo(Carbon::now())
        ->columns(['offer_id', 'country', 'device'])
);
```

#### Referrals Report

```php
use JakubOrava\ScaleoIoClient\Requests\ReferralsReportRequest;

$referrals = $client->affiliate()->reports()->referrals()->list(
    ReferralsReportRequest::create()
        ->rangeFrom(Carbon::now()->subDays(30))
        ->rangeTo(Carbon::now())
);
```

### Billing

#### Get Balance

```php
$balance = $client->affiliate()->billing()->balances()->get();
echo "Current Balance: {$balance->currentBalance} {$balance->currency}" . PHP_EOL;
```

#### Payment Methods

```php
// List payment methods
$methods = $client->affiliate()->billing()->paymentMethods()->list();

// Get specific payment method details
$method = $client->affiliate()->billing()->paymentMethods()->get('USD');

// Create or update payment method
$client->affiliate()->billing()->paymentMethods()->createOrUpdate([
    'currency' => 'USD',
    'payment_method_id' => 1,
    'fields' => [
        'account_number' => '1234567890',
        'bank_name' => 'Example Bank',
    ],
]);

// Delete payment method
$client->affiliate()->billing()->paymentMethods()->delete(123);
```

#### Invoices

```php
use JakubOrava\ScaleoIoClient\Requests\InvoicesListRequest;

// List invoices
$invoices = $client->affiliate()->billing()->invoices()->list(
    InvoicesListRequest::create()->status('paid')
);

// Get specific invoice
$invoice = $client->affiliate()->billing()->invoices()->get(123);

// Download invoice PDF
$pdfPath = $client->affiliate()->billing()->invoices()->downloadPdf(123);
```

#### Payment Request

```php
$request = $client->affiliate()->billing()->paymentRequest()->create(
    currency: 'USD',
    attachmentFile: '/path/to/invoice.pdf',
    amount: 1000.00
);
```

### Profile

```php
$profile = $client->affiliate()->profile()->get();
echo "Name: {$profile->firstname} {$profile->lastname}" . PHP_EOL;
echo "Email: {$profile->email}" . PHP_EOL;
echo "Manager: {$profile->managerName}" . PHP_EOL;
```

### Postbacks

```php
$postbacks = $client->affiliate()->postbacks()->list();

foreach ($postbacks->items as $postback) {
    echo "Postback: {$postback->url}" . PHP_EOL;
}
```

### Leads

```php
// Standard lead creation
$lead = $client->affiliate()->leads()->create([
    'offer_hash' => 'abc123',
    'click_id' => 'xyz789',
    'email' => 'customer@example.com',
]);

// Create lead by offer ID
$lead = $client->affiliate()->leads()->createByOfferId([
    'offer_id' => 123,
    'click_id' => 'xyz789',
    'email' => 'customer@example.com',
]);

// Create lead upon (success delivery only)
$lead = $client->affiliate()->leads()->createUpon([
    'offer_hash' => 'abc123',
    'click_id' => 'xyz789',
    'email' => 'customer@example.com',
]);
```

### Players

```php
use JakubOrava\ScaleoIoClient\Requests\BaseRequest;

$players = $client->affiliate()->players()->list(
    BaseRequest::create()
        ->page(1)
        ->perPage(50)
);
```

### Traders

```php
$traders = $client->affiliate()->traders()->list(
    BaseRequest::create()->page(1)
);
```

### Error Handling

The client throws specific exceptions for different error types:

```php
use JakubOrava\ScaleoIoClient\Exceptions\AuthenticationException;
use JakubOrava\ScaleoIoClient\Exceptions\ValidationException;
use JakubOrava\ScaleoIoClient\Exceptions\ApiErrorException;
use JakubOrava\ScaleoIoClient\Exceptions\UnexpectedResponseException;

try {
    $offers = $client->affiliate()->offers()->list();
} catch (AuthenticationException $e) {
    // Handle authentication errors (401)
} catch (ValidationException $e) {
    // Handle validation errors (422)
    print_r($e->getErrors());
} catch (ApiErrorException $e) {
    // Handle other API errors
    echo "Error ({$e->getStatusCode()}): " . $e->getMessage();
} catch (UnexpectedResponseException $e) {
    // Handle unexpected response format
}
```

### Pagination

All list endpoints return a `PaginatedResponse` object:

```php
$offers = $client->affiliate()->offers()->list(
    OffersListRequest::create()->page(1)->perPage(20)
);

// Access pagination metadata
echo "Total items: {$offers->totalItems}" . PHP_EOL;
echo "Current page: {$offers->currentPage}" . PHP_EOL;
echo "Total pages: {$offers->totalPages}" . PHP_EOL;

// Access items (Collection)
foreach ($offers->items as $offer) {
    // Process each offer
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Jakub Orava](https://github.com/jakuborava)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
