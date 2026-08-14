<?php

declare(strict_types=1);

use App\Enums\TransferStatus;
use App\Models\MableAccount;
use App\Models\Money;
use App\Services\CsvFileReader;
use App\Services\TransferProcessor;

describe("process", function (): void {
    it("applies a transfer between two known accounts that have sufficient funds", function (): void {
        $fixturePath = __DIR__."/../../Fixtures/single_transfer.csv";
        $fromAccountNumber = "1111234522226789";
        $toAccountNumber = "1212343433335665";
        $accounts = [
            $fromAccountNumber => new MableAccount($fromAccountNumber, Money::fromDecimalString("5000.00")),
            $toAccountNumber => new MableAccount($toAccountNumber, Money::fromDecimalString("1200.00")),
        ];

        $results = new TransferProcessor(new CsvFileReader(), MableAccount::class)->process($fixturePath, $accounts);

        expect($results)->toHaveCount(1)
            ->and($results[0]->status)->toBe(TransferStatus::Accepted)
            ->and($accounts[$fromAccountNumber]->getBalance()->toDecimalString())->toBe("4500.00")
            ->and($accounts[$toAccountNumber]->getBalance()->toDecimalString())->toBe("1700.00");
    });

    it("rejects a transfer trying to overdraw the account, and doesn't change the balances", function (): void {
        $fixturePath = __DIR__."/../../Fixtures/single_transfer.csv";
        $fromAccountNumber = "1111234522226789";
        $toAccountNumber = "1212343433335665";
        $accounts = [
            $fromAccountNumber => new MableAccount($fromAccountNumber, Money::fromDecimalString("100.00")),
            $toAccountNumber => new MableAccount($toAccountNumber, Money::fromDecimalString("1200.00")),
        ];

        $results = new TransferProcessor(new CsvFileReader(), MableAccount::class)->process($fixturePath, $accounts);

        expect($results)->toHaveCount(1)
            ->and($results[0]->status)->toBe(TransferStatus::Rejected)
            ->and($results[0]->reason)->toContain("Balance cannot be negative")
            ->and($accounts[$fromAccountNumber]->getBalance()->toDecimalString())->toBe("100.00")
            ->and($accounts[$toAccountNumber]->getBalance()->toDecimalString())->toBe("1200.00");
    });

    it("rejects a transfer referencing an unknown account", function (): void {
        $fixturePath = __DIR__."/../../Fixtures/single_transfer.csv";

        $results = new TransferProcessor(new CsvFileReader(), MableAccount::class)->process($fixturePath, []);

        expect($results)->toHaveCount(1)
            ->and($results[0]->status)->toBe(TransferStatus::Rejected)
            ->and($results[0]->reason)->toContain("Unknown account");
    });
});
