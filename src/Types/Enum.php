<?php
/**
 * Created by PhpStorm.
 * User: zuffa
 * Date: 9.8.2016
 * Time: 14:37
 */

namespace Zuffik\Structures\Types;


use Exception;
use Zuffik\Structures\Serializable;
use ReflectionClass;

abstract class Enum
{
    use Serializable;
    /** @var mixed */
    private $value;
    /** @var array */
    private $constants;

    /**
     * Enum constructor.
     * @param $value
     * @throws Exception
     */
    public function __construct($value)
    {
        $this->constants = (new ReflectionClass($this))->getConstants();
        $this->setValue($value);
    }

    public static function getValues()
    {
        return (new ReflectionClass(self::class))->getConstants();
    }

    /**
     * @param mixed $value
     * @return Enum
     * @throws Exception
     */
    public function setValue($value)
    {
        if(!in_array($value, $this->constants)) {
            throw new Exception('Could not read ' . $value . ' constant of ' . get_class($this));
        }
        $this->value = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->constants;
    }

    public static function verify($value)
    {
        return (new static($value))->getValue();
    }
}