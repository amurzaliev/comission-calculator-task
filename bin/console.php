<?php

use App\Bridge\SimpleKernel;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';
(new Dotenv())->usePutenv()->bootEnv(dirname(__DIR__) . '/.env');

if ('dev' === strtolower(getenv('APP_ENV'))) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$kernel = new SimpleKernel();
$kernel->run();