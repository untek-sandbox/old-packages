<?php

namespace Untek\Component\Web\Widget\Widgets\Toastr\Infrastructure\Services;

use Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\TestServiceInterface;
use Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\TestRepositoryInterface;
use Untek\Domain\Service\Base\BaseCrudService;

class TestService extends BaseCrudService implements TestServiceInterface
{

    public function __construct(TestRepositoryInterface $repository)
    {
        $this->setRepository($repository);
    }
    
}
