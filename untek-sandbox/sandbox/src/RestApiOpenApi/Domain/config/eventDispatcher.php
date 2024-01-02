<?php

use Untek\Core\EventDispatcher\Interfaces\EventDispatcherConfiguratorInterface;
use Untek\Sandbox\Sandbox\RestApiOpenApi\Domain\Subscribers\GenerateOpenApiDocsSubscriber;

return function (EventDispatcherConfiguratorInterface $configurator): void {
    if(getenv('OPEN_API_ENABLED')) {
//        dd(2);
        $configurator->addSubscriber(GenerateOpenApiDocsSubscriber::class); // Генерация документации Open Api 3 YAML
    }
};
