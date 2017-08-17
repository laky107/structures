<?php
/**
 * Created by PhpStorm.
 * User: zuffik
 * Date: 12.2.2017
 * Time: 18:29
 */

namespace Zuffik\Structures\Types;


class Str
{
    /**
     * @var string
     */
    private $string;

    /**
     * Str constructor.
     * @param string|Str|null $string
     */
    public function __construct($string = '')
    {
        $this->string = (string) $string;
    }

    /**
     * @param string|Str $search
     * @param string|Str $replace
     * @return Str
     */
    public function replace($search, $replace)
    {
        $this->string = str_replace($search, $replace, $this->string);
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->string;
    }

    /**
     * @return Str
     */
    public function toUppercase()
    {
        $this->string = strtoupper($this->string);
        return $this;
    }

    /**
     * @return Str
     */
    public function toLowercase()
    {
        $this->string = strtolower($this->string);
        return $this;
    }

    /**
     * @return Str
     */
    public function capitalize()
    {
        $this->string = ucfirst($this->string);
        return $this;
    }

    /**
     * @return Str
     */
    public function lowerFirst()
    {
        $this->string = lcfirst($this->string);
        return $this;
    }

    /**
     * @param int $start
     * @param int $length
     * @return Str
     */
    public function substring($start = 0, $length = null)
    {
        $this->string = substr($this->string, $start, $length);
        return $this;
    }

    /**
     * @param Str|string $string
     * @return bool
     */
    public function contains($string)
    {
        return strpos($this->string, (string) $string) !== false;
    }

    /**
     * @param string $charlist
     * @return Str
     */
    public function trim($charlist = " \t\n\r\0\x0B")
    {
        $this->string = trim($this->string, $charlist);
        return $this;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->string) || ((string) $this->trim()) == '';
    }

    /**
     * @return Str
     */
    public function slug()
    {
        $this->string = strtolower(
            preg_replace(
                '~-+~',
                '-',
                trim(
                    preg_replace(
                        '~[^-\w]+~',
                        '',
                        iconv(
                            'utf-8',
                            'us-ascii//TRANSLIT',
                            preg_replace(
                                '~[^\pL\d]+~u',
                                '-',
                                $this->string
                            )
                        )
                    ),
                    '-'
                )
            )
        );
        if (empty($this->string)) {
            $this->string = 'n-a';
        }
        return $this;
    }

    /**
     * @param Integer|int $length
     * @param string|Str $string
     * @param Integer|int $side
     * @return $this
     */
    public function pad($length, $string = '0', $side = STR_PAD_LEFT)
    {
        $this->string = str_pad(
            intval((string) $this->string),
            intval((string) $length),
            $string,
            intval((string) $side)
        );
        return $this;
    }
}