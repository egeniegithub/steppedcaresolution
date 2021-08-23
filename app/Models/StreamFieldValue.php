<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StreamFieldValue extends Model
{
    use HasFactory;

    protected $table = 'stream_field_values';

    protected $fillable = [
        'stream_id',
        'form_id',
        'user_id',
        'stream_field_id',
        'value',
    ];

    public function field()
    {
        return $this->belongsTo(StreamField::class,'stream_field_id');
    }
}
