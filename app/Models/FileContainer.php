<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileContainer extends Model
{
    use HasFactory;

    protected $fillable = [
        'upload_path',
        'object_type_id',
        'object_id',
    ];

    public function objectType()
    {
        return $this->belongsTo(ObjectType::class);
    }
}
