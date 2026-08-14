<?php

declare(strict_types=1);

namespace App\Services;

use App\Dtos\BalanceLoadResult;
use App\Models\MableAccount;

final class AccountBalanceLoader
{
    public function __construct(
        private readonly CsvFileReader $csvFileReader,
    ) {
    }
    public function load(string $path): BalanceLoadResult
    {
        $accounts = [];
        $errors = [];

        foreach ($this->csvFileReader->read($path) as $lineNumber => $row) {
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
}
