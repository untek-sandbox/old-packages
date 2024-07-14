<?php

namespace Untek\Component\Web\Widget\Widgets\Toastr\Domain\Repositories\Null;

use Untek\Component\Web\Widget\Widgets\Toastr\Domain\Entities\EmailEntity;
use Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\EmailRepositoryInterface;
use Untek\Domain\Repository\Base\BaseRepository;
use Untek\Domain\Domain\Interfaces\GetEntityClassInterface;

class EmailRepository extends BaseRepository implements EmailRepositoryInterface, GetEntityClassInterface
{

    public function getEntityClass(): string
    {
        return EmailEntity::class;
    }

    public function send(EmailEntity $emailEntity)
    {
        
    }
}
