<?php

namespace Untek\Component\Web\Widget\Widgets\Toastr\Application\Services;

use Untek\Component\Web\Widget\Widgets\Toastr\Domain\Entities\EmailEntity;

interface EmailRepositoryInterface
{

    public function send(EmailEntity $emailEntity);

}