<?php

declare(strict_types=1);

namespace JakubOrava\ScaleoIoClient\DTO\Affiliate;

use Illuminate\Support\Collection;
use JakubOrava\ScaleoIoClient\DTO\ArrayHelpers;

readonly class BalanceDTO
{
    use ArrayHelpers;

    /**
     * @param  Collection<int, array<string, mixed>>  $balanceDueByCurrencies
     */
    public function __construct(
        public string $currency,
        public string $approvedBalance,
        public string $pendingBalance,
        public string $balanceDue,
        public Collection $balanceDueByCurrencies,
        public int $availableAdvance,
        public string $referralBalance,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $balanceDueByCurrencies = $data['balance_due_by_currencies'] ?? [];
        if (! is_array($balanceDueByCurrencies)) {
            $balanceDueByCurrencies = [];
        }

        return new self(
            currency: self::getString($data, 'currency'),
            approvedBalance: self::getString($data, 'approved_balance'),
            pendingBalance: self::getString($data, 'pending_balance'),
            balanceDue: self::getString($data, 'balance_due'),
            balanceDueByCurrencies: new Collection($balanceDueByCurrencies),
            availableAdvance: self::getInt($data, 'available_advance'),
            referralBalance: self::getString($data, 'referral_balance'),
        );
    }
}
