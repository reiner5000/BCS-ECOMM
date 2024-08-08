<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdistrict extends Model
{
    use HasFactory;

    protected $fillable = ['city_id', 'subdistrict_name'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
