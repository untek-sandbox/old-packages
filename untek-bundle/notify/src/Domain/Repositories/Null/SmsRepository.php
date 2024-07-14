<?php

namespace Untek\Component\Web\Widget\Widgets\Toastr\Domain\Repositories\Null;

use Untek\Component\Web\Widget\Widgets\Toastr\Domain\Entities\SmsEntity;
use Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\SmsRepositoryInterface;
use Untek\Domain\Repository\Base\BaseRepository;
use Untek\Domain\Domain\Interfaces\GetEntityClassInterface;

class SmsRepository extends BaseRepository implements SmsRepositoryInterface, GetEntityClassInterface
{

    public function getEntityClass(): string
    {
        return SmsEntity::class;
    }

    public function send(SmsEntity $smsEntity)
    {

    }
}
