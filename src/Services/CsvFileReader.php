<?php

declare(strict_types=1);

namespace App\Services;

final class CsvFileReader
{
    /**
     * @return \Generator<int, list<string>>
     */
    public function read(string $path): \Generator
    {
        if (! is_file($path) || ! is_readable($path)) {
            throw new \RuntimeException("Unable to open file '{$path}'.");
        }

        $handle = fopen($path, 'rb');

        if ($handle === false) {
            throw new \RuntimeException("Unable to open file '{$path}'.");
        }

        try {
            while (($row = fgetcsv($handle, escape: '')) !== false) {
                $trimmedRow = array_map(static fn (?string $value): string => trim($value ?? ''), $row);

                if ($trimmedRow === ['']) {
                    continue;
                }

                yield $trimmedRow;
            }
        } finally {
            fclose($handle);
        }
    }
}
