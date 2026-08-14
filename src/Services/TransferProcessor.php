<?php

declare(strict_types=1);

namespace App\Services;

use App\Dtos\TransferResult;
use App\Enums\TransferStatus;
use App\Helpers\CsvFileReader;
use App\Models\BaseAccount;
use App\Models\Money;

final class TransferProcessor
{
    /**
     * @param class-string<BaseAccount> $accountClass
     */
    public function __construct(
        private readonly CsvFileReader $csvFileReader,
        private readonly string $accountClass,
    ) {
    }

    /**
     * @param array<string, BaseAccount> $accounts
     * @return list<TransferResult>
     */
    public function process(string $path, array $accounts): array
    {
        $results = [];

        foreach ($this->csvFileReader->read($path) as $row) {
            try {
                [$fromAccountNumber, $toAccountNumber, $amount] = $this->parseRow($row);
            } catch (\InvalidArgumentException $exception) {
                $results[] = new TransferResult(null, null, null, TransferStatus::Rejected, $exception->getMessage());
                continue;
            }

            $results[] = $this->applyTransfer($accounts, $fromAccountNumber, $toAccountNumber, $amount);
        }

        return $results;
    }

    /**
     * @param list<string> $row
     * @return array{0: string, 1: string, 2: Money}
     */
    private function parseRow(array $row): array
    {
        if (count($row) !== 3) {
            throw new \InvalidArgumentException(sprintf('Expected 3 columns (from, to, amount), got %d.', count($row)));
        }

        [$fromAccountNumber, $toAccountNumber, $amount] = $row;

        ($this->accountClass)::validateAccountNumber($fromAccountNumber);
        ($this->accountClass)::validateAccountNumber($toAccountNumber);

        return [$fromAccountNumber, $toAccountNumber, Money::fromDecimalString($amount)];
    }

    /**
     * @param array<string, BaseAccount> $accounts
     */
    private function applyTransfer(
        array $accounts, 
        string $fromAccountNumber, 
        string $toAccountNumber, 
        Money $amount,
    ): TransferResult {
        $fromAccount = $accounts[$fromAccountNumber] ?? null;
        $toAccount = $accounts[$toAccountNumber] ?? null;

        if ($fromAccount === null || $toAccount === null) {
            $missingAccountNumber = $fromAccount === null ? $fromAccountNumber : $toAccountNumber;

            return new TransferResult($fromAccountNumber, $toAccountNumber, $amount, TransferStatus::Rejected, "Unknown account '{$missingAccountNumber}'.");
        }

        try {
            $fromAccount->removeFromBalance($amount);
        } catch (\Exception $exception) {
            return new TransferResult($fromAccountNumber, $toAccountNumber, $amount, TransferStatus::Rejected, $exception->getMessage());
        }

        $toAccount->addToBalance($amount);

        return new TransferResult($fromAccountNumber, $toAccountNumber, $amount, TransferStatus::Accepted, null);
    }
}
