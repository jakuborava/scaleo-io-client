<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Affiliate;

use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class BillingPreferencesDTO
{
    use ArrayHelpers;

    /**
     * @param  array<string, mixed>  $invoiceFrequency
     * @param  array<string, mixed>  $paymentTerms
     */
    public function __construct(
        public array $invoiceFrequency,
        public string $invoiceDaysOfTheMonth,
        public string $invoiceDayOfTheWeek,
        public int $generateInvoiceAutomatically,
        public array $paymentTerms,
        public string $beneficiaryName,
        public string $beneficiaryAddress,
        public string $billingEmail,
        public string $taxId,
        public int $vat,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $invoiceFrequency = $data['invoice_frequency'] ?? [];
        if (! is_array($invoiceFrequency)) {
            $invoiceFrequency = [];
        }
        /** @var array<string, mixed> $invoiceFrequency */

        $paymentTerms = $data['payment_terms'] ?? [];
        if (! is_array($paymentTerms)) {
            $paymentTerms = [];
        }
        /** @var array<string, mixed> $paymentTerms */

        return new self(
            invoiceFrequency: $invoiceFrequency,
            invoiceDaysOfTheMonth: self::getString($data, 'invoice_days_of_the_month'),
            invoiceDayOfTheWeek: self::getString($data, 'invoice_day_of_the_week'),
            generateInvoiceAutomatically: self::getInt($data, 'generate_invoice_automatically'),
            paymentTerms: $paymentTerms,
            beneficiaryName: self::getString($data, 'beneficiary_name'),
            beneficiaryAddress: self::getString($data, 'beneficiary_address'),
            billingEmail: self::getString($data, 'billing_email'),
            taxId: self::getString($data, 'tax_id'),
            vat: self::getInt($data, 'vat'),
        );
    }
}
