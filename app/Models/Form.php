<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function streams()
    {
        return $this->hasMany(Stream::class)->orderBy('id', 'ASC');
    }

    public function project()
    {
        return $this->belongsTo(project::class, 'project_id');
    }

    public function streamFields()
    {
        return $this->hasMany(StreamFieldValue::class,'stream_id');
    }
}
