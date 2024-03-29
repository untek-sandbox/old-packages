<?php

namespace Untek\Sandbox\Sandbox\I18n\Domain\Interfaces\Services;

use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Domain\Query\Entities\Query;
use Untek\Sandbox\Sandbox\I18n\Domain\Entities\TranslateEntity;

interface TranslateServiceInterface
{

    public function findOneByEntity(int $entityTypeId, int $entityId, int $languageId, Query $query = null): TranslateEntity;

    public function removeByUnique(int $entityTypeId, int $entityId): void;

    public function persist(int $entityTypeId, int $entityId, int $languageId, string $value): TranslateEntity;

    public function batchPersist(int $entityTypeId, int $entityId, array $values): Enumerable;
}
