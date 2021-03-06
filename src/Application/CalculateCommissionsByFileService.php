<?php
declare(strict_types=1);

namespace App\Application;

use App\Application\Dto\Calculation;
use App\Application\Exception\CalculateCommissionsFailed;
use App\Domain\Commission\CommissionCalculator;
use App\Domain\Commission\CommissionCalculatorInput;
use App\Domain\CurrencyRate\CurrencyRateFetcher;
use App\Domain\Transaction\BinChecker;
use App\Domain\Transaction\BinIssuedType;
use App\Domain\Transaction\TransactionsFileReader;
use Throwable;

final class CalculateCommissionsByFileService
{
    private CommissionCalculator $calculator;
    private TransactionsFileReader $fileReader;
    private BinChecker $binChecker;
    private CurrencyRateFetcher $currencyRateFetcher;

    public function __construct(
        CommissionCalculator   $calculator,
        TransactionsFileReader $fileReader,
        BinChecker             $binChecker,
        CurrencyRateFetcher    $currencyRateFetcher
    )
    {
        $this->calculator = $calculator;
        $this->fileReader = $fileReader;
        $this->binChecker = $binChecker;
        $this->currencyRateFetcher = $currencyRateFetcher;
    }

    public function execute(CalculateCommissionsByFile $command): Calculation
    {
        $calculation = new Calculation();

        try {
            foreach ($this->fileReader->readTransactions($command->filepath()) as $transaction) {
                $countryCode = $this->binChecker->countryCode($transaction->bin());
                $binIssuedType = BinIssuedType::byCountryCode($countryCode);
                $currencyRate = $this->currencyRateFetcher->rate($transaction->currency());

                $input = new CommissionCalculatorInput();
                $input->amount = $transaction->amount();
                $input->currencyRate = $currencyRate;
                $input->currency = $transaction->currency();
                $input->binIssuedType = $binIssuedType;

                $calculation->commissions[] = $this->calculator->calculate($input);
            }
        } catch (Throwable $throwable) {
            // some logging
            throw new CalculateCommissionsFailed('Calculate commissions failed.', 0, $throwable);
        }

        return $calculation;
    }
}