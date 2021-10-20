<?php
declare(strict_types=1);

namespace App\Domain\Commission;

use App\Domain\Transaction\BinIssuedType;
use Decimal\Decimal;
use DomainException;

final class CommissionCalculator
{
    private string $defaultCurrency;
    private Decimal $euRate;
    private Decimal $nonEuRate;

    public function __construct(string $defaultCurrency, Decimal $euRate, Decimal $nonEuRate)
    {
        $this->defaultCurrency = $defaultCurrency;
        $this->euRate = $euRate;
        $this->nonEuRate = $nonEuRate;
    }

    public function calculate(CommissionCalculatorInput $input): Decimal
    {
        $amount = $input->amount;

        if ($input->currencyRate->isZero() || $input->currencyRate->isNegative()) {
            throw new DomainException('Currency rate could not be zero or negative.');
        }

        if ($input->currency !== $this->defaultCurrency && $input->currencyRate > 0) {
            $amount = $amount->div($input->currencyRate);
        }

        $rate = $input->binIssuedType->equals(BinIssuedType::EU_ISSUED()) ? $this->euRate : $this->nonEuRate;

        return $amount->mul($rate);
    }
}