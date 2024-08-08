<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';

    protected $fillable = ['country_id', 'country_name'];

    // Relation to provinces
    public function provinces()
    {
        return $this->hasMany(Province::class, 'country_id', 'country_id');
    }
}
