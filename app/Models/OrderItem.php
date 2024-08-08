<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'order_item';

    protected $fillable = [
        'quantity',
        'for_competition',
        'competition_fee',
        'choir_id',
        'order_id',
        'partitur_id',
        'merchandise_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function choir()
    {
        return $this->belongsTo(Choir::class, 'choir_id')->withTrashed();
    }

    public function partiturDetail()
    {
        return $this->belongsTo(PartiturDetail::class, 'partitur_id');
    }

    public function merchandise()
    {
        return $this->belongsTo(Merchandise::class, 'merchandise_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
