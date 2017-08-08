<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 8.8.2017
 * Time: 6:49
 */

namespace Zuffik\Structures\Types;


abstract class Number
{
    /**
     * @var float|int
     */
    protected $value;

    /**
     * Number constructor.
     * @param float|int|Number $value
     */
    public function __construct($value = 0)
    {
        if($value instanceof Number) {
            $this->value = $value->getValue();
        }
        $this->value = $value;
    }

    /**
     * @param int|float $value
     * @return Number
     */
    public static function create($value)
    {
        return is_float($value) ? new Double($value) : new Integer($value);
    }

    /**
     * @param float|int $value
     * @return Number
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return float|int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return float|int
     */
    public function __toString()
    {
        return $this->value;
    }

    /**
     * @param Number|int|float $number
     * @return Number
     */
    public function add($number)
    {
        $this->value += $number;
        return $this;
    }

    /**
     * @param Number|int|float $number
     * @return Number
     */
    public function subtract($number)
    {
        $this->value -= $number;
        return $this;
    }

    /**
     * @param Number|int|float $number
     * @return Number
     */
    public function multiply($number)
    {
        $this->value *= $number;
        return $this;
    }

    /**
     * @param Number|int|float $number
     * @return Number
     */
    public function divide($number)
    {
        $this->value /= $number;
        return $this;
    }

    /**
     * @return Number
     */
    public function abs()
    {
        $this->value = abs($this->value);
        return $this;
    }


}