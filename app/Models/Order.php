<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'order';

    protected $fillable = [
        'no_resi',
        'no_invoice',
        'date',
        'total',
        'shipment_fee',
        'customer_id',
        'shipment_id',
        'payment_id',
        'voucher_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id')->withTrashed();
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
