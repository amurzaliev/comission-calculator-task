<?php
declare(strict_types=1);

namespace App\Bridge\Controller\Console;

use App\Application\CalculateCommissionsByFile;
use App\Application\CalculateCommissionsByFileService;

final class CommissionsController
{
    private CalculateCommissionsByFileService $service;

    public function __construct(CalculateCommissionsByFileService $service)
    {
        $this->service = $service;
    }

    public function calculateCommissionsByFile(string $filepath): string
    {
        $calculation = $this->service->execute(CalculateCommissionsByFile::create($filepath));

        $view = '';

        foreach ($calculation->commissions as $commission) {
            $view .= $commission->toFixed(2) . PHP_EOL;
        }

        return $view;
    }
}