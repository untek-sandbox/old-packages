<?php

namespace Untek\Tool\Generator\Domain\Libs\MigrationFieldRender;

use Untek\Tool\Generator\Domain\Libs\Types\BoolType;

class TypeBooleanRender extends BaseRender
{

    public function isMatch(): bool
    {
        return BoolType::match($this->attributeName);
    }

    public function run(): string
    {
        return $this->renderCode('boolean', $this->attributeName);
    }

}
