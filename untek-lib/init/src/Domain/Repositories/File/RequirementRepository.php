<?php

namespace Untek\Lib\Init\Domain\Repositories\File;

use Untek\Lib\Init\Domain\Entities\RequirementEntity;
use Untek\Lib\Init\Domain\Interfaces\Repositories\RequirementRepositoryInterface;

class RequirementRepository implements RequirementRepositoryInterface
{
    /*public function getEntityClass() : string
    {
        return RequirementEntity::class;
    }*/

    public function findAll() {
        $requirements = [];
        $arr = explode(',', getenv('REQUIREMENT_CONFIG'));
        foreach ($arr as $item) {
            $itemRequirements = include($this->fileName($item));
            $requirements = array_merge($requirements, $itemRequirements);
        }
        return $requirements;
    }

    private function fileName(string $name) : string
    {
        $rootDir = realpath(__DIR__ . '/../../../../../../../../..');
        return $rootDir . '/' . $name;
    }

}
