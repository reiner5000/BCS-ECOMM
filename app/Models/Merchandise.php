<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Merchandise extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'merchandise';

    protected $fillable = [
        'name',
        'deskripsi',
        'harga',
        'stok',
        'category_detail_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function details()
    {
        return $this->hasMany(MerchandiseDetail::class,  'merchandise_id');
    }

    public function categoryDetail()
    {
        return $this->belongsTo(CategoryDetails::class, 'category_detail_id');
    }
}
