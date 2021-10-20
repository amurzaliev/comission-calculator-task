<?php
declare(strict_types=1);

namespace App\Bridge\Service;

use App\Infrastructure\CurrencyRate\DefaultCurrencyRateFetcher;

final class DefaultCurrencyRateFetcherFactory implements Factory
{
    public function create(array $container): DefaultCurrencyRateFetcher
    {
        $config = $container['config'];

        return new DefaultCurrencyRateFetcher(
            $config['currency_rate_fetchers']['default']['url'],
            $config['currency_rate_fetchers']['default']['token'],
        );
    }
}