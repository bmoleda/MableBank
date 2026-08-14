<?php

declare(strict_types=1);

namespace App\Models;

final class MableAccount extends BaseAccount
{
    public function removeFromBalance(Money $amount): void
    {
        $newBalance = $this->balance->subtract($amount);

        if ($newBalance->cents < 0) {
            throw new \Exception("Balance cannot be negative. Current balance: {$this->balance->toDecimalString()}, attempted to remove: {$amount->toDecimalString()}.");
        }

        $this->balance = $newBalance;
    }
}
