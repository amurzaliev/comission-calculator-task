<?php
declare(strict_types=1);

namespace App\Domain\Commission;

use App\Domain\Transaction\BinIssuedType;
use Decimal\Decimal;

final class CommissionCalculatorInput
{
    public Decimal $amount;
    public Decimal $currencyRate;
    public string $currency;
    public BinIssuedType $binIssuedType;
}