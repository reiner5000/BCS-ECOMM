<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'provinces';

    protected $fillable = ['country_id', 'province_id', 'province'];

    // Relation to country
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'country_id');
    }

    // Relation to cities
    public function cities()
    {
        return $this->hasMany(City::class, 'province_id', 'province_id');
    }
}
 