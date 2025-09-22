<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorReading extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'timestamp',
        'distance_cm',
        'water_percent',
        'air_temp',
        'air_humidity',
        'water_temp',
        'tds',
        'pump_on',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'pump_on' => 'boolean',
    ];

    /**
     * Get the user that owns the sensor reading.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
