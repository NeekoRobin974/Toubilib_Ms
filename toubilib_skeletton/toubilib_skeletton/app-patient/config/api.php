<?php
use toubilib\api\actions\getAllPraticiensAction;
use toubilib\api\actions\getPraticienDetailsAction;

return [
    'getAllPraticiensAction' => function($container) {
        return new getAllPraticiensAction($container->get('servicePraticien'));
    },
    'getPraticienDetailsAction' => function($container) {
        return new getPraticienDetailsAction($container->get('servicePraticien'));
    },
    'getCreneauxAction' => function($container) {
        return new \toubilib\api\actions\getCreneauxAction($container->get('servicePraticien'));
    },
];