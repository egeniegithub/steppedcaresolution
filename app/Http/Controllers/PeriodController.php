<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Period;
use App\Models\Stream;
use App\Models\StreamField;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $input = $request->except('_token');
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
        $period = Period::find(decrypt($id));
        return view('Periods.edit')->with(compact('period'));
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $input = $request->except('_token');
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
        $id = decrypt($request->ref);

        try {
            $form_ids = Form::where('period_id', $id)->pluck('id')->toArray();
            $stream_ids = Stream::whereIn('form_id', $form_ids)->pluck('id')->toArray();

            DB::beginTransaction();
            // delete all previous data
            StreamField::whereIn('stream_id', $stream_ids)->delete();
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

                    // delete all previous data
                    StreamField::whereIn('stream_id', $stream_ids)->delete();
                    Stream::whereIn('id', $stream_ids)->delete();
                    Form::whereIn('id', $form_ids)->delete();

                    foreach ($previous_period_forms as $form) {
                        $form_data = array(
                            'name' => $form->name,
                            'project_id' => $form->project_id,
                            'period_id' => $current_period_id,
                            'created_by' => auth()->user()->id,
                            'updated_by' => auth()->user()->id
                        );
                        $stored_form = Form::create($form_data);

                        foreach ($form->streams as $stream) {
                            $stream_data = array(
                                'name' => $stream->name,
                                'form_id' => $stored_form->id,
                                'fields' => $stream->fields,
                                'status' => 'Draft',
                            );
                            $stored_stream = Stream::create($stream_data);
                            $stream_fields = StreamField::where('stream_id', $stream->id)->orderBy('id', 'ASC')->get();

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
                                    'orderCount' => $field->orderCount
                                );
                                StreamField::create($field_data);
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
                            'project_id' => $form->project_id,
                            'period_id' => $current_period_id,
                            'created_by' => auth()->user()->id
                        );
                        $stored_form = Form::create($form_data);

                        foreach ($form->streams as $stream) {
                            $stream_data = array(
                                'name' => $stream->name,
                                'form_id' => $stored_form->id,
                                'fields' => $stream->fields,
                                'status' => 'Draft',
                            );
                            $stored_stream = Stream::create($stream_data);
                            $stream_fields = StreamField::where('stream_id', $stream->id)->orderBy('id', 'ASC')->get();

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
                                    'orderCount' => $field->orderCount
                                );
                                StreamField::create($field_data);
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
