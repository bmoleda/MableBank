<?php

declare(strict_types=1);

namespace App\Dtos;

use App\Models\BaseAccount;

final readonly class BalanceLoadResult
{
    /**
     * @param array<string, BaseAccount> $accounts
     * @param list<string> $errors
     */
    public function __construct(
        public array $accounts,
        public array $errors,
    ) {
    }
}
