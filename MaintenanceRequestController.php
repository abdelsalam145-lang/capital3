<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestAttachment extends Model
{
    protected $fillable = [
        'maintenance_request_id', 'uploaded_by', 'path',
        'original_name', 'mime_type', 'size',
    ];
}
