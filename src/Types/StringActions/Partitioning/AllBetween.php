<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 16.9.2017
 * Time: 16:55
 */

namespace Zuffik\Structures\Types\StringActions\Partitioning;


use Zuffik\Structures\Types\Str;

class AllBetween implements PartitionAction
{
    public function process(Str $str, $character = '')
    {
        return (new UntilLast())->process((new FromFirst())->process($str, $character), $character);
    }
}