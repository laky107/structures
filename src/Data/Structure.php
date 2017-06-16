<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 3.6.2017
 * Time: 21:59
 */

namespace Zuffik\Structures\Data;


use ArrayAccess;
use Countable;
use Iterator;

abstract class Structure implements Countable, ArrayAccess, BasicStructure, Iterator
{
    /**
     * @return int size of structure
     */
    public abstract function size();

    /**
     * @return bool (usually size == 0)
     */
    public function isEmpty()
    {
        return $this->size() == 0;
    }

    /**
     * @return static
     */
    public function copy()
    {
        return new static($this);
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->size();
    }

    /**
     * @param mixed $search a value to search
     * @param callable|null $method a method to call on iterated value to compare with $search
     * @param bool $strict whether to use == or ===
     * @return mixed|null
     */
    public abstract function find($search, $method = null, $strict = false);

    /**
     * @param Structure|array $structure
     * @return static
     */
    public abstract function merge($structure);

    /**
     * @param callable $callable
     * @return static
     */
    public abstract function map($callable);

    /**
     * @param callable $callable
     * @return static
     */
    public abstract function filter($callable);

    /**
     * @return static
     */
    public abstract function clear();
}