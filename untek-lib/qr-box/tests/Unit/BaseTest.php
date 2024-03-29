<?php

namespace Untek\Lib\QrBox\Tests\Unit;

abstract class BaseTest extends \Untek\Tool\Test\Base\BaseTest
{

    public function assertZipContent(string $actual)
    {
        $isZip = mb_substr($actual, 0, 10) === "PK\x03\x04\x14\x00\x02\x00\x08\x00";
        $this->assertTrue($isZip);
    }

    public function assertDateTimeString(string $actual)
    {
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{3}\+\d{2}:\d{2}$/', $actual);
    }

    public function assertXmlString(string $actual)
    {
        $this->assertMatchesRegularExpression('/^<\?xml.+>[\s\S]+<\/.+>\s*$/', $actual);
    }

    public function assertNotXmlString(string $actual)
    {
        $this->assertDoesNotMatchRegularExpression('/^<\?xml.+>[\s\S]+<\/.+>$/', $actual);
    }
}
