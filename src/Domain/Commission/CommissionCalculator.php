<?php
declare(strict_types=1);

namespace App\Domain\Commission;

use Decimal\Decimal;

interface CommissionCalculator
{
    public function calculate(CommissionCalculatorInput $input): Decimal;
}