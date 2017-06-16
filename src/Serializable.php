<?php
/**
 * Created by PhpStorm.
 * UserModule: zuffik
 * Date: 7.7.2016
 * Time: 17:52
 */

namespace Zuffik\Structures;


trait Serializable
{
    /**
     * @return array
     */
    public abstract function toArray();

    /**
     * @return array
     */
    function __debugInfo()
    {
        return array_merge(
            [
                'ptr' => spl_object_hash($this)
            ],
            $this->toArray()
        );
    }

}