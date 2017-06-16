<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 10.1.2017
 * Time: 14:45
 */

namespace Zuffik\Structures\Convertors;


class FromScalar
{
    public static function formatInput($input)
    {
        $unserialized = @unserialize($input);
        if($unserialized !== false) {
            $input = $unserialized;
        }
        return $input;
    }
}