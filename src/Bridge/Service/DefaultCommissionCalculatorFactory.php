<?php
declare(strict_types=1);

namespace App\Bridge\Service;

use App\Domain\Commission\DefaultCommissionCalculator;
use Decimal\Decimal;

final class DefaultCommissionCalculatorFactory implements Factory
{
    public function create(array $container): DefaultCommissionCalculator
    {
        $config = $container['config'];

        return new DefaultCommissionCalculator(
            $config['default_currency'],
            new Decimal($config['eu_rate']),
            new Decimal($config['non_eu_rate'])
        );
    }
}