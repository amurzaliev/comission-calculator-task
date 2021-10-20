<?php
declare(strict_types=1);

namespace unit\Application;

use App\Application\CalculateCommissionsByFile;
use App\Application\CalculateCommissionsByFileService;
use App\Application\Exception\CalculateCommissionsFailed;
use App\Domain\Commission\StubCommissionCalculator;
use App\Domain\Transaction\BinChecker;
use App\Domain\Transaction\Transaction;
use App\Domain\Transaction\TransactionsFileReader;
use App\Infrastructure\CurrencyRate\StubCurrencyRateChecker;
use Decimal\Decimal;
use Exception;
use PHPUnit\Framework\TestCase;

final class CalculateCommissionsByFileServiceTest extends TestCase
{
    private TransactionsFileReader $transactionsFileReader;
    private BinChecker $binChecker;
    private CalculateCommissionsByFileService $it;

    protected function setUp(): void
    {
        parent::setUp();

        $this->transactionsFileReader = $this->createMock(TransactionsFileReader::class);
        $this->binChecker = $this->createMock(BinChecker::class);

        $this->it = new CalculateCommissionsByFileService(
            new StubCommissionCalculator(new Decimal('1')),
            $this->transactionsFileReader,
            $this->binChecker,
            new StubCurrencyRateChecker(new Decimal('1'))
        );
    }

    /** @test */
    public function it_calculate_commissions_for_transactions_by_file(): void
    {
        $command = CalculateCommissionsByFile::create('path.txt');
        $transactions = [
            new Transaction(1, new Decimal('100'), 'EUR'),
            new Transaction(2, new Decimal('200'), 'USD'),
        ];

        $this->transactionsFileReader
            ->expects(self::once())
            ->method('readTransactions')
            ->with($command->filepath())
            ->willReturnCallback(function () use ($transactions) {
                foreach ($transactions as $transaction) {
                    yield $transaction;
                }
            });

        $this->binChecker
            ->expects(self::exactly(2))
            ->method('countryCode')
            ->willReturn('EUR');

        $calculation = $this->it->execute($command);
        self::assertCount(2, $calculation->commissions);
    }

    /** @test */
    public function it_throws_exception_in_case_of_internal_errors(): void
    {
        $command = CalculateCommissionsByFile::create('path.txt');
        $transactions = [
            new Transaction(1, new Decimal('100'), 'EUR'),
        ];

        $this->transactionsFileReader
            ->expects(self::once())
            ->method('readTransactions')
            ->with($command->filepath())
            ->willReturnCallback(function () use ($transactions) {
                foreach ($transactions as $transaction) {
                    yield $transaction;
                }
            });

        $this->binChecker
            ->expects(self::any())
            ->method('countryCode')
            ->willThrowException(new Exception());

        $this->expectException(CalculateCommissionsFailed::class);

        $this->it->execute($command);
    }
}