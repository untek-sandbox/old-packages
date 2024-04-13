<?php

namespace Untek\Lib\Init\Domain\Libs;

use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Framework\Console\Symfony4\Base\BaseConsoleApp;

DeprecateHelper::hardThrow();

class InitConsoleApp extends BaseConsoleApp
{

    protected function bundles(): array
    {
        return [
            \Untek\Lib\Init\Bundle::class,
        ];
    }

    /*protected function initBundles(): void
    {
        $this->addBundles([
            \Untek\Lib\Init\Bundle::class,
        ]);
        parent::initBundles();
    }*/
}
