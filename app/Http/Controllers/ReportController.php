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
        //dd($form_id);
        $form = Form::where('id', $form_id)->first();
        $final_rows_array = array();

        $records = SpecialForm::where('period_id', $form->period_id)->where('project_id', $form->project_id)->get();

        $columns_array = array(
            'Which organization you are reporting for ?',
            'What period you are reporting for ?',
            'How many participants did you have in human led / moderated forums ?',
            'How many total registrations / unique visitors did you have for the period ?',
            'How many users accessed the application two or more times ?',
            'How many users accessed the application three or more times ?',
            'How many times were resources downloaded from yur site or application ? (If applicable)',
            'How many times were self-help resources accessed on your site or application ? (If applicable)',
            'Please provide demographic data (gender, age and location) for the period and cumulative date.',
            'What was the user satisfaction score for the period ?',
            'Is there any outcomes data for the period that you would like included in the report ?',
        );

        $forum_participants = 0;
        $unique_visitors = 0;
        $two_or_more_users = 0;
        $three_or_more_users = 0;
        $downloaded_resources = 0;
        $self_help_resources = 0;
        $demographic_data = 0;
        $user_satisfaction = 0;
        $outcomes_data = 0;

        foreach ($records as $record) {

            $forum_participants += $record->forum_participants;
            $unique_visitors += $record->unique_visitors;
            $two_or_more_users += $record->two_or_more_users;
            $three_or_more_users += $record->three_or_more_users;
            $downloaded_resources += $record->downloaded_resources;
            $self_help_resources += $record->self_help_resources;
            $demographic_data += $record->demographic_data;
            $user_satisfaction += $record->user_satisfaction;
            $outcomes_data += $record->outcomes_data;

            $period_name = \App\Models\Period::where('id', $record->period_id)
                ->select(DB::raw("CONCAT(name,' (',start_date, ' - ', end_date, ')') as period_name"))
                ->first();

            $single_array = array(
                Vendor::where('id', $record->vendor_id)->value('name'),
                $period_name->period_name,
                $record->forum_participants,
                $record->unique_visitors,
                $record->two_or_more_users,
                $record->three_or_more_users,
                $record->downloaded_resources,
                $record->self_help_resources,
                $record->demographic_data,
                $record->user_satisfaction,
                $record->outcomes_data
            );
            array_push($final_rows_array, $single_array);
        }

        $summed_data = array(
            '',
            '',
            number_format($forum_participants, 2),
            number_format($unique_visitors, 2),
            number_format($two_or_more_users, 2),
            number_format($three_or_more_users, 2),
            number_format($downloaded_resources, 2),
            number_format($self_help_resources, 2),
            number_format($demographic_data, 2),
            number_format($user_satisfaction, 2),
            number_format($outcomes_data, 2)
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
