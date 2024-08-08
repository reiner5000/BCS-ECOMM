<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartiturDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'partitur_detail';

    protected $fillable = [
        'name',
        'file_type',
        'deskripsi',
        'harga',
        'minimum_order',
        'preview_audio',
        'preview_video',
        'preview_partitur',
        'partitur_ori',
        'partitur_id',
        'category_detail_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function partitur()
    {
        return $this->belongsTo(Partitur::class, 'partitur_id');
    }

    public function categoryDetail()
    {
        return $this->belongsTo(CategoryDetails::class, 'category_detail_id');
    }

    public function details()
    {
        return $this->hasMany(PartiturDetail::class, 'partitur_id');
    }
}
