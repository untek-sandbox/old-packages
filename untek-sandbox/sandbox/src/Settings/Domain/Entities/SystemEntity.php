<?php

namespace Untek\Sandbox\Sandbox\Settings\Domain\Entities;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Untek\Domain\Entity\Interfaces\EntityIdInterface;
use Untek\Domain\Entity\Interfaces\UniqueInterface;
use Untek\Domain\Validator\Interfaces\ValidationByMetadataInterface;

class SystemEntity implements ValidationByMetadataInterface, UniqueInterface, EntityIdInterface
{

    private $id = null;
    private $name = null;
    private $key = null;
    private $value = null;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new Assert\NotBlank);
        $metadata->addPropertyConstraint('key', new Assert\NotBlank);
//        $metadata->addPropertyConstraint('value', new Assert\NotBlank(['allowNull' => true]));
    }

    public function unique(): array
    {
        return [
            ['name', 'key'],
        ];
    }

    public function setId($value): void
    {
        $this->id = $value;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key): void
    {
        $this->key = $key;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value): void
    {
        $this->value = $value;
    }
}
