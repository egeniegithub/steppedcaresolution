<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Graph extends Model
{
    use HasFactory;

    protected $table = 'graphs';

    protected $fillable = [
        'start_period_id',
        'stream_id',
        'project_id',
        'form_id',
        'is_cumulative',
        'field_id'
    ];

    public function stream()
    {
        return $this->belongsTo(Stream::class,'stream_id');
    }

    public function project()
    {
        return $this->belongsTo(project::class,'project_id');
    }

    public function form()
    {
        return $this->belongsTo(Form::class,'form_id');
    }

    public function period()
    {
        return $this->belongsTo(Period::class,'start_period_id');
    }

    public function field()
    {
        return $this->belongsTo(StreamField::class,'field_id');
    }

}
