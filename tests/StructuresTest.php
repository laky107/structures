<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 4.10.2017
 * Time: 12:11
 */

namespace Zuffik\Test\Structures;

use PHPUnit\Framework\TestCase;
use Zuffik\Structures\Data\ArrayList;
use Zuffik\Test\Structures\Objects\ReturnsObject;

class StructuresTest extends TestCase
{
    public function testFind()
    {
        $obj = new ReturnsObject(5);
        $obj1 = new ReturnsObject(6);
        $list = new ArrayList($obj, $obj1);
        $this->assertEquals($obj, $list->find(5, ['getObj', 'getObject']));
        $this->assertEquals($obj1, $list->find(6, ['getObj', 'getObject']));
    }

    public function testSort()
    {
        $listOrdered = new ArrayList([
            [
                'room' => 1,
                'block' => 'A'
            ],
            [
                'room' => 2,
                'block' => 'A'
            ],
            [
                'room' => 3,
                'block' => 'A'
            ],
            [
                'room' => 4,
                'block' => 'A'
            ],
            [
                'room' => 1,
                'block' => 'B'
            ],
            [
                'room' => 2,
                'block' => 'B'
            ],
            [
                'room' => 3,
                'block' => 'B'
            ],
            [
                'room' => 4,
                'block' => 'B'
            ]
        ]);
        $listUnordered = new ArrayList([
            [
                'room' => 1,
                'block' => 'A'
            ],
            [
                'room' => 3,
                'block' => 'A'
            ],
            [
                'room' => 3,
                'block' => 'B'
            ],
            [
                'room' => 4,
                'block' => 'A'
            ],
            [
                'room' => 1,
                'block' => 'B'
            ],
            [
                'room' => 2,
                'block' => 'B'
            ],
            [
                'room' => 2,
                'block' => 'A'
            ],
            [
                'room' => 4,
                'block' => 'B'
            ]
        ]);
        $this->assertEquals($listOrdered, $listUnordered->multiSort([
            'block' => 'asc',
            'room' => 'asc'
        ]));
    }
}
