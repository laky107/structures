<?php

use Zuffik\Structures\Formats\Regex;
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