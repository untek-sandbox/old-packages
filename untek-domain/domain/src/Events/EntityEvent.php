<?php

namespace Untek\Domain\Domain\Events;

use Symfony\Contracts\EventDispatcher\Event;
use Untek\Core\EventDispatcher\Traits\EventSkipHandleTrait;

class EntityEvent extends Event
{

    use EventSkipHandleTrait;

    private $entity;

    public function __construct(object $entity)
    {
        $this->entity = $entity;
    }

    public function getEntity(): object
    {
        return $this->entity;
    }
}
