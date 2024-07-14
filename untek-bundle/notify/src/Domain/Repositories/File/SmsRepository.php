<?php

namespace Untek\Component\Web\Widget\Widgets\Toastr\Domain\Repositories\File;

use Untek\Component\Web\Widget\Widgets\Toastr\Domain\Entities\SmsEntity;
use Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\SmsRepositoryInterface;
use Untek\Domain\Components\FileRepository\Base\BaseLoopedFileRepository;

class SmsRepository extends BaseLoopedFileRepository implements SmsRepositoryInterface
{

    public function tableName(): string
    {
        return 'notify_sms';
    }

    public function getEntityClass(): string
    {
        return SmsEntity::class;
    }

    public function send(SmsEntity $smsEntity)
    {
        $this->insert($smsEntity);
    }
}
