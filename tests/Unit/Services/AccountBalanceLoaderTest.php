<?php

declare(strict_types=1);

use App\Models\Money;
use App\Services\AccountBalanceLoader;
use App\Services\CsvFileReader;

describe('load', function (): void {
    it('builds an account for every valid row', function (): void {
        $fixturePath = __DIR__.'/../../Fixtures/read_account_balances.csv';

        $result = (new AccountBalanceLoader(new CsvFileReader()))->load($fixturePath);

        expect($result->accounts)->toHaveCount(5)
            ->and($result->errors)->toBeEmpty();

        $expectedBalances = [
            ['1111234522226789', '5000.00'],
            ['1111234522221234', '10000.00'],
            ['2222123433331212', '550.00'],
            ['1212343433335665', '1200.00'],
            ['3212343433335755', '50000.00'],
        ];

        foreach ($expectedBalances as [$accountNumber, $expectedBalance]) {
            expect($result->accounts[$accountNumber]->getBalance()->equals(Money::fromDecimalString($expectedBalance)))->toBeTrue();
        }
    });
});
