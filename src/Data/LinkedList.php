<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 3.6.2017
 * Time: 22:16
 */

namespace Zuffik\Structures\Data;


use Exception;

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
     */
    public function __construct()
    {
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
        return $offset < $this->size();
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @throws \Exception
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        foreach ($this as $i => $item) {
            if ($i == $offset) {
                return $item;
            }
        }
        throw new Exception("Index out of bounds ($offset requested; {$this->size()} limit)");
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
     * @throws \Exception
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        foreach ($this as $i => $item) {
            if ($i == $offset) {
                $item->setData($value);
                return;
            }
        }
        throw new Exception("Index out of bounds ($offset requested; {$this->size()} limit)");
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @throws \Exception
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    /**
     * @return int size of structure
     */
    public function size()
    {
        return $this->size;
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
            foreach ($this as $item) {
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
            foreach ($this as $item) {
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
        if(is_callable($callable)) {
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
        if(is_callable($callable)) {
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
        if(empty($this->current)) {
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
        if(empty($last)) {
            $this->first = $item;
            $this->last = $item;
        } else if($this->last === $this->first) {
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
     * @throws Exception
     */
    public function remove($index)
    {
        /** @var DataItem $item */
        $i = 0;
        $before = $this->first;
        if($index == 0) {
            $this->first = $this->first->getNext();
        } else if($index == $this->size() - 1) {
            if($this->size() == 1) {
                $this->first = $this->last;
                $this->first->setNext(null);
            } else {
                $item = $this->first;
                do {
                    $i++;
                    $item = $item->getNext();
                } while($i != $this->size() - 2);
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
            if($i == $index) {
                $before->setNext($item->getNext());
                unset($item);
                return;
            }
        } while($i != $this->size() - 1);
        throw new Exception("Index out of bounds ($index requested; {$this->size()} limit)");
    }
}