<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MerchandiseDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'merchandise_detail';

    protected $fillable = [
        'merchandise_id',
        'size',
        'color',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function merchandise()
    {
        return $this->belongsTo(Merchandise::class, 'merchandise_id');
    }
}
