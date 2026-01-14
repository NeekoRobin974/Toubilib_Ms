<?php

use toubilib\core\application\ports\spi\AuthRepositoryInterface;
use toubilib\core\application\usecases\ServiceUser;
use toubilib\infra\repositories\PDOAuthRepository;

return [
    'pdo_auth' => function($container) {
        $settings = $container->get('settings')['db_auth'];
        return new \PDO($settings['dsn'], $settings['user'], $settings['password']);
    },
    'jwt_manager' => function($container) {
        $settings = $container->get('settings')['jwt'];
        return new \toubilib\api\provider\JWTManager($settings['key'], $settings['alg']);
    },
    \toubilib\api\provider\AuthnProviderInterface::class => function($container) {
        return new \toubilib\api\provider\JWTAuthnProvider(
            $container->get('jwt_manager'),
            $container->get(\toubilib\core\application\ports\api\ServiceUserInterface::class)
        );
    },
    \toubilib\core\application\ports\api\ServiceUserInterface::class => function($container) {
        return new ServiceUser($container->get(AuthRepositoryInterface::class));
    },
    AuthRepositoryInterface::class => function($container) {
        return new PDOAuthRepository($container->get('pdo_auth'));
    },
    \toubilib\api\middlewares\AuthnMiddleware::class => function($container) {
        $settings = $container->get('settings')['jwt'];
        return new \toubilib\api\middlewares\AuthnMiddleware($settings['key']);
    },
];