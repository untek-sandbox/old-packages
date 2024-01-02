<?php

namespace Untek\User\Person\Rpc\Serializers;

use Untek\Domain\EntityManager\Interfaces\EntityManagerInterface;
use Untek\Framework\Rpc\Rpc\Serializers\DefaultSerializer;
use Untek\User\Person\Domain\Entities\InheritanceEntity;

class MyChildSerializer extends DefaultSerializer
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    protected function encodeEntity(object $entity)
    {
        $this->em->loadEntityRelations($entity, ['child_person']);
        /** @var InheritanceEntity $entity */
        if ($entity->getChildPerson() === null) {
            return null;
        }
        return parent::encodeEntity($entity->getChildPerson());
    }
}
