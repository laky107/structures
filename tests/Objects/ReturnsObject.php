<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 4.10.2017
 * Time: 12:07
 */

namespace Zuffik\Test\Structures\Objects;


class ReturnsObject
{
    private $obj;

    /**
     * ReturnsObject constructor.
     * @param $obj
     */
    public function __construct($obj)
    {
        $this->obj = new class($obj) {
            private $obj;

            public function __construct($obj)
            {
                $this->obj = $obj;
            }

            /**
             * @return mixed
             */
            public function getObject()
            {
                return $this->obj;
            }
        };
    }

    /**
     * @return mixed
     */
    public function getObj()
    {
        return $this->obj;
    }
}