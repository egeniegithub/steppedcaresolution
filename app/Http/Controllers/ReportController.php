<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Period;
use App\Models\project;
use App\Models\SpecialForm;
use App\Models\StreamField;
use App\Models\StreamFieldGrid;
use App\Models\User;
use App\Models\Vendor;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            return redirect()->route('dashboard');
        }

        $perPage = $request->show_rows ?? 10;
        $active_user = User::where('id', auth()->user()->id)->first();
        $project_id = $request->project_id ?? "";
        $projects = $this->projects_model->search_Projects($project_id);
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
        if ($active_user->role != 'Admin') {
            $form_streams = Form::where('project_id', $active_user->project_id)
                ->where('period_id', $period_id)
                ->with(['streams'])
                ->orderBy('order_count', 'ASC')
                ->paginate($perPage);
        } else {
            $form_streams = Form::when($projects, function ($query, $index) {
                $query->whereIn('project_id', $index);
            })
                ->where('period_id', $period_id)
                ->with(['streams'])
                ->orderBy('order_count', 'ASC')
                ->paginate($perPage);
        }
        $row_show = $perPage;
        $periods = Period::all();
        $projects = project::all();
        $report_data = Form::where('period_id', $period_id)
            ->where('project_id', $project_id)
            ->with(['streams'])
            ->orderBy('order_count', 'ASC')
            ->get();
        return view("Reports.index")
            ->with(compact('form_streams', 'row_show', 'projects', 'periods', 'active_user', 'period_id', 'project_id', 'report_data'));
    }

    public function pdfReport($form_id)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            return redirect()->route('dashboard');
        }

        $form = Form::where('id', $form_id)->with(['streams'])->first();
        set_time_limit(300);
        $html_content = view('Reports.partials.pdf_report', compact('form'))->render();

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html_content);

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream($form->name . ".pdf");
        return back()->with('success', 'Report has generated successfully.');
    }

    public function generateWordDoc($form_id)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            return redirect()->route('dashboard');
        }

        $form = Form::where('id', $form_id)->with(['streams'])->first();
        $headers = array(
            "Content-type" => "text/html",
            "Content-Disposition" => "attachment;Filename=" . $form->name . ".doc"
        );
        $html_content = '<html><head><meta charset="utf-8"></head><body>';
        $html_content .= view('Reports.partials.pdf_report', compact('form'))->render();
        $html_content .= '</body></html>';

        return \Response::make($html_content, 200, $headers);
    }

    public function pdfProjectReport($period_id, $project_id)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            return redirect()->route('dashboard');
        }

        $project = project::where('id', $project_id)->first();
        $report_data = Form::where('period_id', $period_id)->where('project_id', $project_id)->with(['streams'])->orderBy('order_count', 'ASC')->get();
        set_time_limit(300);
        $html_content = view('Reports.partials.project_pdf_report', compact('report_data', 'project'))->render();

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html_content);

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream($project->name . ".pdf");
        return back()->with('success', 'Report has generated successfully.');
    }

    public function docProjectReport($period_id, $project_id)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            return redirect()->route('dashboard');
        }

        $project = project::where('id', $project_id)->first();
        $report_data = Form::where('period_id', $period_id)->where('project_id', $project_id)->with(['streams'])->orderBy('order_count', 'ASC')->get();
        $headers = array(
            "Content-type" => "text/html",
            "Content-Disposition" => "attachment;Filename=" . $project->name . ".doc"
        );
        $html_content = '<html><head><meta charset="utf-8"></head><body>';
        $html_content .= view('Reports.partials.project_pdf_report', compact('report_data', 'project'))->render();
        $html_content .= '</body></html>';

        return \Response::make($html_content, 200, $headers);
    }

    public function generateCsv($field_id)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            return redirect()->route('dashboard');
        }

        $grid_name = StreamField::where('id', $field_id)->value('fieldName');
        $tableData = StreamFieldGrid::where('stream_field_id', $field_id)->orderBy('type', 'ASC')->orderBy('order_count', 'ASC')->get();
        $column_dropdown = array();
        $table_options = array();
        $columns_array = array();
        $final_rows_array = array();

        // grid data
        $column_count = 0;
        $loop_iteration_1 = 1;
        foreach ($tableData as $table) {
            if ($table->type == 'column') {

                if ($table->is_dropdown == 1) {
                    array_push($column_dropdown, $column_count);
                    $table_options[$column_count] = explode(',', $table->field_options);
                }
                $column_count++;

                $check_cumulative = StreamField::where('id', $table->stream_field_id)->value('isCumulative');
                if ($loop_iteration_1 == 1) {
                    array_push($columns_array, '');
                }
                array_push($columns_array, $table->name ?? "");

                if ($check_cumulative == 'yes') {
                    array_push($columns_array, $table->name ? $table->name . ' (Cumulative)' : "");
                }
            }
            $loop_iteration_1++;
        }

        $loop_iteration_2 = 1;
        foreach ($tableData as $table) {
            $rows_array = array();
            if ($table->type == 'row') {
                array_push($rows_array, $table->name ?? "");

                for ($i = 0; $i < $column_count; $i++) {

                    $value = json_decode($table->value);

                    if (in_array($i, $column_dropdown)) {
                        array_push($rows_array, $value ? $value[$i] : '');
                    } else {
                        array_push($rows_array, $value ? $value[$i] : '');
                    }

                    $check_cumulative = StreamField::where('id', $table->stream_field_id)->value('isCumulative');

                    if ($check_cumulative == 'yes') {
                        $previous_cumulative_grid = \App\Models\StreamFieldGrid::where('id', $table->previous_id)->value('cumulative_value');
                        array_push($rows_array, $previous_cumulative_grid ? json_decode($previous_cumulative_grid)[$i] : 0);
                    }
                }
                $loop_iteration_2++;
                array_push($final_rows_array, $rows_array);
            }
        }
        //dd($columns_array, $final_rows_array);

        $file_name = $grid_name . '.csv';

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$file_name",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $callback = function () use ($final_rows_array, $columns_array) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns_array);
            foreach ($final_rows_array as $row) {
                if (!empty($row)) {
                    fputcsv($file, $row);
                }
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function generateStaticCsv($form_id)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            return redirect()->route('dashboard');
        }

        $form = Form::where('id', $form_id)->first();
        $final_rows_array = array();

        $records = SpecialForm::where('period_id', $form->period_id)->where('project_id', $form->project_id)->get();

        $columns_array = array(
            '',
            'Period',
            'Registrations',
            'Users Accessing 2X or more',
            'Users Accessing 3X or more',
            'Moderated Forum Participants',
            'Self-Help Resources Accessed'
        );

        $unique_visitors = 0;
        $two_or_more_users = 0;
        $three_or_more_users = 0;
        $forum_participants = 0;
        $self_help_resources = 0;

        foreach ($records as $record) {

            $unique_visitors += $record->unique_visitors;
            $two_or_more_users += $record->two_or_more_users;
            $three_or_more_users += $record->three_or_more_users;
            $forum_participants += $record->forum_participants;
            $self_help_resources += $record->self_help_resources;

            $period_name = \App\Models\Period::where('id', $record->period_id)
                ->select(DB::raw("CONCAT(name,' (',DATE_FORMAT(start_date, '%d-%m-%Y'), ' - ', DATE_FORMAT(end_date, '%d-%m-%Y'), ')') as period_name"))
                ->first();

            $single_array = array(
                Vendor::where('id', $record->vendor_id)->value('name'),
                $period_name->period_name,
                $record->unique_visitors,
                $record->two_or_more_users,
                $record->three_or_more_users,
                $record->forum_participants,
                $record->self_help_resources,
            );
            array_push($final_rows_array, $single_array);
        }

        $summed_data = array(
            '',
            'Total',
            $unique_visitors,
            $two_or_more_users,
            $three_or_more_users,
            $forum_participants,
            $self_help_resources
        );
        array_push($final_rows_array, $summed_data);

        //dd($columns_array, $final_rows_array);

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=static_form.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $callback = function () use ($final_rows_array, $columns_array) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns_array);
            foreach ($final_rows_array as $row) {
                if (!empty($row)) {
                    fputcsv($file, $row);
                }
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function generateStaticCumulativeCsv($form_id)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            return redirect()->route('dashboard');
        }

        $form = Form::where('id', $form_id)->first();
        $final_rows_array = array();

        $current_period_start_date = Period::where('id', $form->period_id)->value('start_date');
        $period_ids = Period::where('start_date', '<=', $current_period_start_date)->pluck('id')->toArray();

        $cumulative_records = \App\Models\SpecialForm::whereIn('period_id', $period_ids)
            ->where('project_id', $form->project_id)
            ->select('period_id', 'project_id', 'vendor_id', 'user_id',
                DB::raw('SUM(unique_visitors) as total_unique_visitors'),
                DB::raw('SUM(two_or_more_users) as total_two_or_more_users'),
                DB::raw('SUM(three_or_more_users) as total_three_or_more_users'),
                DB::raw('SUM(forum_participants) as total_forum_participants'),
                DB::raw('SUM(self_help_resources) as total_self_help_resources')
            )
            ->groupBy('user_id')
            ->orderBy('id', 'ASC')
            ->get();

        $columns_array = array(
            '',
            'Period',
            'Cumulative Registrations',
            'Cumulative Users Accessing 2X or more',
            'Cumulative Users Accessing 3X or more',
            'Cumulative Moderated Forum Participants',
            'Cumulative Self-Help Resources Accessed'
        );

        $total_unique_visitors = 0;
        $total_two_or_more_users = 0;
        $total_three_or_more_users = 0;
        $total_forum_participants = 0;
        $total_self_help_resources = 0;

        foreach ($cumulative_records as $record) {

            $total_unique_visitors += $record->total_unique_visitors;
            $total_two_or_more_users += $record->total_two_or_more_users;
            $total_three_or_more_users += $record->total_three_or_more_users;
            $total_forum_participants += $record->total_forum_participants;
            $total_self_help_resources += $record->total_self_help_resources;

            $period_name = \App\Models\Period::where('id', $record->period_id)
                ->select(DB::raw("CONCAT(name,' (',DATE_FORMAT(start_date, '%d-%m-%Y'), ' - ', DATE_FORMAT(end_date, '%d-%m-%Y'), ')') as period_name"))
                ->first();

            $single_array = array(
                Vendor::where('id', $record->vendor_id)->value('name'),
                $period_name->period_name,
                $record->total_unique_visitors,
                $record->total_two_or_more_users,
                $record->total_three_or_more_users,
                $record->total_forum_participants,
                $record->total_self_help_resources,
            );
            array_push($final_rows_array, $single_array);
        }

        $summed_data = array(
            '',
            'Total',
            $total_unique_visitors,
            $total_two_or_more_users,
            $total_three_or_more_users,
            $total_forum_participants,
            $total_self_help_resources
        );
        array_push($final_rows_array, $summed_data);

        //dd($columns_array, $final_rows_array);

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=static_form.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $callback = function () use ($final_rows_array, $columns_array) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns_array);
            foreach ($final_rows_array as $row) {
                if (!empty($row)) {
                    fputcsv($file, $row);
                }
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}
