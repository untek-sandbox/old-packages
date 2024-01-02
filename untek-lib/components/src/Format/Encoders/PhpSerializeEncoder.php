<?php

namespace Untek\Component\Encoder\Encoders;

use Untek\Core\Contract\Encoder\Interfaces\EncoderInterface;

/**
 * PHP-сериализатор.
 */
class PhpSerializeEncoder implements EncoderInterface
{

    public function encode($data)
    {
        return serialize($data);
    }

    public function decode($encodedData)
    {
        return unserialize($encodedData);
    }
}