<?php
/**
 * Created by PhpStorm.
 * UserModule: zuffik
 * Date: 7.7.2016
 * Time: 15:33
 */

namespace Zuffik\Structures\Data;


use Exception;
use Generator;
use Zuffik\Structures\Serializable;
use Zuffik\Structures\SerializableChecker;

class HashMap extends Structure
{
    use Serializable, SerializableChecker;

    /** @var array */
    private $map = [];
    /** @var ArrayList|KeyValue[] */
    private $iterator;

    /**
     * HashMap constructor.
     * @param array|HashMap $map
     * @throws \Exception
     */
    public function __construct($map = [])
    {
        if(!$this->isSerializable($map) && !is_array($map)) {
            throw new \Exception('Argument passed to HashMap must be type of array or instance of Serializable. ' . gettype($map) . ' given.');
        }
        $this->map = $this->isSerializable($map) ? $map->toArray() : $map;
        $this->iterator = new ArrayList();
        foreach ($map as $key => $item) {
            $this->iterator->add(new KeyValue($key, $item));
        }
    }

    /**
     * @param string|int|bool $key
     * @param mixed $value
     * @return HashMap
     */
    public function put($key, $value)
    {
        $this->map[$key] = $value;
        $this->iterator->add(new KeyValue($key, $value));
        return $this;
    }

    /**
     * @param string|int|bool $key
     * @return bool
     */
    public function keyExists($key)
    {
        return array_key_exists($key, $this->map);
    }

    /**
     * @param string|int|bool $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if(!isset($this->map[$key])){
            return $default;
        }
        return $this->map[$key];
    }

    /**
     * @param array|HashMap $array
     * @return HashMap
     */
    public function merge($array)
    {
        if($array instanceof HashMap) {
            $array = $array->toArray();
        }
        $this->map = array_merge($this->map, $array);
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->map;
    }

    /**
     * @param mixed $key
     * @return HashMap
     */
    public function remove($key)
    {
        foreach ($this->iterator as $k => $v) {
            if($v->getKey() == $key) {
                unset($this->iterator[$k]);
            }
        }
        unset($this->map[$key]);
        return $this;
    }

    /**
     * @param callable $callable
     * @return $this
     */
    public function map($callable)
    {
        $keys = array_keys($this->map);
        $this->map = array_combine($keys, array_map($callable, $this->map, $keys));
        return $this;
    }

    /**
     * @param callable $callable
     * @return HashMap
     */
    public function filter($callable)
    {
        $this->map = array_filter($this->map, $callable, ARRAY_FILTER_USE_BOTH);
        return $this;
    }

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
        $this->put($offset, $value);
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
     * @param string $name
     * @return mixed
     */
    function __get($name)
    {
        return $this->get($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    function __set($name, $value)
    {
        $this->put($name, $value);
    }

    /**
     * @param string $name
     * @return bool
     */
    function __isset($name)
    {
        return !empty($this->get($name));
    }

    /**
     * @param int $index
     * @return mixed
     */
    public function getValueByIndex($index)
    {
        $i = 0;
        foreach ($this->map as $val) {
            if($i == $index) {
                return $val;
            }
            $i++;
        }
        return null;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function contains($value)
    {
        return in_array($value, $this->map);
    }

    /**
     * @return ArrayList
     */
    public function getKeys()
    {
        return new ArrayList(array_keys($this->map));
    }

    /**
     * @return HashMap
     */
    public function flip()
    {
        $this->map = array_flip($this->map);
        return $this;
    }

    /**
     * Makes array values unique
     * @return HashMap
     */
    public function unify()
    {
        $this->map = array_map('unserialize', array_unique(array_map('serialize', $this->map)));
        return $this;
    }

    /**
     * @return ArrayList|KeyValue[]
     */
    public function getIterator()
    {
        return $this->iterator;
    }

    /**
     * @return Generator|KeyValue[]
     */
    public function getGenerator()
    {
        foreach ($this->getIterator() as $item) {
            yield $item;
        }
    }

    /**
     * @param mixed $search a value to search
     * @param callable|null $method a method to call on iterated value to compare with $search
     * @param bool $strict whether to use == or ===
     * @return mixed|null
     * @throws Exception
     */
    public function find($search, $method = null, $strict = false)
    {
        if(!empty($method)) {
            foreach ($this->map as $item) {
                if(!is_object($item)) {
                    throw new Exception('Cannot call method ' . $method . ' on non-object. ' . gettype($item) . ' given.');
                }
                if(!method_exists($item, $method)) {
                    throw new Exception('Object of class ' . get_class($item) . ' has no method ' . $method);
                }
                if($strict) {
                    if(call_user_func([$item, $method]) === $search) {
                        return $item;
                    }
                } else {
                    if(call_user_func([$item, $method]) == $search) {
                        return $item;
                    }
                }
            }
        } else {
            foreach ($this->map as $item) {
                if($strict) {
                    if($item === $search) {
                        return $item;
                    }
                } else {
                    if($item == $search) {
                        return $item;
                    }
                }
            }
        }
        return null;
    }

    /**
     * @return int size of structure
     */
    public function size()
    {
        return count($this->map);
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->iterator->current();
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->iterator->next();
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->iterator->key();
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return $this->iterator->valid();
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->iterator->rewind();
    }

    /**
     * @return static
     */
    public function clear()
    {
        $this->map = array();
        return $this;
    }
}