<?php
declare(strict_types=1);

namespace App\Bridge\Service;

interface Factory
{
    public function create(array $container): mixed;
}