<?php
$env = $_ENV['APP_ENV'] ??= $_SERVER['APP_ENV'] ?? 'dev'; //not used at the moment

error_reporting(0);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
date_default_timezone_set('Europe/Berlin');

//schreibe settings und gib sie als Array zurück
$settings = [
    'debug' => $env !== 'prod',
    'log_errors' => true,
    'error' => [
        'display_error_details' => $env !== 'prod',
    ],

    // Standard DB Settings (können überschrieben werden)
    'db' => [
        'driver' => 'pgsql',
        'host' => getenv('POSTGRES_HOSTNAME') ?: '192.168.178.33',
        'port' => getenv('POSTGRES_PORT') ?: 5432,
        'database' => getenv('POSTGRES_DB_NAME') ?: 'dev02',
        'user' => getenv('POSTGRES_USER') ?: 'andreas',
        //password kommt aus docker secret
        //'password' => trim(file_get_contents(getenv('POSTGRES_DB_PASSWORD_FILE')) ?: file_get_contents('/run/secrets/postgres_db_password')),
        'password' => 'demo',
        'encoding' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'options' => [
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => true,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ],
    ],
    'logger' => [
        'path' => __DIR__ . '/../logs/',
        'level' => Psr\Log\LogLevel::DEBUG,
    ],
    'jwt' => [
        'secret' => getenv('JWT_SECRET') ?: 'dev',
        'algorithm' => 'HS256',
        'issuer' => getenv('JWT_ISSUER') ?: 'http://localhost:9000',
        'audience' => getenv('JWT_AUDIENCE') ?: 'http://localhost:9000',
        'expiration_time' => getenv('JWT_EXPIRATION') ?: 3600, // 1 hour
    ],
];

// 1. Lokale env-Datei einbinden, z. B. `env.dev.php`, `env.prod.php`
$envOverrideFile = __DIR__ . '/env.' . $env . '.php';
if (file_exists($envOverrideFile)) {
    $overrideFn = require $envOverrideFile;
    if (is_callable($overrideFn)) {
        $settings = $overrideFn($settings);
    }
}
return $settings;