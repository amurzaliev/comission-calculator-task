<?php
declare(strict_types=1);

namespace App\Bridge\Service;

use App\Domain\Commission\CommissionCalculator;
use Decimal\Decimal;

final class CommissionCalculatorFactory implements Factory
{
    public function create(array $container): CommissionCalculator
    {
        $config = $container['config'];

        return new CommissionCalculator(
            $config['default_currency'],
            new Decimal($config['eu_rate']),
            new Decimal($config['non_eu_rate'])
        );
    }
}