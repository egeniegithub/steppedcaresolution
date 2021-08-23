<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class project extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'period_id',
        'image'
    ];

    //
    public function search_Projects($project)
    {
        if ($project == "all") {
            $projects = $this::all()
                ->pluck('id');
        } else {
            $projects = $this::where('id', $project)
                ->get()
                ->pluck('id');
        }
        return $projects;
    }
    //

    //
    public function all_Projects()
    {
        return $this::orderBy("id", "desc")->get();
    }
    //
}
