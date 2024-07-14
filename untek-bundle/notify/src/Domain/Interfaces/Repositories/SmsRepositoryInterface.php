<?php

namespace Untek\Component\Web\Widget\Widgets\Toastr\Application\Services;

use Untek\Component\Web\Widget\Widgets\Toastr\Domain\Entities\SmsEntity;

interface SmsRepositoryInterface
{

    public function send(SmsEntity $smsEntity);

}