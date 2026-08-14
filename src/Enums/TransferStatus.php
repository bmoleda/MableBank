<?php

declare(strict_types=1);

namespace App\Enums;

enum TransferStatus: string
{
    case Accepted = 'accepted';
    case Rejected = 'rejected';

    public function isAccepted(): bool
    {
        return $this === self::Accepted;
    }
}
