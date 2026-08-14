<?php

declare(strict_types=1);

use App\Models\BaseAccount;
use App\Models\Money;

function createTestAccount(string $accountNumber, string $balance): BaseAccount
{
    return new class($accountNumber, Money::fromDecimalString($balance)) extends BaseAccount {};
}

describe("validateAccountNumber", function (): void {
    it("never throws exceptions", function (string $accountNumber): void {
        BaseAccount::validateAccountNumber($accountNumber);
    })->with([
        'too short' => ['12345'],
        'too long' => ['11112345222267891'],
        'non-numeric' => ['abcd234522226789'],
        'empty string' => [''],
    ])->throwsNoExceptions();
});

describe("getBalance", function (): void {
    it("returns the balance the account was constructed with", function (): void {
        $account = createTestAccount("1111234522226789", "1.00");

        expect($account->getBalance()->equals(Money::fromDecimalString("1.00")))->toBeTrue();
    });
});

describe('credit', function (): void {
    it('increases the balance by the given amount', function (string $initialBalance, string $amountToAdd, string $expectedBalance): void {
        $account = createMableAccount('1111234522226789', $initialBalance);

        $account->credit(Money::fromDecimalString($amountToAdd));

        expect($account->getBalance()->equals(Money::fromDecimalString($expectedBalance)))->toBeTrue();
    })->with([
        'partial amount A' => ['1.00', '0.40', '1.40'],
        'partial amount B' => ['2.00', '0.20', '2.20'],
        'zero' => ['2.50', '0.00', '2.50'],
    ]);
});

describe("debit", function (): void {
    it('decreases the balance by the given amount', function (string $initialBalance, string $amountToRemove, string $expectedBalance): void {
        $account = createMableAccount('1111234522226789', $initialBalance);

        $account->debit(Money::fromDecimalString($amountToRemove));

        expect($account->getBalance()->equals(Money::fromDecimalString($expectedBalance)))->toBeTrue();
    })->with([
        'partial amount' => ['1.00', '0.40', '0.60'],
        'entire balance' => ['5.00', '5.00', '0.00'],
        'zero' => ['2.50', '0.00', '2.50'],
    ]);

    it("allows the balance to become negative", function (): void {
        $account = createTestAccount("1111234522226789", "1.00");

        $account->debit(Money::fromDecimalString("1.50"));

        expect($account->getBalance()->equals(Money::fromDecimalString("-0.50")))->toBeTrue();
    });
});


