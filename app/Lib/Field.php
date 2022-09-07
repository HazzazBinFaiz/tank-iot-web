<?php

namespace App\Lib;

use BenSampo\Enum\Enum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @method static Field select($name)
 * @method static Field text($name)
 * @method static Field number($name)
 * @method static Field date($name)
 * @method static Field file($name)
 */

class Field
{
    public $type;
    public $name;
    public $value;
    public $visible = true;
    public $valueAccessor;
    public $required = false;
    public $options = [];
    public $extraAttributes = [];
    public $class = '';

    public function __construct($type, $name)
    {
        $this->type = $type;
        $this->name = $name;
    }

    public static function __callStatic($name, $arguments)
    {
        throw_unless(
            count($arguments) === 1,
            new \Exception('At least one argument should be passed in static call')
        );
        return new self($name, $arguments[0]);
    }

    public function __call($name, $arguments) : self
    {
        throw_unless(
            count($arguments) === 1,
            new \Exception('At least one argument should be passed in static call')
        );
        $this->extraAttributes[Str::kebab($name)] = $arguments[0];
        return $this;
    }

    public function required($required = true)
    {
        $this->required = $required;
        return $this;
    }

    public function value($value)
    {
        $this->value = $value;
        return $this;
    }

    public function valueAccessor($valueAccessor)
    {
        $this->valueAccessor = $valueAccessor;
        return $this;
    }

    public function options($options, $callable = null)
    {
        $options = $options instanceof Collection ? $options : collect($options);
        $this->options = $options->mapWithKeys($callable ?? function ($item, $key) use ($callable) {
            if ($item instanceof Enum) {
                return [$item->value => $item->key];
            } else if ($item instanceof Model) {
                return [$item->getKey() => is_string($callable) ? $item->{$callable} : $item->name];
            }
            return [$key => $item];
        });
        return $this;
    }

    public function getValueAccessor()
    {
        return $this->valueAccessor ?? $this->name;
    }

    public function visible($visible)
    {
        $this->visible = value($visible);
        return $this;
    }

    public function class($class)
    {
        $this->class .= value($class).' ';
        return $this;
    }

    public function hide($condition)
    {
        if (value($condition)) {
            $this->class('hidden');
        }
        return $this;
    }
}
