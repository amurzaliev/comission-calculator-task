<?php
declare(strict_types=1);

namespace App\Infrastructure\Bin;

use App\Infrastructure\Bin\Exception\CheckBinFailed;
use Throwable;

final class DefaultBinChecker implements BinChecker
{
    private string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function countryCode(int $bin): string
    {
        $result = file_get_contents($this->url . $bin);

        if (false === $result) {
            throw new CheckBinFailed(sprintf('There is no response from %s service.', $this->url));
        }

        try {
            $data = json_decode($result);
            return $data->country->alpha2;
        } catch (Throwable $throwable) {
            throw new CheckBinFailed(sprintf('Parsing %s response failed.', $this->url), null, $throwable);
        }
    }
}