<?php

namespace Untek\Component\Web\Widget\Widgets\Toastr\Application\Services;

use Untek\Component\Web\Widget\Widgets\Toastr\Domain\Entities\EmailEntity;
use Untek\Bundle\Queue\Domain\Enums\PriorityEnum;

interface EmailServiceInterface
{

    public function push(EmailEntity $emailEntity, $priority = PriorityEnum::NORMAL);

}