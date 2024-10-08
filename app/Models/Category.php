<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'category';

    protected $fillable = [
        'name',
        'type',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function details()
    {
        return $this->hasMany(CategoryDetails::class, 'category_id', 'id');
    }
}
