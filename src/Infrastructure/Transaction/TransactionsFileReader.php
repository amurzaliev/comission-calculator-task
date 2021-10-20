<?php
declare(strict_types=1);

namespace App\Infrastructure\Transaction;

use App\Domain\Transaction\Transaction;

interface TransactionsFileReader
{
    /** @return Transaction[] */
    public function readTransactions(string $filepath): iterable;
}