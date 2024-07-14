<?php

namespace Untek\Component\Web\Widget\Widgets\Toastr\Infrastructure\Services;

use Psr\Container\ContainerInterface;
use Untek\Component\Web\Widget\Widgets\Toastr\Domain\Entities\EmailEntity;
use Untek\Component\Web\Widget\Widgets\Toastr\Domain\Enums\ChannelEnum;
use Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\EmailRepositoryInterface;
use Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\EmailServiceInterface;
use Untek\Component\Web\Widget\Widgets\Toastr\Domain\Jobs\SendEmailJob;
use Untek\Bundle\Queue\Domain\Enums\PriorityEnum;
use Untek\Bundle\Queue\Domain\Interfaces\Services\JobServiceInterface;

class EmailService implements EmailServiceInterface
{

    private $emailRepository;
    private $jobService;
    private $container;

    public function __construct(EmailRepositoryInterface $emailRepository, JobServiceInterface $jobService, ContainerInterface $container)
    {
        $this->emailRepository = $emailRepository;
        $this->jobService = $jobService;
        $this->container = $container;
    }

    public function push(EmailEntity $emailEntity, $priority = PriorityEnum::NORMAL)
    {
        if($emailEntity->getFrom() == null) {
            $emailEntity->setFrom(getenv('EMAIL_FROM'));
        }
        $emailJob = new SendEmailJob($this->container);
        $emailJob->entity = $emailEntity;
        $pushResult = $this->jobService->push($emailJob, $priority, ChannelEnum::EMAIL);
    }

}
