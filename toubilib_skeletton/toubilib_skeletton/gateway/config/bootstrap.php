<?php

use DI\ContainerBuilder;
use gateway\middleware\CorsMiddleware;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/dependances.php');
$container = $builder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();

$container->set(CorsMiddleware::class, function () {
    return new CorsMiddleware([], false, true);
});
(require __DIR__ . '/routes.php')($app);

return $app;