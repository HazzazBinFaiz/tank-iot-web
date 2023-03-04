<?php

namespace App\Lib;

class Column
{
    public $name;
    public $value;
    public $visible = true;
    public $dataOrder = true;
    public $dataSearch = true;

    public function __construct($name, $value = null)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public static function text($name, $value = null)
    {
        return new self($name, $value);
    }

    public function setVisible($visible = true)
    {
        $this->visible = $visible;
        return $this;
    }

    public function dataOrder($order) {
        $this->dataOrder = $order;
        return $this;
    }

    public function dataSearch($search) {
        $this->dataSearch = $search;
        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
