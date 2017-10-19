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
use Zuffik\Structures\Helpers\Finder;

abstract class Structure implements Countable, ArrayAccess, BasicStructure, Iterator
{
    /**
     * Structure constructor.
     * @param static|array $param standard or copy constructor
     */
    public abstract function __construct($param = null);

    /**
     * @return int size of structure
     */
    public abstract function size();

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

    /**
     * @param callable|string $callable
     * @return static
     */
    public abstract function sort($callable);

    /**
     * @param string|int $key
     * @return mixed
     */
    public abstract function get($key);

    /**
     * @param int|string $key
     * @param int|string $value
     * @return static
     */
    public abstract function set($key, $value);

    /**
     * @param int|string $key
     * @return static
     */
    public abstract function remove($key);

    /**
     * @return array
     */
    public abstract function toArray();

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return true;
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

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
     * @param int|string $i1
     * @param int|string $i2
     * @return static
     */
    public function swap($i1, $i2)
    {
        $tmp = $this[$i1];
        $this[$i1] = $this[$i2];
        $this[$i2] = $tmp;
        return $this;
    }

    /**
     * @param mixed $search a value to search
     * @param callable|null $method a method to call on iterated value to compare with $search
     * @param bool $strict whether to use == or ===
     * @return mixed|null
     * @throws \Exception
     */
    public function find($search, $method = null, $strict = false)
    {
        return Finder::find($this, $search, $method, $strict);
    }

    /**
     * @param callable $callable
     * @return int
     */
    public function countIf($callable)
    {
        $c = 0;
        foreach ($this as $key => $value) {
            if(call_user_func($callable, $value, $key)) {
                $c++;
            }
        }
        return $c++;
    }
}