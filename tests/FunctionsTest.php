<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 8.8.2017
 * Time: 14:28
 */

namespace Zuffik\Test\Structures;

use PHPUnit\Framework\TestCase;
use Zuffik\Structures\Data\ArrayList;
use Zuffik\Structures\Data\HashMap;
use Zuffik\Structures\Data\LinkedList;
use Zuffik\Structures\Formats\JSON;
use Zuffik\Structures\Formats\Regex;
use Zuffik\Structures\Types\Double;
use Zuffik\Structures\Types\Integer;
use Zuffik\Structures\Types\Number;
use Zuffik\Structures\Types\Str;

class FunctionsTest extends TestCase
{
    public function test()
    {
        $this->assertEquals(new Str('test'), string('test'));
        $this->assertEquals(new Regex('/^a$/'), regex('/^a$/'));
        $this->assertEquals(new JSON('{"a":"b"}'), json('{"a":"b"}'));
        $this->assertEquals(new ArrayList([1, 2]), arrayList([1, 2]));
        $this->assertEquals(new LinkedList([1, 2]), linkedList([1, 2]));
        $this->assertEquals(new HashMap([1 => 2]), hashMap([1 => 2]));
        $this->assertEquals(Number::create(1), number(1));
        $this->assertEquals(Number::create(1.1), number(1.1));
        $this->assertEquals(new Integer(1), integer(1));
        $this->assertEquals(new Double(1.1), double(1.1));
    }

    public function testNotValidRegex()
    {
        $this->expectException(\InvalidArgumentException::class);
        regex('this is not valid regex');
    }

    public function testNotValidJson()
    {
        $this->expectException(\InvalidArgumentException::class);
        json('this is not valid json');
    }
}
