<?php

declare(strict_types=1);

use App\Models\MableAccount;
use App\Models\Money;

function createMableAccount(string $accountNumber, string $balance): MableAccount
{
    return new MableAccount($accountNumber, Money::fromDecimalString($balance));
}

describe('getBalance', function (): void {
    it('returns the balance the account was constructed with', function (): void {
        $accountNumber = '1111234522226789';
        $account = createMableAccount($accountNumber, '1.00');

        expect($account->accountNumber)->toBe($accountNumber)
            ->and($account->getBalance()->equals(Money::fromDecimalString('1.00')))->toBeTrue();
    });
});

describe('addToBalance', function (): void {
    it('increases the balance by the given amount', function (string $initialBalance, string $amountToAdd, string $expectedBalance): void {
        $account = createMableAccount('1111234522226789', $initialBalance);

        $account->addToBalance(Money::fromDecimalString($amountToAdd));

        expect($account->getBalance()->equals(Money::fromDecimalString($expectedBalance)))->toBeTrue();
    })->with([
        'partial amount A' => ['1.00', '0.40', '1.40'],
        'partial amount B' => ['2.00', '0.20', '2.20'],
        'zero' => ['2.50', '0.00', '2.50'],
    ]);
});

describe('removeFromBalance', function (): void {
    it('decreases the balance by the given amount', function (string $initialBalance, string $amountToRemove, string $expectedBalance): void {
        $account = createMableAccount('1111234522226789', $initialBalance);

        $account->removeFromBalance(Money::fromDecimalString($amountToRemove));

        expect($account->getBalance()->equals(Money::fromDecimalString($expectedBalance)))->toBeTrue();
    })->with([
        'partial amount' => ['1.00', '0.40', '0.60'],
        'entire balance' => ['5.00', '5.00', '0.00'],
        'zero' => ['2.50', '0.00', '2.50'],
    ]);

    it('leaves the balance unchanged if the amount would make it negative', function (): void {
        $account = createMableAccount('1111234522226789', '1.00');

        expect($account->getBalance()->equals(Money::fromDecimalString('1.00')))->toBeTrue();
    });

    it('throws exception if the amount would make it negative', function (): void {
        $account = createMableAccount('1111234522226789', '1.00');

        expect(fn () => $account->removeFromBalance(Money::fromDecimalString('1.50')))
            ->toThrow(Exception::class, 'Balance cannot be negative. Current balance: 1.00, attempted to remove: 1.50.');
    });
});
