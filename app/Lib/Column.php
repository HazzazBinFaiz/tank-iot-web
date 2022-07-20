<?php

namespace App\Lib;

class Column
{
    public $name;
    public $value;

    public function __construct($name, $value = null)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public static function text($name, $value = null)
    {
        return new self($name, $value);
    }

    public function __toString()
    {
        return $this->name;
    }
}
