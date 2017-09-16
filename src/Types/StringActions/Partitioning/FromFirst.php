<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 16.9.2017
 * Time: 16:56
 */

namespace Zuffik\Structures\Types\StringActions\Partitioning;


use Zuffik\Structures\Types\Str;

class FromFirst implements PartitionAction
{

    public function process(Str $str, $character = '')
    {
        $string = substr(strchr((string) $str, $character), 1);
        return $str->setValue($string === false ? '' : $string);
    }
}