<?php

namespace Untek\Domain\Entity\Helpers;

use ReflectionClass;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Core\Instance\Helpers\PropertyHelper;
use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Core\Text\Helpers\Inflector;
//use Untek\Core\Code\Factories\PropertyAccess;
use Untek\Lib\Components\DynamicEntity\Interfaces\DynamicEntityAttributesInterface;

class EntityHelper
{

    /*public static function getValue(object $enitity, string $attribute)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        return $propertyAccessor->getValue($enitity, $attribute);
    }*/

    /**
     * @param string $entityClass
     * @param array $attributes
     * @return object
     * @throws \Untek\Core\Contract\Common\Exceptions\InvalidConfigException
     * @throws \Untek\Core\Instance\Exceptions\NotInstanceOfException
     * @deprecated
     * @see \Untek\Core\Instance\Helpers\PropertyHelper::createObject()
     */
    public static function createEntity(string $entityClass, $attributes = [])
    {
        return \Untek\Core\Instance\Helpers\PropertyHelper::createObject($entityClass, $attributes);
        /*$entityInstance = ClassHelper::createObject($entityClass);
        if ($attributes) {
            PropertyHelper::setAttributes($entityInstance, $attributes);
        }
        return $entityInstance;*/
    }

    public static function isEntity($data)
    {
        return is_object($data) && !($data instanceof Enumerable);
    }

    public static function toArrayForTablize(object $entity, array $columnList = []): array
    {
        $array = self::toArray($entity);
        $arraySnakeCase = [];
        foreach ($array as $name => $value) {
            $tableizeName = Inflector::underscore($name);
            $arraySnakeCase[$tableizeName] = $value;
        }
        if ($columnList) {
            $arraySnakeCase = ArrayHelper::extractByKeys($arraySnakeCase, $columnList);
        }
        return $arraySnakeCase;
    }

    /**
     * @param $entity
     * @param bool $recursive
     * @return array
     * @deprecated
     * @see \Untek\Core\Instance\Helpers\PropertyHelper::toArray()
     */
    public static function toArray($entity, bool $recursive = false): array
    {
        $array = [];
        if (is_array($entity)) {
            $array = $entity;
        } elseif ($entity instanceof Enumerable) {
            $array = $entity->toArray();
        } elseif (is_object($entity)) {
            $attributes = self::getAttributeNames($entity);
            if ($attributes) {
//                $propertyAccessor = PropertyAccess::createPropertyAccessor();
                foreach ($attributes as $attribute) {
                    $array[$attribute] = PropertyHelper::getValue($entity, $attribute);
//                    $array[$attribute] = $propertyAccessor->getValue($entity, $attribute);
                }
            } else {
                $array = (array)$entity;
            }
        }
        if ($recursive) {
            foreach ($array as $key => $item) {
                if (is_object($item) || is_array($item)) {
                    $array[$key] = self::toArray($item, $recursive/*, $keyFormat*/);
                }
            }
        }
        foreach ($array as $key => $value) {
            $isPrivate = mb_strpos($key, "\x00*\x00") !== false;
            if ($isPrivate) {
                unset($array[$key]);
            }
        }
        return $array;
    }

    public static function getAttributeNames($entity): array
    {
        if ($entity instanceof DynamicEntityAttributesInterface) {
            return $entity->attributes();
        }
        return \Untek\Core\Instance\Helpers\PropertyHelper::getAttributeNames($entity);
        /*$reflClass = new ReflectionClass($entity);
        $attributesRef = $reflClass->getProperties();
        $attributes = ArrayHelper::getColumn($attributesRef, 'name');
        foreach ($attributes as $index => $attributeName) {
            if ($attributeName[0] == '_') {
                unset($attributes[$index]);
            }
        }
        return $attributes;*/
    }

    /*public static function setAttribute(object $entity, string $name, $value): void
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $propertyAccessor->setValue($entity, $name, $value);
    }*/

    public static function setAttributesFromObject(object $fromObject, object $toObject): void
    {
        $entityAttributes = self::toArray($fromObject);
        $entityAttributes = ArrayHelper::extractByKeys($entityAttributes, self::getAttributeNames($toObject));
        PropertyHelper::setAttributes($toObject, $entityAttributes);
    }

    /*public static function setAttributes(object $entity, $data, array $filedsOnly = []): void
    {
        if (empty($data)) {
            return;
        }
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach ($data as $name => $value) {
            $name = Inflector::variablize($name);
            $isAllow = empty($filedsOnly) || in_array($name, $filedsOnly);
            if ($isAllow) {
                $isWritable = $propertyAccessor->isWritable($entity, $name);
                if ($isWritable) {
                    $propertyAccessor->setValue($entity, $name, $value);
                }
            }
        }
    }

    public static function getAttribute(object $entity, string $key)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        return $propertyAccessor->getValue($entity, $key);
    }*/
}
