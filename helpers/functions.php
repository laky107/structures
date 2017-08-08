<?php

use Zuffik\Structures\Data\ArrayList;
use Zuffik\Structures\Data\BasicStructure;
use Zuffik\Structures\Formats\Regex;
use Zuffik\Structures\Types\Float;
use Zuffik\Structures\Types\Integer;
use Zuffik\Structures\Types\Number;
use Zuffik\Structures\Types\Str;

if(!function_exists('string')) {
    /**
     * @param string|Str $str
     * @return Str
     */
    function string($str)
    {
        return new Str((string) $str);
    }
}

if(!function_exists('regex')) {
    /**
     * @param string|Regex $regex
     * @return Regex
     */
    function regex($regex)
    {
        return new Regex((string) $regex);
    }
}

if(!function_exists('arrayList')) {
    /**
     * @param array|BasicStructure $param
     * @return ArrayList
     */
    function arrayList($param = [])
    {
        return new ArrayList($param);
    }
}

if(!function_exists('number')) {
    /**
     * @param int $value
     * @return Number
     */
    function number($value = 0)
    {
        return Number::create($value);
    }
}

if(!function_exists('integer')) {
    /**
     * @param int $value
     * @param bool $strict default true. If you want not to type false everywhere use function number
     * @return Integer
     */
    function integer($value = 0, $strict = true)
    {
        return new Integer($value, $strict);
    }
}

if(!function_exists('float')) {
    /**
     * @param float $value
     * @param bool $strict default true. If you want not to type false everywhere use function number
     * @return int
     */
    function float($value = 0.0, $strict = true)
    {
        return new Float($value, $strict);
    }
}
