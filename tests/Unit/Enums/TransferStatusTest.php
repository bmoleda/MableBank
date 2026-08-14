<?php

declare(strict_types=1);

use App\Enums\TransferStatus;

describe("isAccepted", function (): void {
    it("returns true for Accepted", function (): void {
        expect(TransferStatus::Accepted->isAccepted())->toBeTrue();
    });

    it("returns false for Rejected", function (): void {
        expect(TransferStatus::Rejected->isAccepted())->toBeFalse();
    });
});
