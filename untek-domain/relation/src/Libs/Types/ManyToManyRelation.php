<?php

namespace Untek\Domain\Relation\Libs\Types;

use Psr\Container\ContainerInterface;
use Untek\Core\Code\Factories\PropertyAccess;
use Untek\Core\Collection\Helpers\CollectionHelper;
use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Core\Collection\Libs\Collection;
use Untek\Domain\Domain\Interfaces\FindAllInterface;
use Untek\Domain\Query\Entities\Query;
use Untek\Domain\Query\Entities\Where;
use Untek\Domain\Relation\Interfaces\RelationInterface;

class ManyToManyRelation extends BaseRelation implements RelationInterface
{

    /** Связующее поле */
    public $relationAttribute;

    /** @var string Имя связи, указываемое в методе with.
     * Если пустое, то берется из атрибута relationEntityAttribute
     */
    public $name;

    /** @var string Имя поля, в которое записывать вложенную сущность */
    public $relationEntityAttribute;

    /** @var string Имя первичного ключа связной таблицы */
    public $foreignAttribute = 'id';

    /** @var string Имя класса связного репозитория */
    public $foreignRepositoryClass;

    /** @var array Условие для присваивания связи, иногда нужно для полиморических связей */
    public $condition = [];

    /** @var callable Callback-метод для пост-обработки коллекции из связной таблицы */
    public $prepareCollection;

    /** @var Query Объект запроса для связного репозитория */
    public $query;
    protected $container;

    public $viaRepositoryClass;
    public $viaSourceAttribute;
    public $viaTargetAttribute;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function run(Enumerable $collection): void
    {
        $this->loadRelation($collection);
        $collection = $this->prepareCollection($collection);
    }

    protected function prepareCollection(Enumerable $collection)
    {
        if ($this->prepareCollection) {
            call_user_func($this->prepareCollection, $collection);
        }
    }

    protected function loadRelationByIds(array $ids): Enumerable
    {
        $foreignRepositoryInstance = $this->getRepositoryInstance();
        $query = $this->getQuery();
        $query->whereNew(new Where($this->foreignAttribute, $ids));
        return $this->loadCollection($foreignRepositoryInstance, $ids, $query);
    }

    protected function loadViaByIds(array $ids): Enumerable
    {
        $foreignRepositoryInstance = $this->getViaRepositoryInstance();
        $query = $this->getQuery();
        $query->whereNew(new Where($this->viaSourceAttribute, $ids));
        return $this->loadCollection($foreignRepositoryInstance, $ids, $query);
    }

    protected function getQuery(): Query
    {
        return $this->query ? $this->query : new Query;
    }

    protected function getRepositoryInstance(): FindAllInterface
    {
        return $this->container->get($this->foreignRepositoryClass);
    }

    protected function getViaRepositoryInstance(): FindAllInterface
    {
        return $this->container->get($this->viaRepositoryClass);
    }

    protected function loadRelation(Enumerable $collection): void
    {
        $ids = CollectionHelper::getColumn($collection, $this->relationAttribute);
        $ids = array_unique($ids);
        $viaCollection = $this->loadViaByIds($ids);
        $targetIds = CollectionHelper::getColumn($viaCollection, $this->viaTargetAttribute);
        $targetIds = array_unique($targetIds);
        $foreignCollection = $this->loadRelationByIds($targetIds);
        $foreignCollection = CollectionHelper::indexing($foreignCollection, 'id');
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $indexedCollection = CollectionHelper::indexing($collection, 'id');

        $result = [];
        foreach ($viaCollection as $viaEntity) {
            $targetRelationIndex = $propertyAccessor->getValue($viaEntity, $this->viaTargetAttribute);
            $sourceIndex = $propertyAccessor->getValue($viaEntity, $this->viaSourceAttribute);
            $sourceEntity = $indexedCollection[$sourceIndex];
            $targetRelationEntity = $foreignCollection[$targetRelationIndex];
            $result[$sourceIndex][] = $targetRelationEntity;
        }
        foreach ($collection as $entity) {
            $sourceIndex = $propertyAccessor->getValue($entity, 'id');
            if (isset($result[$sourceIndex])) {
                $value = $result[$sourceIndex];
                $value = $this->getValueFromPath($value);
                $propertyAccessor->setValue($entity, $this->relationEntityAttribute, new Collection($value));
            }
        }
    }

    protected function loadCollection(FindAllInterface $foreignRepositoryInstance, array $ids, Query $query): Enumerable
    {
        //$query->limit(count($ids));
        $collection = $foreignRepositoryInstance->findAll($query);
        return $collection;
    }
}
