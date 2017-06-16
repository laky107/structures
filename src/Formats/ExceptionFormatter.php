<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 3.1.2017
 * Time: 11:42
 */

namespace Zuffik\Structures\Formats;


use Zuffik\Structures\Data\ArrayList;
use Zuffik\Structures\Data\HashMap;

class ExceptionFormatter
{
    public static function format(\Exception $exception)
    {
        return (new ArrayList($exception->getTrace()))->map(function($traceItem) {
            $traceItem = new HashMap($traceItem);
            $result = '';
            if(!empty($traceItem['file'])) {
                $result .= $traceItem['file'] . ':' . $traceItem['line'] . ' ';
            }
            if(!empty($traceItem['class'])) {
                $result .= $traceItem['class'] . $traceItem['type'];
            }
            $result .= $traceItem['function'] . '(' . (new ArrayList($traceItem->get('args', [])))->map(function($item) {
                return is_object($item) ? get_class($item) : $item;
            })->join(', ') . ')';
            return $result;
        });
    }
}