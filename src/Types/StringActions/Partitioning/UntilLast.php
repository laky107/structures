<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 16.9.2017
 * Time: 16:55
 */

namespace Zuffik\Structures\Types\StringActions\Partitioning;


use Zuffik\Structures\Types\Str;

class UntilLast implements PartitionAction
{

    public function process(Str $str, $character = '')
    {
        return $str->substring(0, intval(strrpos((string) $str, $character)));
    }
}