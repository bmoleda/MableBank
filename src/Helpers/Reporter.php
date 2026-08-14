<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Dtos\BalanceLoadResult;

final class Reporter
{
    public function reportBalanceLoad(string $path, BalanceLoadResult $result): string
    {
        $lines = [sprintf("Loaded %d account balance(s) from %s.", count($result->accounts), $path)];

        foreach ($result->errors as $error) {
            $lines[] = "  - {$error}";
        }

        return implode(PHP_EOL, $lines);
    }
}
