<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryDetails extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'category_details';

    protected $fillable = [
        'name',
        'category_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
