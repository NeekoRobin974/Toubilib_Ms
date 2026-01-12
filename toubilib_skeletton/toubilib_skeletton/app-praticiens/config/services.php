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
    'pdo' => function($container) {
        $settings = $container->get('settings')['db'];
        return new \PDO($settings['dsn'], $settings['user'], $settings['password']);
    },
    PraticienRepositoryInterface::class => function($container) {
        return new PDOPraticienRepository($container->get('pdo'));
    },
    getAllPraticiensAction::class => function($container) {
        return new getAllPraticiensAction($container->get(PraticienRepositoryInterface::class));
    },
    \toubilib\api\actions\getCreneauxAction::class => function($container) {
        return new \toubilib\api\actions\getCreneauxAction($container->get(RDVRepositoryInterface::class));
    },
    getAgendaPraticienAction::class => function($container) {
        return new getAgendaPraticienAction(
            $container->get(ServiceRDV::class)
        );
    },
];