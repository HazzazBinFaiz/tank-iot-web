<?php

namespace App\Lib;

use BenSampo\Enum\Enum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Field
{
    public $type;
    public $name;
    public $valueAccessor;
    public $required = false;
    public $options = [];

    public function __construct($type, $name)
    {
        $this->type = $type;
        $this->name = $name;
    }

    public static function text($name)
    {
        return new self('text', $name);
    }

    public static function select($name)
    {
        return new self('select', $name);
    }

    public function required($required = true)
    {
        $this->required = $required;
        return $this;
    }

    public function valueAccessor($valueAccessor)
    {
        $this->valueAccessor = $valueAccessor;
        return $this;
    }

    public function options($options, $callable = null)
    {
        if ($callable instanceof \Closure) {
            $this->options = $callable($options);
        } else {
            $options = $options instanceof Collection ? $options : collect($options);
            $this->options = $options->mapWithKeys(function ($item, $key) use ($callable) {
                if ($item instanceof Enum) {
                    return [$item->value => $item->key];
                } else if ($item instanceof Model) {
                    return [$item->getKey() => is_string($callable) ? $item->{$callable} : $item->name];
                }
                return [value($item) => $key];
            });
        }
        return $this;
    }

    public function getValueAccessor()
    {
        return $this->valueAccessor ?? $this->name;
    }
}
