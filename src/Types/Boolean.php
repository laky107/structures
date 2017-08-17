<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 17.8.2017
 * Time: 9:03
 */

namespace Zuffik\Structures\Types;

class Boolean
{
    /** @var boolean */
    private $value;

    /**
     * Boolean constructor.
     * @param bool|Boolean $value
     */
    public function __construct($value = true)
    {
        $this->setValue($value);
    }

    /**
     * @return bool
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param bool|Boolean $value
     * @return Boolean
     */
    public function setValue($value)
    {
        $this->value = !!((string) $value);
        return $this;
    }

    public function __toString()
    {
        return $this->value ? '1' : '0';
    }
}