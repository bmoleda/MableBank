<?php

declare(strict_types=1);

namespace App\Dtos;

use App\Enums\TransferStatus;
use App\Models\Money;

final readonly class TransferResult
{
    public function __construct(
        public ?string $fromAccountNumber,
        public ?string $toAccountNumber,
        public ?Money $amount,
        public TransferStatus $status,
        public ?string $reason,
    ) {
    }
}
