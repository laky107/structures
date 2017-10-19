<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 4.10.2017
 * Time: 12:11
 */

namespace Zuffik\Test\Structures;

use PHPUnit\Framework\TestCase;
use Exception;
use Zuffik\Structures\Data\ArrayList;
use Zuffik\Structures\Data\HashMap;
use Zuffik\Test\Structures\Objects\ReturnsObject;

class StructuresTest extends TestCase
{
    /**
     * @var ArrayList
     */
    private $listOrdered;
    /**
     * @var ArrayList
     */
    private $listUnordered;

    protected function setUp()
    {
        parent::setUp();
        $this->listOrdered = new ArrayList([
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
        $this->listUnordered = new ArrayList([
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
    }

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
        $this->assertEquals($this->listOrdered, $this->listUnordered->multiSort([
            'block' => 'asc',
            'room' => 'asc'
        ]));
    }

    public function testCategorize()
    {
        $categorized = new HashMap([
            'A' => [
                [
                    'room' => 1,
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
                    'room' => 2,
                    'block' => 'A'
                ],
            ],
            'B' => [
                [
                    'room' => 3,
                    'block' => 'B'
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
                    'room' => 4,
                    'block' => 'B'
                ]
            ]
        ]);
        $this->assertEquals($categorized, $this->listUnordered->categorize('block'));
        $this->assertEquals($categorized, $this->listUnordered->categorize(function($item) {
            return $item['block'];
        }));
    }

    public function testSwap()
    {
        $list = \arrayList([1, 2, 4, 8, 16]);
        $this->assertEquals(\arrayList([1, 8, 4, 2, 16]), $list->swap(1, 3));
        $this->expectException(Exception::class);
        $list->swap(10, 15);
    }
}
