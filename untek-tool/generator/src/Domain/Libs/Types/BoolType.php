<?php

namespace Untek\Tool\Generator\Domain\Libs\Types;

use Untek\Tool\Generator\Domain\Helpers\FieldRenderHelper;
use Untek\Tool\Generator\Domain\Helpers\TypeAttributeHelper;

class BoolType extends BaseType
{

    public function getType(): string {
        return 'bool';
    }

    public function isMatch(string $attributeName): bool
    {
        return
            TypeAttributeHelper::isMatchPrefix($attributeName, 'is_') ||
            TypeAttributeHelper::isMatchPrefix($attributeName, 'has_');
    }
}
