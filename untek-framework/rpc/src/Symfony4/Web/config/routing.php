<?php

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Untek\Framework\Rpc\Symfony4\Web\Controllers\DocsController;

return function (RoutingConfigurator $routes) {
    $routes
        ->add('json_rpc_docs_all', '/json-rpc')
        ->controller([DocsController::class, 'index'])
        ->methods(['GET']);
    $routes
        ->add('json_rpc_docs_view', '/json-rpc/view/{name}')
        ->controller([DocsController::class, 'view'])
        ->methods(['GET']);
    $routes
        ->add('json_rpc_docs_download', '/json-rpc/download/{name}')
        ->controller([DocsController::class, 'download'])
        ->methods(['GET']);
};
