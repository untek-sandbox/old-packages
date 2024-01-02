<?php

namespace Untek\Bundle\Reference\Domain\Interfaces\Repositories;

use Untek\Bundle\Reference\Domain\Entities\BookEntity;
use Untek\Domain\Repository\Interfaces\CrudRepositoryInterface;
use Untek\Domain\Query\Entities\Query;

interface BookRepositoryInterface extends CrudRepositoryInterface
{

    public function findOneByName(string $name, Query $query = null): BookEntity;
    
}
