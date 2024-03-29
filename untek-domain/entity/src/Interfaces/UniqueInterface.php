<?php

namespace Untek\Domain\Entity\Interfaces;

/**
 * Интерфейс описания уникальных полей сущности
 */
interface UniqueInterface
{

    /**
     * Уникальные группы полей
     * @return array
     */
    public function unique(): array;
}
