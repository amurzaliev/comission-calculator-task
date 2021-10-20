<?php
declare(strict_types=1);

namespace App\Domain\Transaction;

use MyCLabs\Enum\Enum;

/**
 * @method static BinIssuedType EU_ISSUED()
 * @method static BinIssuedType NON_EU_ISSUED()
 */
final class BinIssuedType extends Enum
{
    private const EU_ISSUED = 'eu_issued';
    private const NON_EU_ISSUED = 'non_eu_issued';
    private static array $euCountryCodes = [
        'AT',
        'BE',
        'BG',
        'CY',
        'CZ',
        'DE',
        'DK',
        'EE',
        'ES',
        'FI',
        'FR',
        'GR',
        'HR',
        'HU',
        'IE',
        'IT',
        'LT',
        'LU',
        'LV',
        'MT',
        'NL',
        'PO',
        'PT',
        'RO',
        'SE',
        'SI',
        'SK',
    ];

    public static function byCountryCode(string $code): self
    {
        if (in_array($code, self::$euCountryCodes, true)) {
            return BinIssuedType::EU_ISSUED();
        }

        return BinIssuedType::NON_EU_ISSUED();
    }
}