<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Choir extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'choir';

    protected $fillable = [
        'name',
        'address',
        'conductor',
        'is_default',
        'customer_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
