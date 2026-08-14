<?php

declare(strict_types=1);

use App\Dtos\TransferResult;
use App\Enums\TransferStatus;
use App\Models\Money;

it("is immutable", function (): void {
    $result = new TransferResult(null, null, null, TransferStatus::Rejected, 'reason');

    expect(fn () => $result->fromAccountNumber = 'changed')->toThrow(Error::class);
    expect(fn () => $result->toAccountNumber = 'changed')->toThrow(Error::class);
    expect(fn () => $result->amount = Money::fromDecimalString('1.00'))->toThrow(Error::class);
    expect(fn () => $result->status = TransferStatus::Accepted)->toThrow(Error::class);
    expect(fn () => $result->reason = 'changed')->toThrow(Error::class);
});


it("can be assigned transfer's details", function (): void {
    $fromAccountNumber = '1111234522226789';
    $toAccountNumber = '1212343433335665';
    $amount = Money::fromDecimalString('500.00');

    $result = new TransferResult($fromAccountNumber, $toAccountNumber, $amount, TransferStatus::Accepted, null);

    expect($result->fromAccountNumber)->toBe($fromAccountNumber)
        ->and($result->toAccountNumber)->toBe($toAccountNumber)
        ->and($result->amount)->toBe($amount)
        ->and($result->status)->toBe(TransferStatus::Accepted)
        ->and($result->reason)->toBeNull();
});

it("can be assigned null account numbers and amount for an unparseable row", function (): void {
    $reason = 'Expected 3 columns (from, to, amount), got 2.';

    $result = new TransferResult(null, null, null, TransferStatus::Rejected, $reason);

    expect($result->fromAccountNumber)->toBeNull()
        ->and($result->toAccountNumber)->toBeNull()
        ->and($result->amount)->toBeNull()
        ->and($result->status)->toBe(TransferStatus::Rejected)
        ->and($result->reason)->toBe($reason);
});
