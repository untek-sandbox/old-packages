<?php

namespace Untek\Tool\Package\Domain\Helpers;

use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Core\Collection\Libs\Collection;
use Untek\Core\FileSystem\Helpers\FilePathHelper;
use Untek\Lib\Components\Store\StoreFile;
use Untek\Tool\Package\Domain\Entities\ConfigEntity;
use Untek\Tool\Package\Domain\Entities\GroupEntity;
use Untek\Tool\Package\Domain\Entities\PackageEntity;

class PackageHelper
{

    /**
     * @return Enumerable | PackageEntity[]
     */
    public static function findAll(): Enumerable
    {
        $packages = self::getInstalled()['packages'];
        $collection = new Collection();
        foreach ($packages as $package) {
            $packageEntity = new PackageEntity();
            $packageEntity->setId($package['name']);
            list($groupName, $packageName) = explode('/', $package['name']);
            $packageEntity->setName($packageName);

            $groupEntity = new GroupEntity();
            $groupEntity->name = $groupName;

            $packageEntity->setGroup($groupEntity);

            $confiEntity = new ConfigEntity();
            $confiEntity->setId($packageEntity->getId());
            $confiEntity->setConfig($package);
            $confiEntity->setPackage($packageEntity);

            $packageEntity->setConfig($confiEntity);
            $collection->add($packageEntity);
        }
        return $collection;
    }

    public static function getInstalled(): array
    {
        $store = new StoreFile(__DIR__ . '/../../../../../../../../vendor/composer/installed.json');
        return $installed = $store->load();
    }

    public static function getLock(): array
    {
        $store = new StoreFile(__DIR__ . '/../../../../../../../../composer.lock', 'json');
        return $installed = $store->load();
    }

    public static function getPsr4Dictonary()
    {
        $psr4 = include(__DIR__ . '/../../../../../../../../vendor/composer/autoload_psr4.php');
        return $psr4;
    }

    public static function pathByNamespace($namespace)
    {
        $nsArray = PackageHelper::findPathByNamespace($namespace);
        if ($nsArray) {
            $partName = mb_substr($namespace, mb_strlen($nsArray['namespace']));
            $partName = trim($partName, '\\');
            $fileName = $nsArray['path'] . '\\' . $partName;
        } else {
            $fileName = FilePathHelper::rootPath() . '\\' . $namespace;
        }
        $fileName = str_replace('\\', '/', $fileName);
        return $fileName;
    }

    public static function findPathByNamespace(string $namespace): ?array
    {
        $namespace = trim($namespace, '\\');
        $namespace .= '\\';
        $psr4 = self::getPsr4Dictonary();
        foreach ($psr4 as $ns => $path) {
            $path = $path[0];
            if (mb_strpos($namespace, $ns) === 0) {
                return [
                    'namespace' => $ns,
                    'path' => $path,
                ];
            }
        }
        return null;
    }
}
