<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class User extends Model implements Authenticatable
{
    use HasFactory, SoftDeletes, AuthenticableTrait;

    protected $table = 'users';
  
    protected $fillable = [
        'name',
        'email',
        'role_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
