<?php
declare(strict_types=1);

namespace App\Infrastructure\Transaction;

use App\Domain\Transaction\Exception\TransactionsFileReadFailed;
use App\Domain\Transaction\Transaction;
use App\Domain\Transaction\TransactionsFileReader;
use Decimal\Decimal;
use Throwable;

final class TxtTransactionsFileReader implements TransactionsFileReader
{
    /** @return Transaction[] */
    public function readTransactions(string $filepath): iterable
    {
        try {
            $rows = file_get_contents($filepath);

            foreach (explode("\n", $rows) as $row) {
                $jsonData = json_decode($row);

                yield new Transaction(
                    (int) $jsonData->bin,
                    new Decimal($jsonData->amount),
                    $jsonData->currency
                );
            }
        } catch (Throwable $throwable) {
            throw new TransactionsFileReadFailed(sprintf('Error has occurred while reading %s file.', $filepath), 0, $throwable);
        }
    }
}