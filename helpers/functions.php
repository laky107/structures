<?php

use Zuffik\Structures\Data\ArrayList;
use Zuffik\Structures\Data\BasicStructure;
use Zuffik\Structures\Data\HashMap;
use Zuffik\Structures\Data\LinkedList;
use Zuffik\Structures\Formats\JSON;
use Zuffik\Structures\Formats\Random;
use Zuffik\Structures\Formats\Regex;
use Zuffik\Structures\Formats\URL;
use Zuffik\Structures\Types\Boolean;
use Zuffik\Structures\Types\Double;
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

if(!function_exists('json')) {
    /**
     * @param array|BasicStructure|string|JSON $json
     * @return JSON
     */
    function json($json)
    {
        return new JSON($json);
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

if(!function_exists('linkedList')) {
    /**
     * @param array|BasicStructure $param
     * @return LinkedList
     */
    function linkedList($param = [])
    {
        return new LinkedList($param);
    }
}

if(!function_exists('hashMap')) {
    /**
     * @param array|BasicStructure $param
     * @return HashMap
     */
    function hashMap($param = [])
    {
        return new HashMap($param);
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

if(!function_exists('double')) {
    /**
     * @param float $value
     * @param bool $strict default true. If you want not to type false everywhere use function number
     * @return Double
     */
    function double($value = 0.0, $strict = true)
    {
        return new Double($value, $strict);
    }
}

if(!function_exists('boolean')) {
    /**
     * @param boolean|Boolean $value
     * @return Boolean
     */
    function boolean($value)
    {
        return new Boolean($value);
    }
}

if(!function_exists('random')) {
    function random()
    {
        return new Random();
    }
}

if(!function_exists('url')) {
    function url($path = '')
    {
        return new Url($path);
    }
}
