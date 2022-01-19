<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Graph;
use App\Models\Period;
use App\Models\Stream;
use App\Models\StreamChangeLog;
use App\Models\StreamField;
use App\Models\StreamFieldGrid;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            return redirect()->route('dashboard');
        }
        $search_keyword = $request->input('keyword') ?? null;
        $perPage = $request->show_rows ?? 10;

        $periods = Period::when($search_keyword, function ($query, $value) {
            $query->where('periods.name', 'like', '%' . $value . '%')
                ->orWhere('start_date', 'like', '%' . $value . '%')
                ->orWhere('end_date', 'like', '%' . $value . '%');
        })
            ->orderBy('id', 'DESC')
            ->paginate($perPage);

        $row_show = $perPage;
        return view('Periods.index')->with(compact('periods', 'row_show'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            return redirect()->route('dashboard');
        }
        return view("Periods.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            return redirect()->route('dashboard');
        }
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $input = $request->except('_token', 'end_date');
            $end_date = date('Y-m-d H:i:s', strtotime($request->end_date. '+1 day')-1);
            $input['end_date'] = $end_date;
            $input['created_by'] = auth()->user()->id;

            Period::create($input);
        } catch (\Exception $e) {

            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('dashboard.periods', [$request->form_id])->with('success', 'Period created successfully!');
    }

    public function edit($id)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            return redirect()->route('dashboard');
        }

        $period = Period::find(decrypt($id));
        return view('Periods.edit')->with(compact('period'));
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            return redirect()->route('dashboard');
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $input = $request->except('_token', 'end_date');
            $end_date = date('Y-m-d H:i:s', strtotime($request->end_date. '+1 day')-1);
            $input['end_date'] = $end_date;
            $input['updated_by'] = auth()->user()->id;

            Period::where('id', $id)->update($input);

        } catch (\Exception $e) {

            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('dashboard.periods')->with('success', 'Period updated successfully!');

    }

    public function delete(Request $request)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            return redirect()->route('dashboard');
        }

        $id = decrypt($request->ref);

        try {
            $form_ids = Form::where('period_id', $id)->pluck('id')->toArray();
            $stream_ids = Stream::whereIn('form_id', $form_ids)->pluck('id')->toArray();
            $stream_fields_ids = StreamField::whereIn('stream_id', $stream_ids)->pluck('id')->toArray();

            DB::beginTransaction();
            // delete all previous data
            Graph::whereIn('stream_id', $stream_fields_ids)->delete();
            StreamFieldGrid::whereIn('stream_field_id', $stream_fields_ids)->delete();
            StreamField::whereIn('stream_id', $stream_ids)->delete();
            StreamChangeLog::whereIn('stream_id', $stream_ids)->delete();
            Stream::whereIn('id', $stream_ids)->delete();
            Form::whereIn('id', $form_ids)->delete();
            Period::find($id)->delete();
            DB::commit();

            return back()->with('success', 'Period deleted successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function syncData(Request $request)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            return redirect()->route('dashboard');
        }

        try {
            $current_period_id = $request->input('period_id');
            $current_period_start_date = Period::where('id', $current_period_id)->value('start_date');
            $currentDateTime = Carbon::createFromDate($current_period_start_date)->subDay(30);
            $previous_period_id = Period:: whereMonth('start_date', $currentDateTime->month)->whereYear('start_date', $currentDateTime->year)->value('id');
            $previous_period_forms = Form::with(['streams'])->where('period_id', $previous_period_id)->orderBy('id', 'ASC')->get();
            $check_forms = Form::where('period_id', $current_period_id)->get();

            DB::beginTransaction();

            if ($check_forms->count() > 0){

                if (!empty($previous_period_id)){

                    $form_ids = Form::where('period_id', $current_period_id)->pluck('id')->toArray();
                    $stream_ids = Stream::whereIn('form_id', $form_ids)->pluck('id')->toArray();
                    $stream_field_ids = StreamField::whereIn('stream_id', $stream_ids)->pluck('id')->toArray();

                    // check if data is added in current period
                    $stream_field_data = StreamField::whereIn('id', $stream_field_ids)->get();
                    foreach ($stream_field_data as $stream_field_datum) {
                        if (!empty($stream_field_datum->value)){
                            return back()->with('error', 'Data is added in the current period it cannot be synced');
                        }

                        $stream_field_grid_data = StreamFieldGrid::where('stream_field_id', $stream_field_datum->id)->get();
                        foreach ($stream_field_grid_data as $value) {
                            if (!empty($value->value)){
                                return back()->with('error', 'Data is added in the current period it cannot be synced');
                            }
                        }
                    }

                    // delete all previously added data
                    StreamFieldGrid::whereIn('stream_field_id', $stream_field_ids)->delete();
                    StreamChangeLog::whereIn('stream_id', $stream_ids)->delete();
                    StreamField::whereIn('stream_id', $stream_ids)->delete();
                    Stream::whereIn('id', $stream_ids)->delete();
                    Form::whereIn('id', $form_ids)->delete();

                    foreach ($previous_period_forms as $form) {
                        $form_data = array(
                            'name' => $form->name,
                            'order_count' => $form->order_count,
                            'project_id' => $form->project_id,
                            'period_id' => $current_period_id,
                            'created_by' => auth()->user()->id,
                            'updated_by' => auth()->user()->id,
                            'is_special' => $form->is_special,
                            'previous_id' => $form->id
                        );
                        $stored_form = Form::create($form_data);

                        foreach ($form->streams as $stream) {
                            $stream_data = array(
                                'name' => $stream->name,
                                'form_id' => $stored_form->id,
                                'fields' => $stream->fields,
                                'status' => 'Draft',
                                'previous_id' => $stream->id
                            );
                            $stored_stream = Stream::create($stream_data);
                            $stream_fields = StreamField::where('stream_id', $stream->id)->orderBy('orderCount', 'ASC')->get();


                            foreach ($stream_fields as $field) {
                                $field_data = array(
                                    'stream_id' => $stored_stream->id,
                                    'form_id' => $stored_form->id,
                                    'user_id' => auth()->user()->id,
                                    'isRequired' => $field->isRequired,
                                    'fieldName' => $field->fieldName,
                                    'fieldType' => $field->fieldType,
                                    'isDuplicate' => $field->isDuplicate,
                                    'isCumulative' => $field->isCumulative,
                                    'fieldOptions' => $field->fieldOptions,
                                    'tableData' => $field->tableData,
                                    'orderCount' => $field->orderCount,
                                    'value' => null,
                                    'cumulative_value' => $field->cumulative_value,
                                    'previous_id' => $field->id
                                );
                                $stream_field = StreamField::create($field_data);
                                $stream_field_grids = StreamFieldGrid::where('stream_field_id', $field->id)->orderBy('order_count', 'ASC')->get();

                                foreach ($stream_field_grids as $grid) {

                                    $grid_data = array(
                                        'name' => $grid->name,
                                        'type' => $grid->type,
                                        'is_dropdown' => $grid->is_dropdown,
                                        'field_options' => $grid->field_options,
                                        'order_count' => $grid->order_count,
                                        'stream_field_id' => $stream_field->id,
                                        'value' => null,
                                        'cumulative_value' => $grid->cumulative_value,
                                        'previous_id' => $grid->id
                                    );
                                    StreamFieldGrid::create($grid_data);
                                }
                            }
                        }
                    }
                }else{
                    return redirect()->route('dashboard.periods')->with('warning', 'This is first Period it cannot be synced!');
                }
            }else{

                if ($previous_period_forms->count() == 0){
                    return back()->with('error', 'No Forms added in Previous Period');
                }else{

                    foreach ($previous_period_forms as $form) {
                        $form_data = array(
                            'name' => $form->name,
                            'order_count' => $form->order_count,
                            'project_id' => $form->project_id,
                            'period_id' => $current_period_id,
                            'created_by' => auth()->user()->id,
                            'is_special' => $form->is_special,
                            'previous_id' => $form->id,
                        );
                        $stored_form = Form::create($form_data);

                        foreach ($form->streams as $stream) {
                            $stream_data = array(
                                'name' => $stream->name,
                                'form_id' => $stored_form->id,
                                'fields' => $stream->fields,
                                'status' => 'Draft',
                                'previous_id' => $stream->id,
                            );
                            $stored_stream = Stream::create($stream_data);
                            $stream_fields = StreamField::where('stream_id', $stream->id)->orderBy('orderCount', 'ASC')->get();

                            foreach ($stream_fields as $field) {
                                $field_data = array(
                                    'stream_id' => $stored_stream->id,
                                    'form_id' => $stored_form->id,
                                    'user_id' => auth()->user()->id,
                                    'isRequired' => $field->isRequired,
                                    'fieldName' => $field->fieldName,
                                    'fieldType' => $field->fieldType,
                                    'isDuplicate' => $field->isDuplicate,
                                    'isCumulative' => $field->isCumulative,
                                    'fieldOptions' => $field->fieldOptions,
                                    'tableData' => $field->tableData,
                                    'orderCount' => $field->orderCount,
                                    'value' => null,
                                    'cumulative_value' => $field->cumulative_value,
                                    'previous_id' => $field->id
                                );
                                $stream_field = StreamField::create($field_data);
                                $stream_field_grids = StreamFieldGrid::where('stream_field_id', $field->id)->orderBy('order_count', 'ASC')->get();

                                foreach ($stream_field_grids as $grid) {
                                    $grid_data = array(
                                        'name' => $grid->name,
                                        'type' => $grid->type,
                                        'is_dropdown' => $grid->is_dropdown,
                                        'field_options' => $grid->field_options,
                                        'order_count' => $grid->order_count,
                                        'stream_field_id' => $stream_field->id,
                                        'value' => null,
                                        'cumulative_value' => $grid->cumulative_value,
                                        'previous_id' => $grid->id
                                    );
                                    StreamFieldGrid::create($grid_data);
                                }
                            }
                        }
                    }
                }
            }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('dashboard.periods')->with('success', 'Period data Synced successfully!');
    }
}
