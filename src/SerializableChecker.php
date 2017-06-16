<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 12.11.2016
 * Time: 17:25
 */

namespace Zuffik\Structures;


trait SerializableChecker
{
    public function isSerializable($var)
    {
        return self::serializable($var);
    }

    public static function serializable($var)
    {
        return is_object($var) && in_array('Zuffik\Structures\Serializable', class_uses(get_class($var)));
    }
}