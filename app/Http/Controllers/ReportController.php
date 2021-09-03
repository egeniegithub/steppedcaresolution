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

    public function pdfReport($form_id)
    {
        $report_data = Form::where('id', $form_id)->with(['streams'])->first();
        $image_path = project::where('id', $report_data->project_id)->value('image');

        $html = "";

        if (!empty($report_data)){
            $html .= "<div class='text-center'>
                        <img src='{{asset(".'project_images'.")}}/"; echo $image_path; $html .= "' height='300px' width='500px' alt='No Img'>
                    </div>
                    <div class='row'>
                        <div class='col-sm-12'>
                            <b>Report Summary:</b>
                            <p class='report_over_flow_fix'>"; echo html_entity_decode($report_data->summary); $html .= "</p>
                        </div>
                    </div>";

                foreach($report_data->streams as $stream){
                    $html .= "<div class='row'>
                        <div class='col-sm-12'>
                            <p class='report_modal_dark_font'>"; echo $stream->name; $html .= "</p>
                            <b>Stream Summary:</b>
                            <p class='report_over_flow_fix'>"; echo html_entity_decode($stream->summary); $html.= "</p>
                        </div>
                    </div>";

                        $stream_fields = StreamField::where('stream_id', $stream->id)->orderBy('orderCount', 'ASC')->get();

                    if(!empty($stream_fields)){
                        foreach($stream_fields as $field){
                            $html .= "<div class='row' style='white-space:normal'>
                                <div class='col-sm-12'>
                                    <div class='form-group'>
                                        <label for='exampleFormControlTextarea1'><b>".$field->fieldName."</b> : </label>";
                                        switch($field->fieldType){
                                            case 'text':
                                                echo $field->value;
                                                break;

                                            case 'textarea':
                                                echo $field->value;
                                                break;

                                            case 'number':
                                                echo $field->value;

                                            $html .= "<div class='row'>
                                                <div class='col-md-6'>

                                                </div>
                                                <div class='col-md-6' style='margin-top: -30px'>";
                                                    if($field->isCumulative == 'yes'){
                                                        $html .= "<label  for='exampleFormControlTextarea1'><b>Cumulative Value</b></label>";
                                                        if(!empty($field->cumulative_value)) {
                                                            echo $field->cumulative_value;
                                                        }
                                                    }
                                                $html .= "</div>
                                            </div>";
                                            break;

                                            case 'date':
                                                echo $field->value;
                                                break;

                                            case 'file':
                                                if(isset($field->value)) {
                                                    $html .= "<img src = '{{asset('stream_answer_image')}}/"; echo $field->value; $html .= "' height = '300px' width = '500px' alt = 'No Img' >";
                                                }
                                                break;

                                            case 'select':
                                                echo $field->value;
                                                break;

                                            case 'table':
                                                $tableData = StreamFieldGrid::where('stream_field_id', $field->id)->orderBy('type', 'ASC')->orderBy('order_count', 'ASC')->get();
                                                $column_dropdown = array();
                                                $table_options = array();

                                            if($tableData){
                                                $html .= "<div class='col-sm-12 col-md-12'>
                                                    <div class='table-responsive'>
                                                        <table class='table report_sub_table report_generated_table table-bordered'>
                                                            <thead>
                                                                <tr class='red_row'>";
                                                                    $column_count = 0;
                                                                    $loop_iteration_column = 1;
                                                                    foreach($tableData as $table){
                                                                        if($table->type == 'column') {

                                                                            if ($table->is_dropdown == 1) {
                                                                                array_push($column_dropdown, $column_count);
                                                                                $table_options[$column_count] = explode(',', $table->field_options);
                                                                            }
                                                                            $column_count++;

                                                                            $check_cumulative = StreamField::where('id', $table->stream_field_id)->value('isCumulative');

                                                                            if ($loop_iteration_column == 1){
                                                                                $html .=  "<td></td>";
                                                                            }
                                                                            $html .= "<td class='text-white'>";
                                                                                echo $table->name;
                                                                            $html .= "</td>";

                                                                            if($check_cumulative == 'yes'){
                                                                                $html .= "<td>";
                                                                                if(!empty($table->name)){
                                                                                    echo $table->name." ".'(Cumulative)';
                                                                                }else{
                                                                                    echo '';
                                                                                }
                                                                                $html .= "</td>";
                                                                            }
                                                                        }
                                                                        $loop_iteration_column++;
                                                                    }
                                                                $html .= "</tr>
                                                            </thead>
                                                            <tbody>";

                                                            $loop_iteration_row = 1;
                                                            foreach($tableData as $table){
                                                                if($table->type == 'row'){
                                                                    if($loop_iteration_row == 1){
                                                                        $html .= "<tr>";
                                                                            for($i=0; $i<$column_count; $i++){
                                                                                $html .= "<td></td>";
                                                                            }
                                                                        $html .= "</tr>";
                                                                    }
                                                                    $html .= "<tr>
                                                                        <td>".$table->name."</td>";
                                                                        for($i=0; $i<$column_count; $i++){
                                                                            $html .= "<td>";

                                                                            $value = json_decode($table->value);
                                                                                if( in_array($i, $column_dropdown)){

                                                                                    if(!empty($value)){
                                                                                        echo $value[$i];
                                                                                    }else{
                                                                                        echo '';
                                                                                    }
                                                                                }else{
                                                                                    if(!empty($value)){
                                                                                        echo $value[$i];
                                                                                    }else{
                                                                                        echo '';
                                                                                    }
                                                                                }
                                                                            $html .= "</td>";

                                                                                $check_cumulative = StreamField::where('id', $table->stream_field_id)->value('isCumulative');

                                                                            if($check_cumulative == 'yes'){
                                                                                $html .= "<td>";

                                                                                    if(!empty($table->cumulative_value)){
                                                                                       echo json_decode($table->cumulative_value)[$i];
                                                                                    }else{
                                                                                        echo 0;
                                                                                    }
                                                                                $html .= "</td>";
                                                                            }
                                                                        }
                                                                    $html .= "</tr>";
                                                                }
                                                                $loop_iteration_row++;
                                                            }
                                                        $html .= "</tbody>
                                                        </table>
                                                    </div>
                                                </div>";
                                            }
                                        break;

                                        default:
                                        echo '';
                                        }
                                    $html .= "</div>
                                </div>
                            </div>";
                        }
                    }
                }
        }
        $html .= "";

        //dd($html);

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        //$dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream();
        return back()->with('success', 'Report has been successfully generated.');
    }
}
