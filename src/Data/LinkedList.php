<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 3.6.2017
 * Time: 22:16
 */

namespace Zuffik\Structures\Data;


use Exception;
use Zuffik\Structures\Helpers\Finder;
use Zuffik\Structures\Helpers\RecursiveGetter;

class LinkedList extends Structure
{
    /**
     * @var DataItem
     */
    private $first;
    /**
     * @var DataItem
     */
    private $last;
    /**
     * @var DataItem
     */
    private $current;
    /**
     * @var int
     */
    private $size;
    /**
     * @var int
     */
    private $key = 0;

    /**
     * LinkedList constructor.
     * @param array|BasicStructure $param
     */
    public function __construct($param = [])
    {
        foreach (array_values($param) as $value) {
            $this->add($value);
        }
        $this->size = 0;
        $this->current = $this->first = $this->last = null;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = [];
        /** @var DataItem $item */
        foreach ($this as $item) {
            $result[] = $item;
        }
        return $result;
    }

    /**
     * @return int size of structure
     */
    public function size()
    {
        return $this->size;
    }

    /**
     * @param Structure|array $structure
     * @return static
     */
    public function merge($structure)
    {
        foreach ($structure as $item) {
            $this->add($item);
        }
        return $this;
    }

    /**
     * @param callable $callable
     * @return static
     */
    public function map($callable)
    {
        if (is_callable($callable)) {
            $list = new LinkedList();
            /** @var DataItem $item */
            foreach ($this as $i => $item) {
                $res = call_user_func($callable, $item, $i);
                $list->add($res);
            }
            $this->clear();
            $this->merge($list);
        }
        return $this;
    }

    /**
     * @param callable $callable
     * @return static
     */
    public function filter($callable)
    {
        if (is_callable($callable)) {
            $list = new LinkedList();
            /** @var DataItem $item */
            foreach ($this as $i => $item) {
                if (call_user_func($callable, $item, $i)) {
                    $list->add($item);
                }
            }
            $this->clear();
            $this->merge($list);
        }
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
        if (empty($this->current)) {
            $this->current = $this->first;
        }
        return $this->current->getData();
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->current = $this->current->getNext();
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
        return !empty($this->current);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->current = $this->first;
        $this->key = 0;
    }

    /**
     * @return static
     */
    public function clear()
    {
        $this->first = $this->last = $this->current = null;
        foreach ($this as $item) {
            unset($item);
        }
        return $this;
    }

    /**
     * @param mixed $item
     */
    public function add($item)
    {
        $item = new DataItem($item);
        $last = $this->last;
        if (empty($last)) {
            $this->first = $item;
            $this->last = $item;
        } else if ($this->last === $this->first) {
            $this->last = $item;
            $this->first->setNext($this->last);
        } else {
            $last->setNext($item);
            $this->last = $item;
        }
        $this->size++;
    }

    /**
     * @param int $index
     * @return static
     * @throws Exception
     */
    public function remove($index)
    {
        /** @var DataItem $item */
        $i = 0;
        $before = $this->first;
        if ($index == 0) {
            $this->first = $this->first->getNext();
        } else if ($index == $this->size() - 1) {
            if ($this->size() == 1) {
                $this->first = $this->last;
                $this->first->setNext(null);
            } else {
                $item = $this->first;
                do {
                    $i++;
                    $item = $item->getNext();
                } while ($i != $this->size() - 2);
                $item->setNext(null);
                unset($this->last);
                $this->last = $item;
            }
        }
        $i = 0;
        $item = $this->first;
        $before = null;
        do {
            $i++;
            $before = $item;
            $item = $item->getNext();
            if ($i == $index) {
                $before->setNext($item->getNext());
                unset($item);
                return $this;
            }
        } while ($i != $this->size() - 1);
        throw new Exception("Index out of bounds ($index requested; {$this->size()} limit)");
    }

    /**
     * @param callable|string $callable
     * @return static
     */
    public function sort($callable)
    {
        foreach ($this as $i1 => $v1) {
            foreach ($this as $i2 => $v2) {
                $sort = call_user_func($callable, $v1, $v2);
                if ($sort > 0) {
                    $this->swap($i1, $i2);
                }
            }
        }
        return $this;
    }

    /**
     * @param string|int $key
     * @return mixed
     * @throws Exception
     */
    public function get($key)
    {
        foreach ($this as $i => $v) {
            if ($i == $key) {
                return $v;
            }
        }
        throw new Exception("Index out of bounds (size: {$this->size()}, requested: $key)");
    }

    /**
     * @param int|string $key
     * @param int|string $value
     * @return static
     * @throws Exception
     */
    public function set($key, $value)
    {
        if ($this->size() == $key) {
            $this->add($value);
        } else if ($this->size() > $key && $key >= 0) {
            foreach ($this as $i => $item) {
                if ($i == $key) {
                    $item->setData($value);
                    return $this;
                }
            }
        }
        throw new Exception("Index out of bounds (size: {$this->size()}, requested: $key)");
    }
}