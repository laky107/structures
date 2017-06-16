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
use Iterator;
use Zuffik\Structures\Serializable;
use Zuffik\Structures\SerializableChecker;

class ArrayList extends Structure implements Iterator
{
    use Serializable, SerializableChecker;

    /**
     * @var int
     */
    private $key = 0;

    /**
     * @var array
     */
    private $array;

    /**
     * ArrayList constructor.
     * @param array|BasicStructure|mixed $param
     * @throws Exception
     */
    public function __construct($param = [])
    {
        if(func_num_args() > 1) {
            $param = func_get_args();
        }
        if(!$this->isSerializable($param) && !is_array($param)) {
            throw new Exception(
                'Argument #1 of ' . get_class($this) . '::__construct must be an array or instance of serializable. ' .
                (is_object($param) ? 'Instance of ' . get_class($param) : gettype($param)) . ' given'
            );
        }
        $this->array = array_values($this->isSerializable($param) ? $param->toArray() : $param);
    }

    /**
     * @param mixed $search
     * @param string $method
     * @param bool $strict
     * @return mixed
     * @throws Exception
     */
    public function find($search, $method = null, $strict = false)
    {
        if(!empty($method)) {
            foreach ($this->array as $item) {
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
            foreach ($this->array as $item) {
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
     * @params mixed $values
     * @return ArrayList
     */
    public function add()
    {
        $this->array = array_values(array_merge($this->array, func_get_args()));
        return $this;
    }

    /**
     * @return mixed
     */
    public function pop()
    {
        $val = $this[0];
        unset($this[0]);
        $this->array = array_values($this->array);
        return $val;
    }

    /**
     * @param int $key
     * @return mixed
     * @throws Exception
     */
    public function get($key)
    {
        $count = $this->size();
        if($key >= $count) {
            throw new Exception("Index out of bounds (requested: $key, limit: $count)");
        }
        return $this->array[$key];
    }

    /**
     * @params mixed $values,...
     * @return ArrayList
     */
    public function addFirst()
    {
        $this->array = array_merge(func_get_args(), $this->array);
        return $this;
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->array[$this->key];
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->key++;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->key;
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
        return $this->keyExist($this->key);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->key = 0;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->array;
    }

    /**
     * @param Structure|array $structure
     * @return Structure
     * @throws Exception
     */
    public function merge($structure)
    {
        if($this->isSerializable($structure)) {
            $structure = $structure->toArray();
        }
        if(!is_array($structure)) {
            throw new Exception(
                'Argument #1 of ' . get_class($this) . '::mergeWith must be an array or instance of serializable. ' .
                (is_object($structure) ? 'Instance of ' . get_class($structure) : gettype($structure)) . ' given'
            );
        }
        $this->array = array_merge($this->array, $structure);
        return $this;
    }

    /**
     * @return int
     */
    public function size()
    {
        return count($this->array);
    }

    /**
     * @param int $key
     * @return ArrayList
     */
    public function delete($key)
    {
        unset($this->array[$key]);
        $this->array = array_values($this->array);
        return $this;
    }

    /**
     * @param mixed $value
     * @param bool $stopAtFirst
     * @return ArrayList
     */
    public function deleteByValue($value, $stopAtFirst = true)
    {
        foreach ($this->array as $k => $v) {
            if($v == $value) {
                unset($this->array[$k]);
                if($stopAtFirst) {
                    break;
                }
            }
        }
        $this->array = array_values($this->array);
        return $this;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function contains($value)
    {
        foreach ($this->array as $k => $v) {
            if($v == $value) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param int $key
     * @return bool
     */
    public function keyExist($key)
    {
        return array_key_exists($key, $this->array);
    }

    /**
     * @param int $start
     * @param int $length
     * @return ArrayList
     */
    public function slice($start = 0, $length = null)
    {
        $this->array = array_values(array_slice($this->array, intval($start), $length));
        return $this;
    }

    /**
     * @return ArrayList
     * @deprecated use clear
     */
    public function emptyArray()
    {
        return $this->clear();
    }

    /**
     * Makes array values unique
     * @return ArrayList
     */
    public function unify()
    {
        $this->array = array_values(array_map('unserialize', array_unique(array_map('serialize', $this->array))));
        return $this;
    }

    /**
     * @param callable|string $callable
     * @return ArrayList
     */
    public function sort($callable)
    {
        usort($this->array, $callable);
        $this->array = array_values($this->array);
        return $this;
    }

    /**
     * @param callable|string $callable
     * @return ArrayList
     */
    public function filter($callable)
    {
        $this->array = array_values(array_filter($this->array, $callable, ARRAY_FILTER_USE_BOTH));
        return $this;
    }

    /**
     * @param callable $callable
     * @return ArrayList
     */
    public function map($callable)
    {
        $this->array = array_values(array_map($callable, $this->array, array_keys($this->array)));
        return $this;
    }

    /**
     * @param int $index
     * @return ArrayList
     */
    public function remove($index)
    {
        unset($this->array[$index]);
        $this->array = array_values($this->array);
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
        $this->array[intval($offset)] = $value;
        $this->array = array_values($this->array);
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
     * @param string $glue
     * @return string
     */
    public function join($glue = '')
    {
        return $this->joinLevel($glue, $this->array);
    }

    /**
     * @param string $glue
     * @param array $level
     * @return string
     */
    private function joinLevel($glue, $level)
    {
        foreach ($level as $i => $item) {
            if(is_array($item)) {
                $level[$i] = $this->joinLevel($glue, $item);
            } else if(is_object($item) && !$this->isSerializable($item)) {
                $level[$i] = get_class($item);
            }
        }
        return implode($glue, $level);
    }

    /**
     * @return float
     */
    public function sum()
    {
        return array_sum($this->array);
    }

    /**
     * @return float
     */
    public function min()
    {
        return min($this->array);
    }

    /**
     * @return float
     */
    public function max()
    {
        return max($this->array);
    }

    /**
     * @return ArrayList
     */
    public function reverse()
    {
        $this->array = array_values(array_reverse($this->array));
        return $this;
    }

    /**
     * @return Generator
     */
    public function getGenerator()
    {
        foreach ($this->array as $item) {
            yield $item;
        }
    }

    /**
     * @param array|ArrayList $array
     * @return ArrayList
     */
    public function diff($array)
    {
        $this->array = array_values(array_diff($this->array, (array) $array));
        return $this;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getLastItem()
    {
        return $this[$this->size() - 1];
    }

    /**
     * @return static
     */
    public function clear()
    {
        $this->array = array();
        return $this;
    }
}
