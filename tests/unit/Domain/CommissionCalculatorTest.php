<?php
declare(strict_types=1);

namespace unit\Domain;

use App\Domain\Commission\CommissionCalculator;
use App\Domain\Commission\CommissionCalculatorInput;
use App\Domain\Transaction\BinIssuedType;
use Decimal\Decimal;
use DomainException;
use PHPUnit\Framework\TestCase;

final class CommissionCalculatorTest extends TestCase
{
    private CommissionCalculator $it;

    protected function setUp(): void
    {
        parent::setUp();

        $this->it = new CommissionCalculator(
            'EUR',
            new Decimal('0.01'),
            new Decimal('0.02'),
        );
    }

    /**
     * @test
     * @dataProvider validInputDataProvider
     */
    public function it_calculates_commissions_right(CommissionCalculatorInput $input, Decimal $expected): void
    {
        $actual = $this->it->calculate($input);
        self::assertEquals($expected->toFixed(4), $actual->toFixed(4));
    }

    public function validInputDataProvider(): array
    {
        return [
            'EUR, amount - 100, exchange rate - 999, eu-issued' => [
                'input' => $this->createInput('200', '999', 'EUR', BinIssuedType::EU_ISSUED()),
                'expected' => new Decimal('2'),
            ],
            'EUR, amount - 100, exchange rate - 999, non-eu-issued' => [
                'input' => $this->createInput('200', '999', 'EUR', BinIssuedType::NON_EU_ISSUED()),
                'expected' => new Decimal('4'),
            ],
            'EUR, amount - 0, exchange rate - 999, eu-issued' => [
                'input' => $this->createInput('0', '999', 'EUR', BinIssuedType::EU_ISSUED()),
                'expected' => new Decimal('0'),
            ],
            'USD, amount - 100, exchange rate - 2, eu-issued' => [
                'input' => $this->createInput('100', '2', 'USD', BinIssuedType::EU_ISSUED()),
                'expected' => new Decimal('0.5'),
            ],
            'USD, amount - 100, exchange rate - 2, non-eu-issued' => [
                'input' => $this->createInput('100', '2', 'USD', BinIssuedType::NON_EU_ISSUED()),
                'expected' => new Decimal('1'),
            ],
        ];
    }

    /**
     * @test
     * @dataProvider invalidInputDataProvider
     */
    public function it_throws_exceptions_if_currency_rate_is_not_valid(CommissionCalculatorInput $input): void
    {
        $this->expectException(DomainException::class);
        $this->it->calculate($input);
    }

    public function invalidInputDataProvider(): array
    {
        return [
            'EUR, amount - 100, exchange rate - 0, eu-issued' => [
                'input' => $this->createInput('100', '0', 'EUR', BinIssuedType::EU_ISSUED()),
            ],
            'EUR, amount - 100, exchange rate - -100, eu-issued' => [
                'input' => $this->createInput('100', '-100', 'EUR', BinIssuedType::NON_EU_ISSUED()),
            ],
        ];
    }

    private function createInput(string $amount, string $currencyRate, string $currency, BinIssuedType $binIssuedType): CommissionCalculatorInput
    {
        $input = new CommissionCalculatorInput();
        $input->amount = new Decimal($amount);
        $input->currencyRate = new Decimal($currencyRate);
        $input->currency = $currency;
        $input->binIssuedType = $binIssuedType;

        return $input;
    }
}