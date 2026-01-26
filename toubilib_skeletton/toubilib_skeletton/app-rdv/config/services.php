<?php

use toubilib\api\actions\annulerRDVAction;
use toubilib\api\actions\creerRDVAction;
use toubilib\api\actions\GetHistoriqueConsultationsAction;
use toubilib\api\actions\getRDVAction;
use toubilib\api\actions\changerStatusRDVAction;
use toubilib\api\actions\getRDVByPraticienAction;
use toubilib\core\application\ports\api\ServiceRDVInterface;
use toubilib\core\application\ports\spi\PatientRepositoryInterface;
use toubilib\core\application\ports\spi\PraticienRepositoryInterface;
use toubilib\core\application\ports\spi\RDVRepositoryInterface;
use toubilib\core\application\usecases\ServiceRDV;
use toubilib\infra\repositories\PDORdvRepository;
use toubilib\infra\repositories\HttpPraticienRepository;
use toubilib\infra\repositories\HttpPatientRepository;
use GuzzleHttp\Client;

return [
    'pdo' => function($container) {
        $settings = $container->get('settings')['db'];
        return new \PDO($settings['dsn'], $settings['user'], $settings['password']);
    },
    'pdo_rdv' => function($container) {
        $settings = $container->get('settings')['db_rdv'];
        return new \PDO($settings['dsn'], $settings['user'], $settings['password']);
    },
    'praticien.client' => function($container) {
        $settings = $container->get('settings');
        return new Client(['base_uri' => $settings['praticien_api_url']]);
    },
    'patient.client' => function($container) {
        $settings = $container->get('settings');
        return new Client(['base_uri' => $settings['patient_api_url']]);
    },
    PraticienRepositoryInterface::class => function($container) {
        return new HttpPraticienRepository($container->get('praticien.client'));
    },
    PatientRepositoryInterface::class => function($container) {
        return new HttpPatientRepository($container->get('patient.client'));
    },
    RDVRepositoryInterface::class => function($container) {
        return new PDORdvRepository($container->get('pdo_rdv'));
    },
    getRDVAction::class => function($container) {
        return new getRDVAction($container->get(RDVRepositoryInterface::class));
    },
    creerRDVAction::class => function($container) {
        return new creerRDVAction($container->get(RDVRepositoryInterface::class));
    },
    ServiceRDV::class => function($container) {
        return new ServiceRDV(
            $container->get(RDVRepositoryInterface::class),
            $container->get(PraticienRepositoryInterface::class),
            $container->get(PatientRepositoryInterface::class)
        );
    },
    getRDVByPraticienAction::class => function($container) {
        return new getRDVByPraticienAction(
            $container->get(ServiceRDV::class)
        );
    },
    annulerRDVAction::class => function($container) {
        return new annulerRDVAction($container->get(RDVRepositoryInterface::class));
    },
    ServiceRDVInterface::class => function($container) {
        return $container->get(ServiceRDV::class);
    },
    GetHistoriqueConsultationsAction::class => function ($container) {
        return new GetHistoriqueConsultationsAction($container->get(ServiceRDVInterface::class));
    },
    changerStatusRDVAction::class => function($container) {
        return new changerStatusRDVAction($container->get(RDVRepositoryInterface::class));
    },
];