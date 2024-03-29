<?php

namespace Untek\Bundle\Eav\Domain\Entities;

use InvalidArgumentException;
use Untek\Bundle\Eav\Domain\Libs\Rules;
use Untek\Bundle\Eav\Domain\Traits\DynamicAttribute;
use Untek\Lib\Components\DynamicEntity\Interfaces\DynamicEntityAttributesInterface;
use Untek\Domain\Entity\Interfaces\EntityIdInterface;
use Untek\Lib\Components\DynamicEntity\Interfaces\ValidateDynamicEntityInterface;

class DynamicEntity implements ValidateDynamicEntityInterface, EntityIdInterface, DynamicEntityAttributesInterface
{

    use DynamicAttribute;

    protected $id;
    protected $_validationRules = [];
    protected $_entity;

    public function __construct(EntityEntity $entityEntity)
    {
        if ($entityEntity) {
            $this->_attributes = $entityEntity->getAttributeNames();
            $this->_entity = $entityEntity;
            $rulesLib = new Rules();
            $this->_validationRules = $rulesLib->convert($entityEntity->getAttributes());
        }
        if (empty($this->_attributes)) {
            throw new InvalidArgumentException('No attributes for dynamic entity!');
        }
    }

    public function entity(): EntityEntity
    {
        return $this->_entity;
    }

    public function entityId(): int
    {
        return $this->_entity->getId();
    }

    public function validationRules(): array
    {
        return $this->_validationRules;
    }

    public function setId($value): void
    {
        $this->id = $value;
    }

    public function getId()
    {
        return $this->id;
    }
}
