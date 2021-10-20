<?php
declare(strict_types=1);

namespace App\Domain\Transaction;

use Decimal\Decimal;

final class Transaction
{
    private int $bin;
    private Decimal $amount;
    private string $currency;

    public function __construct(int $bin, Decimal $amount, string $currency)
    {
        $this->bin = $bin;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function bin(): int
    {
        return $this->bin;
    }

    public function amount(): Decimal
    {
        return $this->amount;
    }

    public function currency(): string
    {
        return $this->currency;
    }
}