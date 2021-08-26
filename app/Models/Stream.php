<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function getFields()
    {
        return $this->hasMany(StreamField::class);
    }

    public function getFieldValues()
    {
        return $this->hasMany(StreamFieldValue::class, 'stream_id');
    }

}
