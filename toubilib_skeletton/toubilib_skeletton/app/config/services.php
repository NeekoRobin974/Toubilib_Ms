<?php

use toubilib\api\actions\annulerRDVAction;
use toubilib\api\actions\creerRDVAction;
use toubilib\api\actions\getAgendaPraticienAction;
use toubilib\api\actions\getAllPraticiensAction;
use toubilib\api\actions\getPatientDetailsAction;
use toubilib\api\actions\getPraticienDetailsAction;
use toubilib\api\actions\getRDVAction;
use toubilib\api\provider\AuthnProviderInterface;
use toubilib\core\application\ports\api\AuthnServiceInterface;
use toubilib\core\application\ports\spi\PatientRepositoryInterface;
use toubilib\core\application\ports\spi\PraticienRepositoryInterface;
use toubilib\core\application\ports\spi\RDVRepositoryInterface;
use toubilib\core\application\ports\spi\UserRepositoryInterface;
use toubilib\core\application\usecases\AuthnService;
use toubilib\core\application\usecases\ServiceRDV;
use toubilib\infra\repositories\PDOPraticienRepository;
use toubilib\infra\repositories\PDORdvRepository;
use toubilib\infra\repositories\PDOUserRepository;

return [
    'pdo' => function($container) {
        $settings = $container->get('settings')['db'];
        return new \PDO($settings['dsn'], $settings['user'], $settings['password']);
    },
    'pdo_rdv' => function($container) {
        $settings = $container->get('settings')['db_rdv'];
        return new \PDO($settings['dsn'], $settings['user'], $settings['password']);
    },
    'pdo_patient' => function($container) {
        $settings = $container->get('settings')['db_patient'];
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
    RDVRepositoryInterface::class => function($container) {
        return new PDORdvRepository($container->get('pdo_rdv'));
    },
    PatientRepositoryInterface::class =>function($container) {
        return new \toubilib\infra\repositories\PDOPatientRepository($container->get('pdo_patient'));
    },
    getPraticienDetailsAction::class => function($container) {
        return new getPraticienDetailsAction($container->get(PraticienRepositoryInterface::class));
    },
    getRDVAction::class => function($container) {
        return new getRDVAction($container->get(RDVRepositoryInterface::class));
    },
    creerRDVAction::class => function($container) {
        return new creerRDVAction($container->get(RDVRepositoryInterface::class));
    },
    getAgendaPraticienAction::class => function($container) {
        return new getAgendaPraticienAction(
            $container->get(ServiceRDV::class)
        );
    },
    ServiceRDV::class => function($container) {
        return new ServiceRDV(
            $container->get(RDVRepositoryInterface::class),
            $container->get(PraticienRepositoryInterface::class),
            $container->get(PatientRepositoryInterface::class)
        );
    },
    annulerRDVAction::class => function($container) {
        return new annulerRDVAction($container->get(RDVRepositoryInterface::class));
    },
    getPatientDetailsAction::class => function($container) {
        return new getPatientDetailsAction($container->get(PatientRepositoryInterface::class));
    },
    AuthnProviderInterface::class => function($container) {
        return new \toubilib\api\provider\AuthnProvider(
            $container->get(\toubilib\core\application\ports\api\AuthnServiceInterface::class)
        );
    },
    UserRepositoryInterface::class => function($container) {
        return new PDOUserRepository($container->get('pdo'));
    },
    AuthnServiceInterface::class => function($container) {
        return new AuthnService($container->get(UserRepositoryInterface::class));
    },
];