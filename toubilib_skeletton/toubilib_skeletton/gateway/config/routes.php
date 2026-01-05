<?php
declare(strict_types=1);

use Slim\App;
use gateway\actions\GatewayAction;

return function (App $app): App {
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', GatewayAction::class);

    return $app;
};