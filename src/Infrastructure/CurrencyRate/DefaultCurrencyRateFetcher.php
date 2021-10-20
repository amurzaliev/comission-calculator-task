<?php
declare(strict_types=1);

namespace App\Infrastructure\CurrencyRate;

use App\Domain\Commission\CurrencyRateFetcher;
use App\Domain\Commission\Exception\FetchCurrencyRateFailed;
use Decimal\Decimal;
use Throwable;

final class DefaultCurrencyRateFetcher implements CurrencyRateFetcher
{
    private string $url;
    private string $token;

    public function __construct(string $url, string $token)
    {
        $this->url = $url;
        $this->token = $token;
    }

    public function rate(string $currency): Decimal
    {
        $result = file_get_contents($this->url. '?access_key='. $this->token);

        if (false === $result) {
            throw new FetchCurrencyRateFailed((sprintf('There is no response from %s service.', $this->url)));
        }

        try {
            $data = json_decode($result);
            return new Decimal((string) $data->rates->{$currency}); // data is stored in float format, which leeds to losing precision
        } catch (Throwable $throwable) {
            throw new FetchCurrencyRateFailed(sprintf('Parsing %s response failed.', $this->url), 0, $throwable);
        }
    }
}