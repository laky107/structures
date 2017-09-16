<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 16.9.2017
 * Time: 16:51
 */

namespace Zuffik\Structures\Types\StringActions\Partitioning;


use Zuffik\Structures\Types\Str;

class UntilFirst implements PartitionAction
{
    public function process(Str $str, $character = '')
    {
        $pos = intval($str->find($character));
        return $str->substring(0, $pos < 0 ? 0 : $pos);
    }
}