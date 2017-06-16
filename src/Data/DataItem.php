<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 3.6.2017
 * Time: 22:16
 */

namespace Zuffik\Data\Structures;


use Zuffik\Structures\Serializable;

class DataItem
{
    use Serializable;
    /**
     * @var mixed
     */
    private $data;
    /**
     * @var DataItem
     */
    private $next;

    /**
     * DataItem constructor.
     * @param mixed $data
     * @param DataItem $next
     */
    public function __construct($data, DataItem $next = null)
    {
        $this->data = $data;
        $this->next = $next;
    }

    /**
     * @param DataItem $next
     * @return DataItem
     */
    public function setNext($next)
    {
        $this->next = $next;
        return $this;
    }

    /**
     * @param mixed $data
     * @return DataItem
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return DataItem
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'data' => $this->data,
            'next' => $this->next
        ];
    }
}