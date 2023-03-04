<?php

namespace App\Lib;

class Column
{
    public $name;
    protected $value;
    public $dataOrder = true;
    public $dataSearch = true;
    public $label = null;
    public $visible = true;

    public function __construct($name, $value = null)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public static function make($name, $value = null)
    {
        return new self($name, $value);
    }

    public function getValue($model)
    {
        if ($this->value) {
            return is_callable($this->value) ? call_user_func($this->value, $model) : $this->value;
        }
        return $model->{$this->name};
    }

    public function label($label)
    {
        $this->label = $label;
        return $this;
    }

    public function dataSearch($search = true)
    {
        $this->dataSearch = $search;
        return $this;
    }

    public function dataOrder($order = true)
    {
        $this->dataOrder = $order;
        return $this;
    }

    public function visible($visible)
    {
        $this->visible = $visible;
        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
