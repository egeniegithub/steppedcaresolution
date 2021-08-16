<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StreamField extends Model
{
    use HasFactory;
    protected $table = 'stream_fields';

    protected $fillable = [
        'stream_id',
        'form_id',
        'user_id',
        'isRequired',
        'fieldName',
        'fieldType',
        'isDuplicate',
        'isCumulative',
        'orderCount',
        'fieldOptions',
        'tableData'
    ];
}
