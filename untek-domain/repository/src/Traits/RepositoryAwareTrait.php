<?php

namespace Untek\Domain\Repository\Traits;

use Untek\Domain\Domain\Interfaces\GetEntityClassInterface;

trait RepositoryAwareTrait
{

    private $repository;

    /**
     * @return GetEntityClassInterface
     */
    protected function getRepository(): object
    {
        if ($this->repository) {
            return $this->repository;
        }
        return $this
            ->getEntityManager()
            ->getRepository($this->getEntityClass());
    }

    protected function setRepository(object $repository)
    {
        $this->repository = $repository;
    }
}
