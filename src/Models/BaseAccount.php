<?php

declare(strict_types=1);

namespace App\Models;

abstract class BaseAccount
{
    public function __construct(
        public readonly string $accountNumber,
        protected Money $balance,
    ) {
    }

    public function getBalance(): Money
    {
        return $this->balance;
    }

    public function credit(Money $amount): void
    {
        $this->balance = $this->balance->add($amount);
    }

    public function debit(Money $amount): void
    {
        $this->balance = $this->balance->subtract($amount);
    }

    public static function validateAccountNumber(string $accountNumber): void
    {
        return;
    }
}
