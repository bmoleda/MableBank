<?php

declare(strict_types=1);

use App\Models\Money;

describe('add', function (): void {
    it('sums two amounts', function (): void {
        $sum = Money::fromDecimalString('1200.00')->add(Money::fromDecimalString('500.00'));

        expect($sum->toDecimalString())->toBe('1700.00');
    });
});

describe('subtract', function (): void {
    it('subtracts one amount from another', function (): void {
        $difference = Money::fromDecimalString('5000.00')->subtract(Money::fromDecimalString('500.00'));

        expect($difference->toDecimalString())->toBe('4500.00');
    });

    it('produces a negative amount when the subtrahend is larger', function (): void {
        $difference = Money::fromDecimalString('100.00')->subtract(Money::fromDecimalString('500.00'));

        expect($difference->toDecimalString())->toBe('-400.00');
    });
});

describe('equals', function (): void {
    it('considers two amounts equal when cents are equal', function (): void {
        expect(Money::fromDecimalString('50.00')->equals(Money::fromDecimalString('50.00')))->toBeTrue();
    });

    it('considers two amounts  not equal if cents are not equal', function (): void {
        expect(Money::fromDecimalString('50.00')->equals(Money::fromDecimalString('50.01')))->toBeFalse();
    });
});

describe('fromDecimalString', function (): void {
    it('parses a decimal amount into cents', function (string $amount, int $expectedCents): void {
        $money = Money::fromDecimalString($amount);

        expect($money->cents)->toBe($expectedCents);
    })->with([
        'positive amount' => ['5000.00', 500000],
        'positive amount with a non-zero cents component' => ['320.50', 32050],
        'negative amount' => ['-5000.00', -500000],
        'negative amount with a non-zero cents component' => ['-320.50', -32050],
    ]);

    it('rejects incorrect amounts', function (string $malformedAmount): void {
        Money::fromDecimalString($malformedAmount);
    })->with([
        'missing cents' => ['5000'],
        'single-digit cents' => ['5000.5'],
        'non-numeric' => ['not a number'],
        'empty string' => [''],
    ])->throws(InvalidArgumentException::class);
});
