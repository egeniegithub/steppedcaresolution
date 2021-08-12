<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Period;
use App\Models\project;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $projects_model;

    //
    function __construct()
    {
        $this->projects_model = new project;
    }

    public function index(Request $request)
    {
        $perPage = $request->show_rows ?? 10;
        $project = $request->project ?? "all";

        $projects = $this->projects_model->search_Projects($project);

        if (!empty($request->period_id)){
            $period_id = $request->period_id;
        }else{
            $period_id = Period::all()->filter(function($item) {
                if (Carbon::now()->between($item->start_date, $item->to)) {
                    return $item;
                }
            })->first()->value('id');
        }

        $form_streams = Form::when($projects, function ($query, $index) {
            $query->whereIn('project_id', $index);
        })
            ->where('period_id', $period_id)
            ->with(['streams'])
            ->paginate($perPage);

        $row_show = $perPage;
        $periods = Period::all();
        $projects = project::all();

        return view("Reports.index")->with(compact('form_streams', 'row_show', 'projects', 'periods'));
    }
}
