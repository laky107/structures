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
use Zuffik\Structures\Helpers\Finder;
use Zuffik\Structures\Helpers\RecursiveGetter;
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
        if (func_num_args() > 1) {
            $param = func_get_args();
        }
        if (!$this->isSerializable($param) && !is_array($param)) {
            throw new Exception(
                'Argument #1 of ' . get_class($this) . '::__construct must be an array or instance of serializable. ' .
                (is_object($param) ? 'Instance of ' . get_class($param) : gettype($param)) . ' given'
            );
        }
        $this->array = array_values($this->isSerializable($param) ? $param->toArray() : $param);
    }

    /**
     * @param int|string $key
     * @param int|string $value
     * @return ArrayList
     */
    public function set($key, $value)
    {
        $this->array[intval($key)] = $value;
        $this->array = array_values($this->array);
        return $this;
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
        if ($key >= $count) {
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
        if ($this->isSerializable($structure)) {
            $structure = $structure->toArray();
        }
        if (!is_array($structure)) {
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
            if ($v == $value) {
                unset($this->array[$k]);
                if ($stopAtFirst) {
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
            if ($v == $value) {
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
     * @param string[] $criteria
     * @return ArrayList
     */
    public function multiSort($criteria = [])
    {
        return $this->sort(function ($v1, $v2) use ($criteria) {
            foreach ($criteria as $what => $order) {
                $val1 = RecursiveGetter::get($v1, $what);
                $val2 = RecursiveGetter::get($v2, $what);
                if ($val1 == $val2) {
                    continue;
                }
                return ($order == 'desc' ? -1 : 1) * strcmp($val1, $val2);
            }
            return 0;
        });
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
            if (is_array($item)) {
                $level[$i] = $this->joinLevel($glue, $item);
            } else if (is_object($item) && !$this->isSerializable($item)) {
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
        $this->array = array_values(array_diff($this->array, (array)$array));
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

    /**
     * @param callable|callable[]|string|string[] $criteria
     * @return ArrayList
     */
    public function categorize($criteria)
    {
        if(!is_array($criteria)) {
            $criteria = [$criteria];
        }
        $result = [];
        foreach ($criteria as $criterion) {
            foreach ($this->array as $k => $item) {
                if(is_callable($criterion)) {
                    $key = call_user_func($criterion, $item, $k);
                } else if(is_object($item) && method_exists($item, $criterion)) {
                    $key = call_user_func([$item, $criterion]);
                } else if(is_array($item) || $item instanceof \ArrayAccess) {
                    $key = $item[$criterion];
                } else {
                    throw new \InvalidArgumentException('Categorize by unknown way.');
                }
                if(empty($result[$key])) {
                    $result[$key] = [];
                }
                $result[$key][] = $item;
            }
        }
        return hashMap($result);
    }
}
