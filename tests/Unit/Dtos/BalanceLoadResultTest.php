<?php

declare(strict_types=1);

use App\Dtos\BalanceLoadResult;
use App\Models\BaseAccount;

function createBaseAccount(string $accountNumber, int $balance): BaseAccount
{
    return new class($accountNumber, $balance) extends BaseAccount {};
}

it('is immutable', function (): void {
    $result = new BalanceLoadResult([], []);

    expect(fn () => $result->accounts = ['changed' => createBaseAccount('1111234522226789', 0)])
        ->toThrow(Error::class);

    expect(fn () => $result->errors = ['changed'])
        ->toThrow(Error::class);
});

it('can be assignedn a list of accounts', function (): void {
    $accountNumber = '1111234522226789';
    $account = createBaseAccount($accountNumber, 100);

    $result = new BalanceLoadResult([$accountNumber => $account], []);

    expect($result->accounts)->toBe([$accountNumber => $account]);
});

it('can be assigned a list of errors', function (): void {
    $errors = ['Row 2: bad data'];

    $result = new BalanceLoadResult([], $errors);

    expect($result->errors)->toBe($errors);
});


