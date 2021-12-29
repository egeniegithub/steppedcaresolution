<?php

namespace App\Http\Controllers;

use App\Models\Graph;
use App\Models\Period;
use App\Models\Permission;
use App\Models\Stream;
use App\Models\StreamAccess;
use App\Models\StreamField;
use App\Models\StreamFieldGrid;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use App\Models\Form;
use App\Models\project;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if(Auth::user()->role == "User" || Auth::user()->role=="Vendor"){
            $active_user = User::where('id', auth()->user()->id)->first();
            $perPage = $request->show_rows ?? 10;
            $period_id = $request->period_id ?? '';
            $date = Carbon::now();

            if (!$period_id){
                $current_period_id =Period::whereRaw('"'.$date.'" between `start_date` and `end_date`')->value('id');
            }else{
                $current_period_id = null;
            }

            $permission_ids = StreamAccess::where('assigned_user_id', $active_user->id)->pluck('permission_id')->toArray();

            $stream_ids = Permission::when($period_id, function ($query, $value) {
                $query->where('period_id', $value);
            })->when($current_period_id, function ($query, $value) {
                $query->where('period_id', $value);
            })->whereIn('id', $permission_ids)->pluck('stream_id')->toArray();

            $streams = Stream::leftjoin('forms as f', 'streams.form_id', '=', 'f.id')
                ->where('f.project_id', $active_user->project_id)
                ->whereIn('streams.id', $stream_ids)
                ->where(function ($q) use($period_id) {
                    if ($period_id) {
                        $q->where('period_id', $period_id);
                    }
                })
                ->select('streams.id AS stream_id', 'streams.name as stream_name', 'f.name as form_name', 'f.project_id as project_id',
                    'streams.status as stream_status')
                //->orderBy('stream_id', 'DESC')
                ->paginate($perPage);

            $row_show = $perPage;
            $periods = Period::all();
            $vendors = Vendor::all();

            return view('dashboard')->with(compact('active_user', 'row_show', 'streams', 'periods', 'current_period_id', 'vendors'));
        }else{
            if (!empty($request->period_id)){
                $period_id = $request->period_id;
            }else{

                $current_period = Period::all()->filter(function($item) {
                    if (Carbon::now()->between($item->start_date, $item->end_date)) {
                        return $item;
                    }
                })->first();

                if (!empty($current_period)){
                    $period_id = $current_period->id;
                }else{
                    $period_id = null;
                }
            }

            //start non cumulative graphs
            $non_cumulative_graph = array();
            $graphs_array = array();

            $grid_non_cumulative = Graph::with(['stream','project','form','period','field'])->where('is_cumulative', 0)->get();

            foreach ($grid_non_cumulative as $grid_value) {
                $single_table_array = array();
                $grid_current_graph = array();
                $column_array = array();

                $check_field = StreamField::where('id', $grid_value->field_id)->first();
                //dd($check_field);

                if ($check_field->fieldType == 'table'){

                    $column_count = 0;
                    $grid_data = StreamFieldGrid::where('stream_field_id', $check_field->id)->orderBy('type', 'ASC')->orderBy('order_count', 'ASC')->get();

                    // column check
                    foreach($grid_data as $grid){
                        if($grid->type == 'column'){
                            $column_count++;
                            array_push($column_array, $grid->name);
                        }
                    }
                    $graphs_array['column_name'] = $column_array;

                    $graphs_array['graph_info'] = [
                        'graph_id' => $grid_value->id,
                        'name' => $grid_value->form->name.' - '.$grid_value->stream->name.' - '.$grid_value->field->fieldName,
                        'duration' => date('d M, Y', strtotime($grid_value->period->start_date)).' to '.date('d M, Y', strtotime($grid_value->period->end_date))
                    ];

                    // row values
                    foreach($grid_data as $grid) {
                        $row_values_array = array();
                        if ($grid->type == 'row'){
                            for($i=0; $i<$column_count; $i++){
                                $value = json_decode($grid->value);
                                if ($value){
                                    $final_value = (float)$value[$i];
                                    //dd($final_value);
                                    array_push($row_values_array, $final_value);
                                }
                            }
                            $grid_current_graph['row_name'] = $grid->name;
                            $grid_current_graph['row_values'] = json_encode($row_values_array);
                            array_push($single_table_array, $grid_current_graph);
                        }
                    }
                    $graphs_array['data'] = $single_table_array;
                    array_push($non_cumulative_graph, $graphs_array);

                }
            }

            //dd($non_cumulative_graph);
            //end non cumulative graphs

            //start cumulative graphs
            $cumulative_graph = array();
            $graphs_array_cumulative = array();

            $grid_cumulative = Graph::with(['stream','project','form','period','field'])->where('is_cumulative', 1)->get();

            foreach ($grid_cumulative as $grid_value_cumulative) {
                $cumulative_single_table_array = array();
                $cumulative_grid_current_graph = array();
                $column_array = array();

                $check_field = StreamField::where('id', $grid_value_cumulative->field_id)->first();
                //dd($check_field);

                if ($check_field->fieldType == 'table'){

                    $column_count = 0;
                    $grid_data_cumulative = StreamFieldGrid::where('stream_field_id', $check_field->id)->orderBy('type', 'ASC')->orderBy('order_count', 'ASC')->get();

                    // column check
                    foreach($grid_data_cumulative as $grid){
                        if($grid->type == 'column'){
                            $column_count++;
                            array_push($column_array, $grid->name);
                        }
                    }
                    $graphs_array_cumulative['column_name'] = $column_array;

                    $graphs_array_cumulative['graph_info'] = [
                        'graph_id' => $grid_value_cumulative->id,
                        'name' => $grid_value_cumulative->form->name.' - '.$grid_value_cumulative->stream->name.' - '.$grid_value_cumulative->field->fieldName,
                        'duration' => date('d M, Y', strtotime($grid_value_cumulative->period->start_date)).' to '.date('d M, Y', strtotime($grid_value_cumulative->period->end_date))
                    ];

                    // row values
                    foreach($grid_data_cumulative as $grid) {
                        $row_values_array = array();
                        if ($grid->type == 'row'){
                            for($i=0; $i<$column_count; $i++){
                                $value = json_decode($grid->cumulative_value);
                                if ($value){
                                    $final_value = (float)$value[$i];
                                    //dd($final_value);
                                    array_push($row_values_array, $final_value);
                                }
                            }
                            $cumulative_grid_current_graph['row_name'] = $grid->name;
                            $cumulative_grid_current_graph['row_values'] = json_encode($row_values_array);
                            array_push($cumulative_single_table_array, $cumulative_grid_current_graph);
                        }
                    }
                    //dd($cumulative_single_table_array);
                    $graphs_array_cumulative['data'] = $cumulative_single_table_array;
                    array_push($cumulative_graph, $graphs_array_cumulative);

                }
            }
            //dd($cumulative_graph);
            //end cumulative graphs

            $forms = Form::where('period_id', $period_id)->with('streams')->orderBy('id', 'DESC')->get();
            $periods = Period::all();
            $projects = project::all();
            $graphs = Graph::with(['stream','project','form','period','field'])->get();
            return view('dashboard')->with(compact('forms', 'periods', 'period_id','projects','graphs', 'non_cumulative_graph', 'cumulative_graph'));
        }
    }

    public function getFormStreams(Request $request)
    {
        $id = $request->id ?? null;
        $streams = [];
        if ($id){
            $streams = Stream::where('form_id',$id)->get();
        }
        return response()->json(['data' => $streams]);
    }

    public function getProjectForms(Request $request)
    {
        $id = $request->id;
        $forms = Form::where('project_id', $id)->get();
        return response()->json(['data' => $forms]);
    }

    public function getStreamFields(Request $request)
    {
        $id = $request->id;
        $response = StreamField::where(['stream_id'=>$id, 'fieldType' => 'table'])->get();

        return response()->json([
            'data' => $response
        ]);
    }

    public function saveGraph(Request $request)
    {
        //dd($request->all());
        Graph::create($request->all());
        return back()->with('success','New Graph has been successfully added.');
    }

    public function deleteGraph(Request $request)
    {
        $id = $request->id;
        Graph::where('id',$id)->delete();
        return back()->with('success','Graph has been successfully removed');
    }

    private function __PurchaseChartOptions($title)
    {
        return [
            'yAxis' => [
                'title' => [
                    'text' => $title
                ]
            ],
            'plotOptions' => [
                'column' => [
                    //'color' => '#dd4b39',
                    'dataLabels' => [
                        'enabled' => true
                    ]
                ]
            ],
        ];
    }
}
