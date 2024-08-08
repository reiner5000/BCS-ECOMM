<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';

    protected $fillable = ['province_id', 'city_id', 'city_name', 'postal_code'];

    // Relation to province
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'province_id');
    }
}
