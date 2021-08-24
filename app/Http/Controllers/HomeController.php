<?php

namespace App\Http\Controllers;

use App\Models\Graph;
use App\Models\Period;
use App\Models\Permission;
use App\Models\Stream;
use App\Models\StreamAccess;
use App\Models\StreamField;
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
        if(Auth::user()->role=="User"){
            $active_user = User::where('id', auth()->user()->id)->first();
            $perPage = $request->show_rows ?? 10;
            $period_id = $request->period_id ?? '';

            if (!$period_id){
                $current_period_id = Period::all()->filter(function($item) {
                    if (Carbon::now()->between($item->start_date, $item->end_date)) {
                        return $item;
                    }
                })->first();
                $current_period_id = $current_period_id ? $current_period_id->value('id') : null;
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
                ->select('streams.id AS stream_id', 'streams.name as stream_name', 'f.name as form_name', 'f.project_id as project_id',
                    'streams.status as stream_status')
                ->orderBy('stream_id', 'DESC')
                ->paginate($perPage);

            $row_show = $perPage;
            $periods = Period::all();

            return view('dashboard')->with(compact('active_user', 'row_show', 'streams', 'periods', 'current_period_id'));
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

            $forms = Form::where('period_id', $period_id)->with('streams')->orderBy('id', 'DESC')->get();
            $periods = Period::all();
            $projects = project::all();
            $graphs = Graph::with(['stream','project','form','period','field'])->get();
            return view('dashboard')->with(compact('forms', 'periods', 'period_id','projects','graphs'));
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
        $response = StreamField::where(['stream_id'=>$id, 'fieldType' => 'number'])->get();

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

    public function removeGraph(Request $request)
    {
        $id = $request->id;

        return back()->with('success','Graph has been successfully removed');
    }

}
