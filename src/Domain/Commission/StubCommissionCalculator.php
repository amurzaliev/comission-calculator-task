<?php
declare(strict_types=1);

namespace App\Domain\Commission;

use Decimal\Decimal;

final class StubCommissionCalculator implements CommissionCalculator
{
    private Decimal $commission;

    public function __construct(Decimal $commission)
    {
        $this->commission = $commission;
    }

    public function calculate(CommissionCalculatorInput $input): Decimal
    {
        return $this->commission;
    }
}