<?php

namespace Untek\Tool\Test\Facades;

use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Core\Container\Libs\Container;
use Untek\Core\Container\Helpers\ContainerHelper;
use Untek\Core\Container\Interfaces\ContainerConfiguratorInterface;
use Untek\Core\App\Interfaces\AppInterface;
use Untek\Core\App\Libs\ZnCore;
use Untek\Tool\Test\Libs\TestApp;

DeprecateHelper::hardThrow();

//class BoostrapTestFacade
//{
//
//    public static function init(): AppInterface
//    {
//        $container = ContainerHelper::getContainer() ?: new Container();
//        $znCore = new ZnCore($container);
//        $znCore->init();
//
//        /** @var ContainerConfiguratorInterface $containerConfigurator */
//        $containerConfigurator = $container->get(ContainerConfiguratorInterface::class);
//        $containerConfigurator->singleton(AppInterface::class, TestApp::class);
//
//        /** @var AppInterface $appFactory */
//        $appFactory = $container->get(AppInterface::class);
//        return $appFactory;
//    }
//}
