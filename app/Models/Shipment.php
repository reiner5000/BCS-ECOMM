<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'shipment';

    protected $fillable = [
        'nama_penerima',
        'phone_number',
        'negara',
        'provinsi',
        'kota',
        'kecamatan',
        'kode_pos',
        'informasi_tambahan',
        'detail_informasi_tambahan',
        'is_default',
        'customer_id',
        'order_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
