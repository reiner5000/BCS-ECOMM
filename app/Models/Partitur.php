<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partitur extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'partitur';

    protected $fillable = [
        'name',
        'deskripsi',
        'collection_id',
        'composer_id',
        'description',
        'file_image',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function collection()
    {
        return $this->belongsTo(Collection::class, 'collection_id');
    }

    public function composer()
    {
        return $this->belongsTo(Composer::class, 'composer_id');
    }

    public function details()
    {
        return $this->hasMany(PartiturDetail::class);
    }

    public function getFileImageFirstAttribute()
    {
        $images = explode(',', $this->file_image);
        return count($images) ? $images[0] : null;
    }

}