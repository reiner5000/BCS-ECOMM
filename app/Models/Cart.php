<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cart';

    protected $fillable = [
        'customer_id',
        'partiturdet_id',
        'merchandise_id',
        'choir_id',
        'competition',
        'qty',
        'subtotal',
        'size',
        'color',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function partiturdet()
    {
        return $this->belongsTo(PartiturDetail::class,'partiturdet_id');
    }

    public function merchandise()
    {
        return $this->belongsTo(Merchandise::class,'merchandise_id');
    }


}