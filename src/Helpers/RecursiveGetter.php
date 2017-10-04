<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 4.10.2017
 * Time: 12:21
 */

namespace Zuffik\Structures\Helpers;


use Exception;

class RecursiveGetter
{
    /**
     * @param object|array $item
     * @param string|string[] $methodKey
     * @return mixed
     * @throws Exception
     */
    public static function get($item, $methodKey)
    {
        if (!is_array($methodKey)) {
            $methodKey = [$methodKey];
        }
        $val = $item;
        foreach ($methodKey as $m) {
            if (is_object($val) && !method_exists($val, $m)) {
                throw new Exception('Object of class ' . get_class($val) . ' has no method ' . $m);
            }
            if (is_array($val) && !array_key_exists($m, $val)) {
                throw new Exception("Not existing key: $m");
            }
            $val = is_array($val) ? $val[$m] : call_user_func([$val, $m]);
        }
        return $val;
    }
}