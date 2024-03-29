<?php

namespace Untek\Tool\Stress\Domain\Helpers;

use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Tool\Stress\Domain\Libs\Runtime;

class RuntimeHelper
{

    public static function sunOfTree(Enumerable $all)
    {
        $localRuntime = 0;
        foreach ($all as $runtimeCollection) {
            $localRuntime += self::sumOfCollection($runtimeCollection);
        }
        return $localRuntime;
    }

    public static function sumOfCollection($runtimeCollection)
    {
        /** @var Runtime[] $runtimeCollection */
        $localRuntime = 0;
        foreach ($runtimeCollection as $rt) {
            $localRuntime = $localRuntime + $rt->getResult();
        }
        return $localRuntime;
    }
}
