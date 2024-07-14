<?php

namespace Untek\Bundle\Notify\Application\Services;

use Untek\Bundle\Notify\Domain\Entities\SmsEntity;

interface SmsRepositoryInterface
{

    public function send(SmsEntity $smsEntity);

}