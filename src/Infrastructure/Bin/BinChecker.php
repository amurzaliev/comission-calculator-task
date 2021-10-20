<?php
declare(strict_types=1);

namespace App\Infrastructure\Bin;

use App\Infrastructure\Bin\Exception\CheckBinFailed;

interface BinChecker
{
    /** @throws CheckBinFailed */
    public function countryCode(int $bin): string;
}