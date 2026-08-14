<?php

declare(strict_types=1);

namespace App\Models;

abstract class BaseAccount

{
    public function __construct(
        public readonly string $accountNumber,
        // The balance is stored as an integer representing the amount in cents to avoid floating-point precision issues:
        protected int $balance,
    ) {
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function addToBalance(int $amount): void
    {
        $this->balance += $amount;
    }

    public function removeFromBalance(int $amount): void
    {
        $this->balance -= $amount;
    }
}
