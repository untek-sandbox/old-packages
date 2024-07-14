<?php

namespace Untek\Component\Web\Widget\Widgets\Toastr\Domain\Repositories\File;

use Untek\Component\Web\Widget\Widgets\Toastr\Domain\Entities\EmailEntity;
use Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\EmailRepositoryInterface;
use Untek\Domain\Components\FileRepository\Base\BaseLoopedFileRepository;

class EmailRepository extends BaseLoopedFileRepository implements EmailRepositoryInterface
{

    public function tableName(): string
    {
        return 'notify_email';
    }

    public function getEntityClass(): string
    {
        return EmailEntity::class;
    }

    public function send(EmailEntity $emailEntity)
    {
        $this->insert($emailEntity);
    }
}
