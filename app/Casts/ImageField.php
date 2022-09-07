<?php

namespace App\Casts;

use App\Lib\Image;
use App\Traits\DeletesImage;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class ImageField implements CastsAttributes, Castable
{
    protected $path;
    protected $fallbackImage;

    public function __construct(string $path = null, string $fallbackImage = null)
    {
        $this->path = $path ?? 'images';
        $this->fallbackImage = $fallbackImage ?? 'images/no-image.png';
    }

    /**
     * Cast the given value.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        throw_unless(
            in_array(DeletesImage::class, class_uses($model)),
            new \Exception('Model ' . get_class($model) . ' is not using DeletesImage trait. use it in order to delete image while deleting model')
        );
        
        return $value ? Image::url($value) : asset($this->fallbackImage);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        throw_unless(
            in_array(DeletesImage::class, class_uses($model)),
            new \Exception('Model ' . get_class($model) . ' is not using DeletesImage trait. use it in order to delete image while deleting model')
        );
        
        if (isset($attributes[$key]) && $attributes[$key]) {
            Image::delete($attributes[$key]);
        }
        return Image::storeFile($value, $this->path);
    }

    public static function castUsing(array $arguments)
    {
        return new self($arguments[0] ?? null, $arguments[1] ?? null);
    }
}
