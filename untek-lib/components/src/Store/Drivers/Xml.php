<?php

namespace Untek\Lib\Components\Store\Drivers;

use Untek\Component\Encoder\Encoders\XmlEncoder;

class Xml extends BaseEncoderDriver implements DriverInterface
{

    public function __construct()
    {
        $encoder = new XmlEncoder(true, 'UTF-8', false);
        $this->setEncoder($encoder);
    }
}