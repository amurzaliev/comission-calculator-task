<?php
declare(strict_types=1);

namespace App\Bridge\Service;

use App\Infrastructure\Transaction\DefaultBinChecker;

final class DefaultBinCheckerFactory implements Factory
{
    public function create(array $container): DefaultBinChecker
    {
        $config = $container['config'];

        return new DefaultBinChecker($config['bin_checkers']['default']['url']);
    }
}