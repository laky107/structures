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
use Zuffik\Structures\Helpers\Finder;
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
        if (!$this->isSerializable($map) && !is_array($map)) {
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
        if (!isset($this->map[$key])) {
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
        if ($array instanceof HashMap) {
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
            if ($v->getKey() == $key) {
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
            if ($i == $index) {
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

    /**
     * @return ArrayList
     */
    public function getValues()
    {
        return arrayList(array_values($this->map));
    }

    /**
     * @param callable|string $callable
     * @return static
     */
    public function sort($callable)
    {
        foreach ($this->map as $i1 => $v1) {
            foreach ($this->map as $i2 => $v2) {
                $res = call_user_func($callable, $v1, $v2, $i1, $i2);
                if($res > 0) {
                    $this->swap($i1, $i2);
                }
            }
        }
        return $this;
    }

    /**
     * @param int|string $key
     * @param int|string $value
     * @return static
     */
    public function set($key, $value)
    {
        $this->map[$key] = $value;
        return $this;
    }
}