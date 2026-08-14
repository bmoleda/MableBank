<?php

declare(strict_types=1);

namespace App\Enums;

enum TransferStatus
{
    case Accepted;
    case Rejected;
}
