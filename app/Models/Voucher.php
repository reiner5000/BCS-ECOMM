<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'voucher';

    protected $fillable = [
        'name',
        'potongan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
