<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StreamChangeLog extends Model
{
    use HasFactory;

    protected $table = 'stream_change_logs';

    protected $fillable = [
        'stream_id',
        'user_id',
        'old_data',
        'new_data'
    ];

}
