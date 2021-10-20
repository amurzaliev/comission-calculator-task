<?php
declare(strict_types=1);

namespace App\Bridge;

use App\Application\CalculateCommissionsByFileService;
use App\Bridge\Controller\Console\CommissionsController;
use App\Bridge\Service\DefaultCommissionCalculatorFactory;
use App\Bridge\Service\DefaultBinCheckerFactory;
use App\Bridge\Service\DefaultCurrencyRateFetcherFactory;
use App\Infrastructure\Transaction\TxtTransactionsFileReader;

final class SimpleKernel
{
    private array $container;

    public function __construct()
    {
        $this->initFakeDiContainer();
    }

    public function run(): void
    {
        $this->runFakeRoutingWithFakeHttpSystem();
    }

    private function initFakeDiContainer(): void
    {
        $config = $this->simpleConfig();
        $this->container['config'] = $config;

        $commissionCalculator = (new DefaultCommissionCalculatorFactory())->create($this->container);
        $fileReader = new TxtTransactionsFileReader();
        $binChecker = (new DefaultBinCheckerFactory())->create($this->container);
        $currencyRateFetcher = (new DefaultCurrencyRateFetcherFactory())->create($this->container);

        $calculateCommissionsByFileService = new CalculateCommissionsByFileService(
            $commissionCalculator,
            $fileReader,
            $binChecker,
            $currencyRateFetcher
        );

        $this->container[CommissionsController::class] = new CommissionsController($calculateCommissionsByFileService);
    }

    private function simpleConfig(): array
    {
        return include 'config/config.php';
    }

    private function runFakeRoutingWithFakeHttpSystem(): void
    {
        /** @var CommissionsController $controller */
        $controller = $this->container[CommissionsController::class];
        $view = $controller->calculateCommissionsByFile($_SERVER['argv'][1]);
        echo $view;
    }
}