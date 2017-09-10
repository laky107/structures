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
     * @param string $name
     * @param array ...$args
     * @return mixed
     */
    private function callFunc($name, ...$args)
    {
        return function_exists("mb_$name") ? call_user_func_array("mb_$name", $args) : call_user_func_array($name, $args);
    }

    /**
     * @param string|Str $search
     * @param string|Str $replace
     * @return Str
     */
    public function replace($search, $replace)
    {
        $this->string = $this->callFunc('str_replace', $search, $replace, $this->string);
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
        $this->string = $this->callFunc('strtoupper', $this->string);
        return $this;
    }

    /**
     * @return Str
     */
    public function toLowercase()
    {
        $this->string = $this->callFunc('strtolower', $this->string);
        return $this;
    }

    /**
     * @return Str
     */
    public function capitalize()
    {
        $this->string = $this->callFunc('ucfirst', $this->string);
        return $this;
    }

    /**
     * @return Str
     */
    public function lowerFirst()
    {
        $this->string = $this->callFunc('lcfirst', $this->string);
        return $this;
    }

    /**
     * @param int $start
     * @param int $length
     * @return Str
     */
    public function substring($start = 0, $length = null)
    {
        $this->string = $this->callFunc('substr', $this->string, $start, $length);
        return $this;
    }

    /**
     * @param Str|string $string
     * @return bool
     */
    public function contains($string)
    {
        return $this->callFunc('strpos', $this->string, (string) $string) !== false;
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

    /**
     * @return Str
     */
    public function upperCamelCase()
    {
        $delimiters = " \t\r\n\f\v_";
        $this->string = preg_replace("/[$delimiters]/", '', ucwords($this->string, $delimiters));
        return $this;
    }

    /**
     * @return Str
     */
    public function lowerCamelCase()
    {
        $this->string = lcfirst($this->upperCamelCase());
        return $this;
    }

    /**
     * @return Str
     * @see Str::lowerCamelCase()
     */
    public function camelCase()
    {
        return $this->lowerCamelCase();
    }

    /**
     * @param string[] $args
     * @return string[]
     */
    private function getSprintfArgs($args)
    {
        if(count($args) > 1 || !is_array($args[0])) {
            $args = $args[0];
        }
        return $args;
    }

    /**
     * Format string
     *
     * @see sprintf()
     * @param array $args
     * @return Str
     * @throws \Exception
     */
    public function format(...$args)
    {
        $args = $this->getSprintfArgs($args);
        $expected = count($args);
        $real = substr_count($this->string, '%s');
        if($expected != $real) {
            throw new \Exception("Method Str::format expects $expected exactly arguments. $real given.");
        }
        $this->string = vsprintf($this->string, $args);
        return $this;
    }

    /**
     * Returns NEW formatted string string
     *
     * @see sprintf()
     * @param array $args
     * @return Str
     * @throws \Exception
     */
    public function formatNew(...$args)
    {
        return string($this)->format($args);
    }

    /**
     * @param string|Str $string
     * @return Str
     */
    public function setValue($string)
    {
        $this->string = $string;
        return $this;
    }
}