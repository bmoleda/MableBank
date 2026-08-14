<?php

declare(strict_types=1);

use App\Services\CsvFileReader;
use App\Services\AccountBalanceLoader;

describe('load', function (): void {
    it('builds an account for every valid row', function (): void {
        $fixturePath = __DIR__.'/../../Fixtures/read_account_balances.csv';

        $result = (new AccountBalanceLoader(new CsvFileReader()))->load($fixturePath);

        expect($result->accounts)->toHaveCount(5)
            ->and($result->errors)->toBeEmpty();

        $expectedBalances = [
            ['1111234522226789', 500000],
            ['1111234522221234', 1000000],
            ['2222123433331212', 55000],
            ['1212343433335665', 120000],
            ['3212343433335755', 5000000],
        ];

        foreach ($expectedBalances as [$accountNumber, $expectedBalance]) {
            expect($result->accounts[$accountNumber]->getBalance())->toBe($expectedBalance);
        }
    });
});
