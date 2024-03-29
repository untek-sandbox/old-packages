<?php declare(strict_types=1);

namespace Untek\Bundle\Log\Domain\Monolog\Formatter;

use Monolog\DateTimeImmutable;
use Monolog\Formatter\NormalizerFormatter;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Domain\Entity\Helpers\EntityHelper;
use Untek\Bundle\Log\Domain\Entities\LogEntity;

class EloquentFormatter extends NormalizerFormatter
{

    public function format(array $record): LogEntity
    {
        $normalized = $this->normalize($record);
        $normalized['context'] = json_encode($normalized['context'], JSON_PRETTY_PRINT);
        $normalized['extra'] = json_encode($normalized['extra'], JSON_PRETTY_PRINT);
        $normalized['createdAt'] = $normalized['datetime'];
        $normalized = ArrayHelper::extractByKeys($normalized, [
            'level',
            'level_name',
            'channel',
            'createdAt',
            'message',
            'context',
            'extra',
        ]);
        $logEntity = EntityHelper::createEntity(LogEntity::class, $normalized);
        return $logEntity;
    }

    protected function normalize($data, int $depth = 0)
    {
        if ($data instanceof DateTimeImmutable) {
            return $data;
        }
        $data = parent::normalize($data);
        return $data;
    }
}
