<?php

namespace Untek\Tool\Test\Base;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{

    use ArraySubsetAsserts;

}
