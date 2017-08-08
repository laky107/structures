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
        parent::__construct($value);
        if($strict && !(is_float($this->value) || !is_numeric($this->value))) {
            throw new \InvalidArgumentException('Double::__construct() accepts only floats and ' . gettype($this->value) . ' given.');
        }
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
        return (string) floatval(parent::__toString());
    }
}