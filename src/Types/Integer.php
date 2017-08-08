<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 8.8.2017
 * Time: 6:49
 */

namespace Zuffik\Structures\Types;


class Integer extends Number
{
    /**
     * Integer constructor.
     * @param int $value
     * @param bool $strict
     */
    public function __construct($value = 0, $strict = false)
    {
        parent::__construct($value);
        if($strict && (is_float($this->value) || !is_numeric($this->value))) {
            throw new \InvalidArgumentException('Integer::__construct() accepts only integers and ' . gettype($this->value) . ' given.');
        }
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return intval(parent::getValue());
    }

    /**
     * @return int
     */
    public function __toString()
    {
        return intval(parent::__toString());
    }

    /**
     * @param int|Integer $number
     * @return Number
     */
    public function divide($number)
    {
        $this->value = intval(floor(parent::divide($number)));
        return $this;
    }

    /**
     * @param int|Integer $number
     * @return Number
     */
    public function mod($number)
    {
        $this->value %= $number;
        return $this;
    }
}