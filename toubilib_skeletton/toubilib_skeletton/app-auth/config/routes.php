<?php
declare(strict_types=1);

return function( \Slim\App $app):\Slim\App {
    $app->post('/auth/signin', function ($request, $response, $args) use ($app) {
        $container = $app->getContainer();
        $authProvider = $container->get(\toubilib\api\provider\AuthnProviderInterface::class);
        $action = new \toubilib\api\actions\SigninAction($authProvider);
        return $action($request, $response, $args);
    });
    $app->post('/auth/register', function ($request, $response, $args) use ($app) {
        $container = $app->getContainer();
        $authProvider = $container->get(\toubilib\api\provider\AuthnProviderInterface::class);
        $action = new \toubilib\api\actions\SignUpAction($authProvider);
        return $action($request, $response, $args);
    });
    $app->post('/auth/refresh', function ($request, $response, $args) use ($app) {
        $container = $app->getContainer();
        $authProvider = $container->get(\toubilib\api\provider\AuthnProviderInterface::class);
        $action = new \toubilib\api\actions\RefreshAction($authProvider);
        return $action($request, $response, $args);
    });

    return $app;
};