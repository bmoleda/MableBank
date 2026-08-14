<?php

declare(strict_types=1);

use App\Models\MableAccount;

function createMableAccount(string $accountNumber, int $balance): MableAccount
{
    return new MableAccount($accountNumber, $balance);
}

describe('getBalance', function (): void {
    it('returns the balance the account was constructed with', function (): void {
        $accountNumber = '1111234522226789';
        $account = createMableAccount($accountNumber, 100);

        expect($account->accountNumber)->toBe($accountNumber)
            ->and($account->getBalance())->toBe(100);
    });
});

describe('addToBalance', function (): void {
    it('increases the balance by the given amount', function (int $initialBalance, int $amountToAdd, int $expectedBalance): void {
        $account = createMableAccount('1111234522226789', $initialBalance);

        $account->addToBalance($amountToAdd);

        expect($account->getBalance())->toBe($expectedBalance);
    })->with([
        'partial amount A' => [100, 40, 140],
        'partial amount B' => [200, 20, 220],
        'zero' => [250, 0, 250],
    ]);
});

describe('removeFromBalance', function (): void {
    it('decreases the balance by the given amount', function (int $initialBalance, int $amountToRemove, int $expectedBalance): void {
        $account = createMableAccount('1111234522226789', $initialBalance);

        $account->removeFromBalance($amountToRemove);

        expect($account->getBalance())->toBe($expectedBalance);
    })->with([
        'partial amount' => [100, 40, 60],
        'entire balance' => [500, 500, 0],
        'zero' => [250, 0, 250],
    ]);

    it('leaves the balance unchanged if the amount would make it negative', function (): void {
        $account = createMableAccount('1111234522226789', 100);

        expect($account->getBalance())->toBe(100);
    });

    it('throws exception if the amount would make it negative', function (): void {
        $account = createMableAccount('1111234522226789', 100);

        expect(fn () => $account->removeFromBalance(150))
            ->toThrow(Exception::class, 'Balance cannot be negative. Current balance: 100, attempted to remove: 150.');
    });
});
