<?php

namespace Untek\Component\Encoder\Encoders;

use Untek\Core\Instance\Helpers\InstanceHelper;
use Untek\Core\Contract\Encoder\Interfaces\EncoderInterface;
use Untek\Core\Collection\Interfaces\Enumerable;

/**
 * Агрегатный кодер
 *
 * Содержит в себе инстансы других кодеров.
 *
 * При кодировании/декодировании вызывает соответствующие методы вложенных кодеров.
 * Агрегатный кодер пригодится, когда необходимо реализовать "матрешку" из форматов, например - .tar.gz
 *
 * @todo переименовать в ChainEncoder
 */
abstract class BaseChainEncoder implements EncoderInterface
{

    /**
     * @var Enumerable|EncoderInterface[] Коллекция кодеров
     */
    protected $encoderCollection;

    /**
     * Получить коллекцию кодеров
     * @return Enumerable|EncoderInterface[] Коллекция кодеров
     */
    public function getEncoders(): Enumerable
    {
        return $this->encoderCollection;
    }

    public function encode($data)
    {
        $encoders = $this->encoderCollection->toArray();
        foreach ($encoders as $encoderClass) {
            $encoderInstance = $this->getEncoderInstance($encoderClass);
            $data = $encoderInstance->encode($data);
        }
        return $data;
    }

    public function decode($data)
    {
        $encoders = $this->encoderCollection->toArray();
        $encoders = array_reverse($encoders);
        foreach ($encoders as $encoderClass) {
            $encoderInstance = $this->getEncoderInstance($encoderClass);
            $data = $encoderInstance->decode($data);
        }
        return $data;
    }

    /**
     * Создать инстанс кодера
     * @param string|array $encoderClass Описание инстанса
     * @return EncoderInterface
     */
    protected function getEncoderInstance($encoderClass): EncoderInterface
    {
        return InstanceHelper::ensure($encoderClass);
    }
}
