<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 16.9.2017
 * Time: 16:52
 */

namespace Zuffik\Structures\Types\StringActions\Partitioning;


use Zuffik\Structures\Types\Str;
use Zuffik\Structures\Types\StringActions\Action;

interface PartitionAction extends Action
{
    /**
     * @param Str $str
     * @param string|Str $character
     * @return Str
     */
    public function process(Str $str, $character = '');
}