<?php

declare(strict_types=1);

namespace App\Services;

use App\Dtos\BalanceLoadResult;
use App\Models\MableAccount;

final class AccountBalanceLoader
{
    public function load(string $path): BalanceLoadResult
    {
        $accounts = [];
        $errors = [];

        foreach ($this->readCsvFile($path) as $lineNumber => $row) {
            try {
                [$accountNumber, $balance] = $this->parseRow($row);
            } catch (\InvalidArgumentException $exception) {
                $errors[] = sprintf('Row %d: %s', $lineNumber + 1, $exception->getMessage());
                continue;
            }

            // Convert balance to integer (cents) to avoid floating-point precision issues:
            $balance = (int) round((float) $balance * 100);

            $accounts[$accountNumber] = new MableAccount($accountNumber, $balance);
        }

        return new BalanceLoadResult($accounts, $errors);
    }

    /**
     * @param list<string> $row
     * @return array{0: string, 1: int}
     */
    private function parseRow(array $row): array
    {
        if (count($row) !== 2) {
            throw new \InvalidArgumentException(sprintf('Expected 2 columns (account number, balance), got %d.', count($row)));
        }

        return $row;
    }

    /**
     * @return \Generator<int, list<string>>
     */
    private function readCsvFile(string $path): \Generator
    {
        if (! is_file($path) || ! is_readable($path)) {
            throw new \RuntimeException("Unable to open file '{$path}'.");
        }

        $handle = fopen($path, 'rb');

        if ($handle === false) {
            throw new \RuntimeException("Unable to open file '{$path}'.");
        }

        try {
            while (($row = fgetcsv($handle, escape: '')) !== false) {
                $trimmedRow = array_map(static fn (?string $value): string => trim($value ?? ''), $row);

                if ($trimmedRow === ['']) {
                    continue;
                }

                yield $trimmedRow;
            }
        } finally {
            fclose($handle);
        }
    }
}
