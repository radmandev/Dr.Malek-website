<?php
declare(strict_types=1);

define('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/app/helpers.php';
require APP_ROOT . '/app/db.php';
require APP_ROOT . '/app/icons.php';
require APP_ROOT . '/app/components.php';

mb_internal_encoding('UTF-8');
date_default_timezone_set('Europe/London');

$debug = (bool) config('debug', false);
ini_set('display_errors', $debug ? '1' : '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

header_remove('X-Powered-By');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('X-Frame-Options: SAMEORIGIN');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
