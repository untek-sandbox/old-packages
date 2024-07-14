<?php

namespace Untek\Component\Web\Widget\Widgets\Toastr\Application\Services;

use Untek\Component\Web\Widget\Widgets\Toastr\Domain\Entities\SmsEntity;
use Untek\Bundle\Queue\Domain\Enums\PriorityEnum;

interface SmsServiceInterface
{

    public function push(SmsEntity $smsEntity, $priority = PriorityEnum::NORMAL);

}