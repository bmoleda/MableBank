<?php

declare(strict_types=1);

namespace App\Models;

final class MableAccount extends BaseAccount
{
    public function removeFromBalance(int $amount): void
    {
        $newBalance = $this->balance - $amount;

        if ($newBalance < 0) {
            throw new \Exception("Balance cannot be negative. Current balance: {$this->balance}, attempted to remove: {$amount}.");
        }

        $this->balance = $newBalance;
    }
}
