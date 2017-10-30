<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 31.10.2017
 * Time: 0:45
 */

namespace Zuffik\Test\Structures;

use Zuffik\Structures\Formats\CSV;
use PHPUnit\Framework\TestCase;

class CSVTest extends TestCase
{
    public function test()
    {
        $handle = fopen(__DIR__ . '/../data/example.csv', 'r');
        $csv = csv($handle);
        foreach ($csv as $i => $item) {
            if($i % 2 == 0) {
                $this->assertEquals(arrayList(['foo', 'bar', 5, 6.7]), $item);
            } else {
                $this->assertEquals(arrayList(["yo", "ma", 7, 8.8]), $item);
            }
        }
        fclose($handle);
    }
}
