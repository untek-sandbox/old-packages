<?php

namespace Untek\Bundle\Notify\Application\Services;

use Untek\Bundle\Notify\Domain\Entities\EmailEntity;

interface EmailRepositoryInterface
{

    public function send(EmailEntity $emailEntity);

}