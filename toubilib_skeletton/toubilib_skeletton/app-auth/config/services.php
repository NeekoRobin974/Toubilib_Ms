<?php

use toubilib\api\actions\annulerRDVAction;
use toubilib\api\actions\creerRDVAction;
use toubilib\api\actions\getAgendaPraticienAction;
use toubilib\api\actions\getAllPraticiensAction;
use toubilib\api\actions\GetHistoriqueConsultationsAction;
use toubilib\api\actions\getPatientDetailsAction;
use toubilib\api\actions\getPraticienDetailsAction;
use toubilib\api\actions\getRDVAction;
use toubilib\api\actions\changerStatusRDVAction;
use toubilib\api\actions\getRDVByPraticienAction;
use toubilib\api\provider\AuthnProviderInterface;
use toubilib\core\application\ports\api\ServiceRDVInterface;
use toubilib\core\application\ports\api\ServiceUserInterface;
use toubilib\core\application\ports\spi\AuthRepositoryInterface;
use toubilib\core\application\ports\spi\PatientRepositoryInterface;
use toubilib\core\application\ports\spi\PraticienRepositoryInterface;
use toubilib\core\application\ports\spi\RDVRepositoryInterface;
use toubilib\core\application\usecases\ServiceRDV;
use toubilib\core\application\usecases\ServiceUser;
use toubilib\infra\repositories\PDOAuthRepository;
use toubilib\infra\repositories\PDOPraticienRepository;
use toubilib\infra\repositories\PDORdvRepository;

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