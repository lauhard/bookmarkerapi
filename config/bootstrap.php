<?php
require_once __DIR__ . '/../vendor/autoload.php';
define('PROJECT_ROOT', realpath(__DIR__ . '/../'));
Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

//print_r(getenv());
//erstelle den Container mit ContainerBuilder
$containerBuilder = new DI\ContainerBuilder();
//füge die  Definitionen hinzu
$containerBuilder->addDefinitions(__DIR__ . '/container.php');

//baue den Container
$container = $containerBuilder->build();
//gib die APP aus dem Container zurück
$app = $container->get(Slim\App::class);

return $app;
