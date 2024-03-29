<?php

namespace Untek\Domain\Service\Traits;

use Untek\Core\Instance\Helpers\PropertyHelper;
use Untek\Domain\Domain\Enums\EventEnum;

trait CrudServiceUpdateTrait
{

    public function updateById($id, $data)
    {
        if ($this->hasEntityManager()) {
            $this->getEntityManager()->beginTransaction();
        }
        try {
            $entity = $this->getRepository()->findOneById($id);
            PropertyHelper::setAttributes($entity, $data);
            $event = $this->dispatchEntityEvent($entity, EventEnum::BEFORE_UPDATE_ENTITY);
            $this->getRepository()->update($entity);
            $event = $this->dispatchEntityEvent($entity, EventEnum::AFTER_UPDATE_ENTITY);
        } catch (\Throwable $e) {
            if ($this->hasEntityManager()) {
                $this->getEntityManager()->rollbackTransaction();
            }
            throw $e;
        }
        if ($this->hasEntityManager()) {
            $this->getEntityManager()->commitTransaction();
        }
    }
}
