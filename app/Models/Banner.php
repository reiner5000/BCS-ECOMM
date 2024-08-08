<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'banner';

    protected $fillable = [
        'cover',
        'link',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}