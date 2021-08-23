<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Period;
use App\Models\project;
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

    public function getStreamReport(Request $request)
    {
        $formId = $request->id;
        $form = Form::find($formId)->with(['streams', 'project', 'streams.getFields', 'streams.getFieldValues', 'streamFields', 'streams.getFieldValues.field'])->first();
        return response()->json(['message' => '', 'data' => $form]);
    }

    public function downReport(Request $request)
    {
        $formId = $request->id;
        $data = Form::find($formId)->with(['streams', 'project', 'streams.getFields', 'streams.getFieldValues', 'streamFields', 'streams.getFieldValues.field'])->first();

        $html = '<div class="row">'
            . '<div class="col-sm-12 ">'
            . '<img src="' . $data->project->image . '" />'
            . '</div>'
            . '<div class="col-sm-12 ">'
            . '<p>' . $data->summary . '</p>'
            . '</div>';

        foreach ($data->streams as $stream) {
            $html .= '<div class="col-sm-12 ">'
                . '<p class="report_modal_dark_font">' . $stream->name . '</p>'
                . '<p>' . $stream->summary . '</p>'
                . '</div>'
                . '</div>'
                . '<div class="row">'
                . '<div class="col-sm-12 col-md-12">'
                . '<p class="report_modal_dark_font">Fields</p>';

            $get_field_values = $stream->getFieldValues;

            $html .= '<div class="col-sm-12 ">'
                . '<p class="report_modal_dark_font">' . $stream->name . '</p>'
                . '<p>' . $stream->summary . '</p>'
                . '</div>'
                . '</div>'
                . '<div class="row">'
                . '<div class="col-sm-12 col-md-12">'
                . '<p class="report_modal_dark_font">Fields</p>';

            foreach ($get_field_values as $get_field_value) {
                $dd = $get_field_value->field->fieldName ?? null;

                if ($get_field_value->field->fieldType == 'table') {
                    //
                } else if ($get_field_value->field->fieldType == 'file') {
                    $streamImage = $get_field_value->value ? base64_encode(env('APP_URL').'/stream_answer_image/'.$get_field_value->value) : null;
                    $html .= '<div class="row">' .
                        '<div class="col-sm-12">' .
                        '<span style="font-weight: bold">' . $dd . '</span>' .
                        '</div>' .
                        '<div class="col-sm-12">' .
                        '<img src="data:image/png;base64,' . $streamImage . '" style="width: 200px; height: 200px" />' .
                        '</div>' .
                        '</div>';
                } else {
                    $fieldName = $get_field_value->field->fieldName ?? null;
                    $fieldValue = $get_field_value->value ?? null;
                    $html .= '<div class="row">' .
                        '<div class="col-sm-3">' .
                        '<span style="font-weight: bold">' . $fieldName . '</span>' .
                        '</div>' .
                        '<div class="col-sm-9">' .
                        $fieldValue .
                        '</div>' .
                        '</div>';
                }
            }

        }

// instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
// (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
        $dompdf->render();

// Output the generated PDF to Browser
        $dompdf->stream();
        return back()->with('success', 'Report has been successfully generated.');
    }

}
