<?php

namespace Slizk\PackageTemplate\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Slizk\PackageTemplate\Example;

class ExampleTest extends TestCase
{
    public function testAdd()
    {
        $example = new Example();
        $this->assertEquals(3, $example->add(1, 2));
    }

    public function testMinus()
    {
        $example = new Example();
        $this->assertEquals(-1, $example->minus(1, 2));
    }
}
