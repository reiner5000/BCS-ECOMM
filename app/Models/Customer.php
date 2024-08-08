<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class Customer extends Model implements Authenticatable
{
    use HasFactory, SoftDeletes, AuthenticableTrait;

    protected $table = 'customer';

    protected $fillable = [
        'name',
        'gender',
        'phone_number',
        'email',
        'password',
        'photo_profile',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}