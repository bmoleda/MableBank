<?php

declare(strict_types=1);

namespace App\Models;

final class Money
{
    private const string PRESENTATION_PATTERN = '/^-?\d+\.\d{2}$/';

    private function __construct(
        public readonly int $cents,
    )
    {
    }

    public function add(self $other): self
    {
        return new self($this->cents + $other->cents);
    }

    public function subtract(self $other): self
    {
        return new self($this->cents - $other->cents);
    }

    public function equals(self $other): bool
    {
        return $this->cents === $other->cents;
    }

    public function toDecimalString(): string
    {
        $sign = $this->cents < 0 ? '-' : '';
        $absoluteCents = abs($this->cents);

        return sprintf('%s%d.%02d', $sign, intdiv($absoluteCents, 100), $absoluteCents % 100);
    }

    public static function fromDecimalString(string $amount): self
    {
        if (! preg_match(self::PRESENTATION_PATTERN, $amount)) {
            throw new \InvalidArgumentException("'{$amount}' is not a valid decimal amount, expected format like '1234.56'.");
        }

        [$wholePart, $fractionalPart] = explode('.', ltrim($amount, '-'));
        $cents = ((int) $wholePart * 100) + (int) $fractionalPart;
        $isNegative = str_starts_with($amount, '-');

        return new self($isNegative ? -$cents : $cents);
    }
}
