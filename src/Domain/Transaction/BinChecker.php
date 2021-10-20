<?php
declare(strict_types=1);

namespace App\Domain\Transaction;

use App\Domain\Transaction\Exception\CheckBinFailed;

interface BinChecker
{
    /** @throws CheckBinFailed */
    public function countryCode(int $bin): string;
}