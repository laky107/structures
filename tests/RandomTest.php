<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 10.9.2017
 * Time: 20:23
 */

namespace Zuffik\Test\Structures;

use Zuffik\Structures\Formats\Random;
use PHPUnit\Framework\TestCase;
use Zuffik\Structures\Types\Str;

class RandomTest extends TestCase
{
    public function testInterface()
    {
        $random = \random();
        $string = $random->string(10);
        $this->assertInstanceOf(Str::class, $string);
        $this->assertEquals(10, $string->length());
        $integer = $random->integer(0, 10);
        $this->assertLessThanOrEqual(10, $integer);
        $this->assertGreaterThanOrEqual(0, $integer);
        $decimal = $random->decimal(0, 10);
        $this->assertLessThanOrEqual(10, $decimal);
        $this->assertGreaterThanOrEqual(0, $decimal);
    }
}
