<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'collection';

    protected $fillable = [
        'name',
        'short_description',
        'cover',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function partiturs()
    {
        return $this->hasMany(Partitur::class, 'collection_id');
    }

}