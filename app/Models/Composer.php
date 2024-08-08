<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Composer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'composer';

    protected $fillable = [
        'name',
        'profile_desc',
        'instagram',
        'twitter',
        'facebook',
        'asal_negara',
        'photo_profile',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function partiturs()
    {
        return $this->hasMany(Partitur::class, 'composer_id');
    }
}