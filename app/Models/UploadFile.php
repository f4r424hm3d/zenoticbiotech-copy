<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadFile extends Model
{
    protected $fillable = [
        'title',
        'file_name',
        'file_path',
    ];
}
