<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 10.12.2016
 * Time: 23:05
 */

namespace Zuffik\Structures\Formats;


use ArrayAccess;
use Zuffik\Structures\Data\Structure;
use Zuffik\Structures\Convertors\ArraySerializableConvertor;
use Zuffik\Structures\Serializable;

class JSON implements ArrayAccess, \Iterator
{
    use Serializable;
    /** @var Structure */
    private $array;

    /**
     * JSON constructor.
     * @param array|Structure|string|JSON $json
     * @throws \Exception
     */
    public function __construct($json)
    {
        if(is_string($json)) {
            $decoded = json_decode($json, true);
            if($decoded === null) {
                throw new \InvalidArgumentException("Empty or corrupted JSON string ($json)");
            }
            $this->array = ArraySerializableConvertor::toSerializable($decoded);
        } else if($json instanceof Structure) {
            $this->array = $json;
        } else if($json instanceof JSON) {
            $this->array = $json->array;
        } else {
            $this->array = ArraySerializableConvertor::toSerializable($json);
        }
    }

    /**
     * @return Structure
     */
    public function getArray()
    {
        return $this->array;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->array->toArray();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode(ArraySerializableConvertor::toArray($this->array));
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
        return $this->array[$offset];
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
        $this->array[$offset] = $value;
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
        unset($this->array[$offset]);
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->array->current();
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->array->next();
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->array->key();
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
        return $this->array->valid();
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->array->valid();
    }
}