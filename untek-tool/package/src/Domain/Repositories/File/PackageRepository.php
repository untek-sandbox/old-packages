<?php

namespace Untek\Tool\Package\Domain\Repositories\File;

use Untek\Core\FileSystem\Helpers\FindFileHelper;
use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Core\Collection\Libs\Collection;
use Untek\Domain\Entity\Interfaces\EntityIdInterface;
use Untek\Domain\Query\Entities\Query;
use Untek\Lib\Components\Store\StoreFile;
use Untek\Tool\Package\Domain\Entities\ConfigEntity;
use Untek\Tool\Package\Domain\Entities\GroupEntity;
use Untek\Tool\Package\Domain\Entities\PackageEntity;
use Untek\Tool\Package\Domain\Interfaces\Repositories\PackageRepositoryInterface;

class PackageRepository implements PackageRepositoryInterface
{

    const VENDOR_DIR = __DIR__ . '/../../../../../../../../../vendor/';

    protected $tableName = '';
    private $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function getEntityClass(): string
    {
        return PackageEntity::class;
    }

    public function allWithThirdParty(Query $query = null)
    {
        $vendorDir = realpath(self::VENDOR_DIR);

        $groups = FindFileHelper::scanDir($vendorDir);
        /** @var GroupEntity[] $groupCollection */
        $groupCollection = new Collection();

        foreach ($groups as $group) {
            if (is_dir($vendorDir . '/' . $group)) {
                $groupEntity = new GroupEntity;
                $groupEntity->name = $group;
                $groupCollection->add($groupEntity);
            }
        }

        $collection = new Collection();
        foreach ($groupCollection as $groupEntity) {
            $dir = $vendorDir . DIRECTORY_SEPARATOR . $groupEntity->name;

            $names = FindFileHelper::scanDir($dir);

            foreach ($names as $name) {
                $packageEntity = new PackageEntity;
                $packageEntity->setName($name);
                $packageEntity->setGroup($groupEntity);
                $this->assignConfig($packageEntity);
                if ($this->isComposerPackage($packageEntity)) {
                    $collection->add($packageEntity);
                }
            }
        }

        return $collection;
    }

    public function findAll(Query $query = null): Enumerable
    {
        $vendorDir = realpath(self::VENDOR_DIR);
        /** @var GroupEntity[] $groupCollection */
        $groupCollection = $this->groupRepository->findAll();
        $collection = new Collection();
        foreach ($groupCollection as $groupEntity) {
            $dir = $vendorDir . DIRECTORY_SEPARATOR . $groupEntity->name;
            $names = FindFileHelper::scanDir($dir);
            foreach ($names as $name) {
                $packageEntity = new PackageEntity();
                $packageEntity->setName($name);
                $packageEntity->setGroup($groupEntity);
                $this->assignConfig($packageEntity);
                if ($this->isComposerPackage($packageEntity)) {
                    $collection->add($packageEntity);
                }
            }
        }
        return $collection;
    }

    private function assignConfig(PackageEntity $packageEntity) {
        if ($this->isComposerPackage($packageEntity)) {
            $configEntity = $this->loadConfig($packageEntity);
            $packageEntity->setConfig($configEntity);
        }
    }

    private function loadConfig(PackageEntity $packageEntity): ConfigEntity {
        $composerConfigFile = $packageEntity->getDirectory() . '/composer.json';
        $composerConfigStore = new StoreFile($composerConfigFile);
        $composerConfig = $composerConfigStore->load();
        $confiEntity = new ConfigEntity;
        $confiEntity->setId($packageEntity->getId());
        $confiEntity->setConfig($composerConfig);
        $confiEntity->setPackage($packageEntity);
        return $confiEntity;
    }

    private function isComposerPackage(PackageEntity $packageEntity): bool
    {
        $composerConfigFile = $packageEntity->getDirectory() . '/composer.json';
        $isPackage = is_dir($packageEntity->getDirectory()) && is_file($composerConfigFile);
        return $isPackage;
    }

    public function count(Query $query = null): int
    {
        return count($this->findAll($query));
    }

    public function findOneById($id, Query $query = null): EntityIdInterface
    {
        // TODO: Implement findOneById() method.
    }

    /*public function findOneById($id, Query $query = null): EntityIdInterface
    {
        // TODO: Implement findOneById() method.
    }*/

    /*public function _relations()
    {
        // TODO: Implement relations() method.
    }*/
}
