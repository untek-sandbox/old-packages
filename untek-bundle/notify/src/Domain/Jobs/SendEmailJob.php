<?php

namespace Untek\Component\Web\Widget\Widgets\Toastr\Domain\Jobs;

use Untek\Component\Web\Widget\Widgets\Toastr\Domain\Entities\EmailEntity;
use Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\EmailRepositoryInterface;
use Untek\Bundle\Queue\Domain\Interfaces\JobInterface;
use Psr\Container\ContainerInterface;

class SendEmailJob implements JobInterface
{

    /** @var EmailEntity */
    public $entity;

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function run()
    {
        /** @var EmailRepositoryInterface $emailRepository */
        $emailRepository = $this->container->get(EmailRepositoryInterface::class);
        $emailRepository->send($this->entity);
    }
}