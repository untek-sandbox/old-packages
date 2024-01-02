<?php

namespace Untek\Database\Fixture\Domain\Repositories;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Core\Contract\Common\Exceptions\InvalidConfigException;
use Untek\Domain\Domain\Interfaces\GetEntityClassInterface;
use Untek\Core\Collection\Helpers\CollectionHelper;
use Untek\Domain\Entity\Helpers\EntityHelper;
use Untek\Core\FileSystem\Helpers\FilePathHelper;
use Untek\Core\FileSystem\Helpers\FindFileHelper;
use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Domain\Repository\Interfaces\RepositoryInterface;
use Untek\Database\Base\Domain\Entities\RelationEntity;
use Untek\Database\Fixture\Domain\Entities\FixtureEntity;
use Untek\Database\Fixture\Domain\Libs\DataFixture;
use Untek\Database\Fixture\Domain\Libs\FixtureInterface;
use Untek\Lib\Components\Store\StoreFile;
use Untek\Sandbox\Sandbox\Generator\Domain\Services\GeneratorService;

class FileRepository implements RepositoryInterface, GetEntityClassInterface
{

    protected $config;
    public $extension = 'php';

    public function __construct($config = [])
    {
        $this->config = $config;
    }

    public function getEntityClass(): string
    {
        return FixtureEntity::class;
    }

    public function allTables(): Enumerable
    {
        $array = [];
        if (empty($this->config['directory'])) {
            throw new InvalidConfigException('Empty directories configuration for fixtures!');
        }
        foreach ($this->config['directory'] as $dir) {
            $fixtureArray = $this->scanDir($dir);
            foreach ($fixtureArray as $i => $item) {
                if (FilePathHelper::fileExt($item['fileName']) != 'php') {
                    unset($fixtureArray[$i]);
                }
            }
            $array = ArrayHelper::merge($array, $fixtureArray);
        }
        //$collection = $this->forgeEntityCollection($array);
        //return $collection;

        $entityClass = $this->getEntityClass();
        return CollectionHelper::create($entityClass, $array);
    }

    private function getRelations(string $name): array
    {
        /** @var GeneratorService $generatorService */
        $generatorService = ClassHelper::createObject(GeneratorService::class);
        $struct = $generatorService->getStructure([$name]);
        $deps = [];
        /** @var RelationEntity $relationEntity */
        foreach ($struct[0]->getRelations() as $relationEntity) {
            $deps[] = $relationEntity->getForeignTableName();
        }
        return $deps;
    }

    public function saveData($name, Enumerable $collection)
    {
        $dataFixture = $this->loadData($name);
        $data['deps'] = $dataFixture->deps();
        $data['deps'] = array_merge($data['deps'], $this->getRelations($name));
        ArrayHelper::removeValue($data['deps'], $name);
        $data['deps'] = array_unique($data['deps']);
        $data['deps'] = array_values($data['deps']);

        if (property_exists($collection->first(), 'id')) {
            $collection = $collection->sortBy('id');
        }
        $data['collection'] = ArrayHelper::toArray($collection->toArray());
        $data['collection'] = array_values($data['collection']);
        $this->getStoreInstance($name)->save($data);
    }

    public function loadData($name): FixtureInterface
    {
        $data = $this->getStoreInstance($name)->load();
        if (empty($data)) {
            return new DataFixture([], []);
        } elseif (ArrayHelper::isAssociative($data)) {
            return new DataFixture($data['collection'], $data['deps'] ?? []);
        } elseif ($data instanceof FixtureInterface) {
            return $data;
        } elseif (ArrayHelper::isIndexed($data)) {
            return new DataFixture($data);
        }

        //dd($data);
        throw new \Exception('Bad fixture format of ' . $name . '!');
    }

    private function findOneByName(string $name): FixtureEntity
    {
        $collection = $this->allTables();
        $expr = new Comparison('name', Comparison::EQ, $name);
        $criteria = new Criteria();
        $criteria->andWhere($expr);
        $collection = $collection->matching($criteria);


//        $collection = new \Illuminate\Support\Collection($this->allTables());
//        $collection = $collection->where('name', '=', $name);


        if ($collection->count() < 1) {

            $entityClass = $this->getEntityClass();
            return EntityHelper::createEntity($entityClass, [
                'name' => $name,
                'fileName' => $this->config['directory']['default'] . '/' . $name . '.' . $this->extension,
            ]);

            //return $this->forgeEntity();
        }

        $entityClass = $this->getEntityClass();
        return EntityHelper::createEntity($entityClass, $collection->first());
    }

    private function getStoreInstance(string $name): StoreFile
    {
        $entity = $this->findOneByName($name);
        $store = new StoreFile($entity->fileName);
        return $store;
    }

    private function scanDir($dir): array
    {
        $files = FindFileHelper::scanDir($dir);
        $array = [];
        foreach ($files as $file) {
            $name = FilePathHelper::fileRemoveExt($file);
            $entity = [
                'name' => $name,
                'fileName' => $dir . '/' . $file,
            ];
            $array[] = $entity;
        }
        return $array;
    }
}
