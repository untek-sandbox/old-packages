<?php

namespace Untek\Bundle\Notify\Infrastructure\Services;

use Untek\Bundle\Notify\Application\Services\TestServiceInterface;
use Untek\Bundle\Notify\Application\Services\TestRepositoryInterface;
use Untek\Domain\Service\Base\BaseCrudService;

class TestService extends BaseCrudService implements TestServiceInterface
{

    public function __construct(TestRepositoryInterface $repository)
    {
        $this->setRepository($repository);
    }
    
}
