<?php
declare(strict_types=1);

namespace App\Domain\Transaction;

interface TransactionsFileReader
{
    /** @return Transaction[] */
    public function readTransactions(string $filepath): iterable;
}