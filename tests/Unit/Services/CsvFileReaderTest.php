<?php

declare(strict_types=1);

use App\Services\CsvFileReader;

describe('read', function (): void {
    it('yields trimmed rows and skips blank lines', function (): void {
        $fixturePath = __DIR__.'/../../Fixtures/read_account_balances.csv';

        $rows = iterator_to_array((new CsvFileReader())->read($fixturePath), preserve_keys: false);

        expect($rows)->toBe([
            ['1111234522226789', '5000.00'],
            ['1111234522221234', '10000.00'],
            ['2222123433331212', '550.00'],
            ['1212343433335665', '1200.00'],
            ['3212343433335755', '50000.00'],
        ]);
    });

    it('throws exception if the file does not exist', function (): void {
        iterator_to_array((new CsvFileReader())->read('/nonexistent/path.csv'));
    })->throws(RuntimeException::class);
});
