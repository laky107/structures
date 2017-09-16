<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 10.9.2017
 * Time: 20:02
 */

namespace Zuffik\Test\Structures;

use PHPUnit\Framework\TestCase;
use Zuffik\Structures\Types\StringActions\Partitioning\StringPartition;

class StrTest extends TestCase
{
    public function testInterface()
    {
        $this->assertEquals('', (string) string(''));
        $this->assertEquals('bbb', (string) string('aaa')->replace('a', 'b'));
        $this->assertEquals('HELLO', (string) string('HeLlO')->toUppercase());
        $this->assertEquals('hello', (string) string('HeLlO')->toLowercase());
        $this->assertEquals('HeLlO woRlD', (string) string('heLlO woRlD')->capitalize());
        $this->assertEquals('HeLlO WoRlD', (string) string('heLlO woRlD')->capitalizeAll());
        $this->assertEquals('heLlO WoRlD', (string) string('HeLlO WoRlD')->lowerFirst());
        $this->assertEquals('ello worl', (string) string('Hello world')->substring(1, 9));
        $this->assertTrue(string('Hello world')->contains('Hello'));
        $this->assertFalse(string('Hello world')->contains('Foo'));
        $this->assertTrue(string('')->isEmpty());
        $this->assertFalse(string('Hello world')->isEmpty());
        $this->assertEquals('Hello world', (string) string('        Hello world       ')->trim());
        $this->assertEquals('hello-world', (string) string('Hello world')->slug());
        $this->assertEquals('00001', (string) string('1')->pad(5));
        $this->assertEquals('HelloWorld', (string) string('Hello world')->upperCamelCase());
        $this->assertEquals('helloWorld', (string) string('Hello world')->lowerCamelCase());
        $this->assertEquals('Hello world', (string) string('Hello %s')->format('world'));
        $this->assertEquals('Hello world', (string) string('')->setValue('Hello world'));
        $this->assertEquals('hello_world', (string) string('Hello world')->snakeCase());
    }

    public function testPartitioning()
    {
        $str = string('hello_world_how_are_you');
        $this->assertEquals('hello_world_how_are', (string) string($str)->part('_', StringPartition::STR_PART_UNTIL_LAST));
        $this->assertEquals('hello', (string) string($str)->part('_', StringPartition::STR_PART_UNTIL_FIRST));
        $this->assertEquals('world_how_are_you', (string) string($str)->part('_', StringPartition::STR_PART_FROM_FIRST));
        $this->assertEquals('you', (string) string($str)->part('_', StringPartition::STR_PART_FROM_LAST));
        $this->assertEquals('world_how_are', (string) string($str)->part('_', StringPartition::STR_PART_ALL_BETWEEN));

        $this->assertEquals('', (string) string($str)->part(',', StringPartition::STR_PART_UNTIL_LAST));
        $this->assertEquals('', (string) string($str)->part(',', StringPartition::STR_PART_UNTIL_FIRST));
        $this->assertEquals('', (string) string($str)->part(',', StringPartition::STR_PART_FROM_FIRST));
        $this->assertEquals('', (string) string($str)->part(',', StringPartition::STR_PART_FROM_LAST));
        $this->assertEquals('', (string) string($str)->part(',', StringPartition::STR_PART_ALL_BETWEEN));
    }
}
