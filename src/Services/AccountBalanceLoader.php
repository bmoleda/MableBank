<?php

declare(strict_types=1);

namespace App\Services;

use App\Dtos\BalanceLoadResult;
use App\Models\MableAccount;
use App\Models\Money;

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
                $balance = Money::fromDecimalString($balance);
            } catch (\InvalidArgumentException $exception) {
                $errors[] = sprintf('Row %d: %s', $lineNumber + 1, $exception->getMessage());
                continue;
            }

            $accounts[$accountNumber] = new MableAccount($accountNumber, $balance);
        }

        return new BalanceLoadResult($accounts, $errors);
    }

    /**
     * @param list<string> $row
     * @return array{0: string, 1: string}
     */
    private function parseRow(array $row): array
    {
        if (count($row) !== 2) {
            throw new \InvalidArgumentException(sprintf('Expected 2 columns (account number, balance), got %d.', count($row)));
        }

        return $row;
    }
}
