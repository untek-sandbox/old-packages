<?php

namespace Untek\Sandbox\Sandbox\Person2\Domain\Services;

use Untek\Sandbox\Sandbox\Person2\Domain\Interfaces\Services\InheritanceServiceInterface;
use Untek\Domain\EntityManager\Interfaces\EntityManagerInterface;
use Untek\Sandbox\Sandbox\Person2\Domain\Interfaces\Repositories\InheritanceRepositoryInterface;
use Untek\Domain\Service\Base\BaseCrudService;
use Untek\Sandbox\Sandbox\Person2\Domain\Entities\InheritanceEntity;

/**
 * @method
 * InheritanceRepositoryInterface getRepository()
 */
class InheritanceService extends BaseCrudService implements InheritanceServiceInterface
{

    public function __construct(EntityManagerInterface $em)
    {
        $this->setEntityManager($em);
    }

    public function getEntityClass() : string
    {
        return InheritanceEntity::class;
    }


}

