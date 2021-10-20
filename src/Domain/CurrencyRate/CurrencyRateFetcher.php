<?php
declare(strict_types=1);

namespace App\Domain\CurrencyRate;

use Decimal\Decimal;

interface CurrencyRateFetcher
{
    public function rate(string $currency): Decimal;
}