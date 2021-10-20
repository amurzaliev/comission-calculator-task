<?php

return [
    'default_currency' => 'EUR',
    'eu_rate' => '0.01',
    'non_eu_rate' => '0.02',
    'bin_checkers' => [
        'default' => [
            'url' => 'https://lookup.binlist.net/',
        ]
    ],
    'currency_rate_fetchers' => [
        'default' => [
            'url' => 'http://api.exchangeratesapi.io/latest',
            'token' => getenv('EXCHANGE_RATE_TOKEN'),
        ]
    ]
];