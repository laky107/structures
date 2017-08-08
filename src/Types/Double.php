<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 8.8.2017
 * Time: 6:49
 */

namespace Zuffik\Structures\Types;


class Double extends Number
{
    /**
     * Integer constructor.
     * @param float $value
     * @param bool $strict
     */
    public function __construct($value = 0.0, $strict = false)
    {
        if($strict && !(is_float($value) || !is_numeric($value))) {
            throw new \InvalidArgumentException('Double::__construct() accepts only floats and ' . gettype($value) . ' given.');
        }
        parent::__construct($value);
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return floatval(parent::getValue());
    }

    /**
     * @return float
     */
    public function __toString()
    {
        return floatval(parent::__toString());
    }
}