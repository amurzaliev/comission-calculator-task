<?php
declare(strict_types=1);

namespace App\Application;

final class CalculateCommissionsByFile
{
    private string $filepath;

    private function __construct(string $filepath)
    {
        $this->filepath = $filepath;
    }

    public static function create(string $filepath): self
    {
        assert(pathinfo($filepath, PATHINFO_EXTENSION) === 'txt', 'File has invalid extension.');
        assert(is_file($filepath), 'File does not exist.');

        return new self($filepath);
    }

    public function filepath(): string
    {
        return $this->filepath;
    }
}