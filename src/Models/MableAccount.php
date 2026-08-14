<?php

declare(strict_types=1);

namespace App\Models;

final class MableAccount extends BaseAccount
{
    private const string ACCOUNT_NUMBER_PATTERN = '/^\d{16}$/';

    public function removeFromBalance(Money $amount): void
    {
        $newBalance = $this->balance->subtract($amount);

        if ($newBalance->cents < 0) {
            throw new \Exception("Balance cannot be negative. Current balance: {$this->balance->toDecimalString()}, attempted to remove: {$amount->toDecimalString()}.");
        }

        $this->balance = $newBalance;
    }

    public static function validateAccountNumber(string $accountNumber): void
    {
        if (! preg_match(self::ACCOUNT_NUMBER_PATTERN, $accountNumber)) {
            throw new \InvalidArgumentException("'{$accountNumber}' is not a valid account number.");
        }
    }
}
