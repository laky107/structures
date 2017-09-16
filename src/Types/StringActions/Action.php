<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 16.9.2017
 * Time: 16:49
 */

namespace Zuffik\Structures\Types\StringActions;


use Zuffik\Structures\Types\Str;

interface Action
{
    /**
     * @param Str $str
     * @return Str
     */
    public function process(Str $str);
}