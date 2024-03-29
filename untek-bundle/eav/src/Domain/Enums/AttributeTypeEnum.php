<?php

namespace Untek\Bundle\Eav\Domain\Enums;

use Untek\Core\Enum\Interfaces\GetLabelsInterface;

class AttributeTypeEnum implements GetLabelsInterface
{

    const STRING = 'string';
    const DATE = 'date';
    const ENUM = 'enum';
    const INTEGER = 'integer';
    const BOOLEAN = 'boolean';

    public static function getLabels()
    {
        return [
            self::STRING => 'String',
            self::DATE => 'Date',
            self::ENUM => 'Enum',
            self::INTEGER => 'Integer',
            self::BOOLEAN => 'Boolean',
        ];
    }
}
