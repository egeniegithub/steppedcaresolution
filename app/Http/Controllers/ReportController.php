<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Period;
use App\Models\project;
use App\Models\StreamField;
use App\Models\StreamFieldGrid;
use App\Models\User;
use Carbon\Carbon;
use Dompdf\Dompdf;
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
        $active_user = User::where('id', auth()->user()->id)->first();
        $project = $request->project_id ?? "all";
        $projects = $this->projects_model->search_Projects($project);
        if (!empty($request->period_id)) {
            $period_id = $request->period_id;
        } else {
            $current_period = Period::all()->filter(function ($item) {
                if (Carbon::now()->between($item->start_date, $item->end_date)) {
                    return $item;
                }
            })->first();

            if (!empty($current_period)) {
                $period_id = $current_period->id;
            } else {
                $period_id = null;
            }
        }
        if ($active_user->role != 'Admin'){
            $form_streams = Form::where('project_id', $active_user->project_id)
            ->where('period_id', $period_id)
            ->with(['streams'])
            ->paginate($perPage);
        }else{
            $form_streams = Form::when($projects, function ($query, $index) {
            $query->whereIn('project_id', $index);
        })
            ->where('period_id', $period_id)
            ->with(['streams'])
            ->paginate($perPage);
        }
        $row_show = $perPage;
        $periods = Period::all();
        $projects = project::all();
        return view("Reports.index")->with(compact('form_streams', 'row_show', 'projects', 'periods', 'active_user'));
    }

    public function pdfReport($form_id)
    {
        $form = Form::where('id', $form_id)->with(['streams'])->first();
        $html_content = view('Reports.partials.pdf_report', compact('form'))->render();

        // instantiate and use the dompdf class
        ini_set('max_execution_time', 0);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html_content);

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream($form->name.".pdf");
        return back()->with('success', 'Report has been successfully generated.');
    }

    public function generateWordDoc($form_id)
    {
        $form = Form::where('id', $form_id)->with(['streams'])->first();
        $headers = array(
            "Content-type"=>"text/html",
            "Content-Disposition"=>"attachment;Filename=".$form->name.".doc"
        );
        $html_content = '<html><head><meta charset="utf-8"></head><body>';
        $html_content .= view('Reports.partials.pdf_report', compact('form'))->render();
        $html_content .= '</body></html>';

        return \Response::make($html_content,200, $headers);
    }

    public function generateCsv($field_id)
    {
        $grid_name = StreamField::where('id', $field_id)->value('fieldName');
        $tableData = StreamFieldGrid::where('stream_field_id', $field_id)->orderBy('type', 'ASC')->orderBy('order_count', 'ASC')->get();
        $column_dropdown = array();
        $table_options = array();
        $columns_array = array();
        $final_rows_array = array();

        // grid data
        $column_count = 0;
        $loop_iteration_1 = 1;
        foreach($tableData as $table){
            if($table->type == 'column'){

                if ($table->is_dropdown == 1){
                    array_push($column_dropdown, $column_count);
                    $table_options[$column_count] = explode(',',$table->field_options);
                }
                $column_count++;

                $check_cumulative = StreamField::where('id', $table->stream_field_id)->value('isCumulative');
                if($loop_iteration_1 == 1){
                    array_push($columns_array, '');
                }
                array_push($columns_array, $table->name ?? "");

                if($check_cumulative == 'yes'){
                    array_push($columns_array, $table->name ? $table->name.' (Cumulative)' : "");
                }
            }
            $loop_iteration_1++;
        }

        $loop_iteration_2 = 1;
        foreach($tableData as $table){
            $rows_array = array();
            if($table->type == 'row'){
                array_push($rows_array, $table->name ?? "");

                for($i=0; $i<$column_count; $i++){

                    $value = json_decode($table->value);

                    if( in_array($i, $column_dropdown)){
                        array_push($rows_array, $value ? $value[$i] : '');
                    }else{
                        array_push($rows_array, $value ? $value[$i] : '');
                    }

                    $check_cumulative = StreamField::where('id', $table->stream_field_id)->value('isCumulative');

                    if($check_cumulative == 'yes'){
                        $previous_cumulative_grid = \App\Models\StreamFieldGrid::where('id', $table->previous_id)->value('cumulative_value');
                        array_push($rows_array, $previous_cumulative_grid ? json_decode($previous_cumulative_grid)[$i] : 0);
                    }
                }
                $loop_iteration_2++;
                array_push($final_rows_array, $rows_array);
            }
        }
        //dd($columns_array, $final_rows_array);

        $file_name = $grid_name.'.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$file_name",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $callback = function() use($final_rows_array, $columns_array) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns_array);
            foreach ($final_rows_array as $row){
                if (!empty($row)){
                    fputcsv($file, $row);
                }
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function exportCsv(Request $request)
    {
        $fileName = 'tasks.csv';
        $tasks = Task::all();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Title', 'Assign', 'Description', 'Start Date', 'Due Date');

        $callback = function() use($tasks, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tasks as $task) {
                $row['Title']  = $task->title;
                $row['Assign']    = $task->assign->name;
                $row['Description']    = $task->description;
                $row['Start Date']  = $task->start_at;
                $row['Due Date']  = $task->end_at;

                fputcsv($file, array($row['Title'], $row['Assign'], $row['Description'], $row['Start Date'], $row['Due Date']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
