<?php
declare(strict_types=1);

namespace App\Infrastructure\CurrencyRate;

use App\Domain\CurrencyRate\CurrencyRateFetcher;
use Decimal\Decimal;

final class StubCurrencyRateChecker implements CurrencyRateFetcher
{
    private Decimal $currencyRate;

    public function __construct(Decimal $currencyRate)
    {
        $this->currencyRate = $currencyRate;
    }

    public function rate(string $currency): Decimal
    {
        return $this->currencyRate;
    }
}