<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Affiliate;

use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class ProfileDTO
{
    use ArrayHelpers;

    public function __construct(
        public int $id,
        public string $email,
        public string $firstname,
        public string $lastname,
        public ?string $image,
        public ?string $contacts,
        public int $status,
        public int $apiStatus,
        public ?string $apiKey,
        public int $numberFormatId,
        public int $dateFormatId,
        public ?string $phone,
        public string $timezone,
        public string $role,
        public ?string $companyName,
        public ?string $paymentDetails,
        public ?string $customFields,
        public ?int $country,
        public ?string $region,
        public ?string $city,
        public ?string $address,
        public ?string $postalCode,
        public ?string $managersAssigned,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: self::getInt($data, 'id'),
            email: self::getString($data, 'email'),
            firstname: self::getString($data, 'firstname'),
            lastname: self::getString($data, 'lastname'),
            image: self::getStringOrNull($data, 'image'),
            contacts: self::getStringOrNull($data, 'contacts'),
            status: self::getInt($data, 'status'),
            apiStatus: self::getInt($data, 'api_status'),
            apiKey: self::getStringOrNull($data, 'api_key'),
            numberFormatId: self::getInt($data, 'number_format_id'),
            dateFormatId: self::getInt($data, 'date_format_id'),
            phone: self::getStringOrNull($data, 'phone'),
            timezone: self::getString($data, 'timezone'),
            role: self::getString($data, 'role'),
            companyName: self::getStringOrNull($data, 'company_name'),
            paymentDetails: self::getStringOrNull($data, 'payment_details'),
            customFields: self::getStringOrNull($data, 'custom_fields'),
            country: self::getIntOrNull($data, 'country'),
            region: self::getStringOrNull($data, 'region'),
            city: self::getStringOrNull($data, 'city'),
            address: self::getStringOrNull($data, 'address'),
            postalCode: self::getStringOrNull($data, 'postal_code'),
            managersAssigned: self::getStringOrNull($data, 'managers_assigned'),
        );
    }
}
