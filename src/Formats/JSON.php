<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 10.12.2016
 * Time: 23:05
 */

namespace Zuffik\Structures\Formats;


use ArrayAccess;
use Zuffik\Structures\Data\BasicStructure;
use Zuffik\Structures\Convertors\ArraySerializableConvertor;
use Zuffik\Structures\Serializable;

class JSON implements ArrayAccess
{
    use Serializable;
    /** @var BasicStructure */
    private $array;

    /**
     * JSON constructor.
     * @param array|BasicStructure|string|JSON $json
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
        } else if($json instanceof BasicStructure) {
            $this->array = $json;
        } else if($json instanceof JSON) {
            $this->array = $json->array;
        } else {
            $this->array = ArraySerializableConvertor::toSerializable($json);
        }
    }

    /**
     * @return BasicStructure
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
}