<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Dtos\BalanceLoadResult;
use App\Dtos\TransferResult;

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

    /**
     * @param list<TransferResult> $results
     */
    public function reportTransfers(string $path, array $results): string
    {
        $lines = ["Processed transfers from {$path}:"];
        $appliedCount = 0;
        $rejectedCount = 0;

        foreach ($results as $result) {
            $lines[] = $this->formatTransferResult($result);

            if ($result->status->isAccepted()) {
                $appliedCount++;
            } else {
                $rejectedCount++;
            }
        }

        $lines[] = sprintf("Summary: %d applied, %d rejected.", $appliedCount, $rejectedCount);

        return implode(PHP_EOL, $lines);
    }

    private function formatTransferResult(TransferResult $result): string
    {
        $statusString = strtoupper($result->status?->value);
        $fromAccount = $result->fromAccountNumber ?? '?';
        $toAccount = $result->toAccountNumber ?? '?';
        $amount = $result->amount?->toDecimalString() ?? '?';

        $line = sprintf("  %s: %s -> %s : %s", $statusString, $fromAccount, $toAccount, $amount);

        if ($result->reason !== null) {
            $line .= " ({$result->reason})";
        }

        return $line;
    }
}
