<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Stream;
use App\Models\StreamAnswer;
use App\Models\StreamChangeLog;
use App\Models\StreamField;
use App\Models\StreamFieldGrid;
use App\Models\StreamFieldValue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StreamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */

    public function index($form_id)
    {
        $streams = Stream::leftjoin('forms as f', 'f.id', '=', 'streams.form_id')
            ->leftjoin('projects as p', 'p.id', '=', 'f.project_id')
            ->where('form_id', $form_id)
            ->select(
                'streams.id as stream_id', 'streams.name as stream_name', 'streams.status as stream_status', 'f.name as form_name', 'p.name as project_name', 'order_count'
            )
            ->orderBy('stream_id', 'DESC')
            ->get();

        $form = Form::where('id', $form_id)->first();
        return view('streams.index')->with(compact('streams', 'form'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */

    public function create($form_id, $stream_id = null)
    {
        $stream = null;
        $fields = [];
        if (!empty($stream_id)) {
            $stream = Stream::where('id', $stream_id)->with(['getFields'])->first();
            $fields = $stream->getFields;
        }
        return view('streams.create')->with(compact('form_id', 'stream', 'fields'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store(Request $request)
    {
        dd($request);
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user = auth()->user();
            $input = $request->input();

            $stream = array(
                'name' => $input['name'],
                'form_id' => $input['form_id'],
                'fields' => null,
                'status' => 'Draft'
            );

            DB::beginTransaction();

            if (!empty($input['stream_id'])){
                Stream::where('id', $input['stream_id'])->update($stream);
                $inserted_stream = $input['stream_id'];
            }else{
                $inserted = Stream::create($stream);
                $inserted_stream = $inserted->id;
            }

            $fields = [];
            $table_fields = [];
            foreach ($input['fields'] as $field) {

                $fields[] = [
                    'id' => !empty($field['id']) ? $field['id'] : null,
                    'stream_id' => $inserted_stream,
                    'form_id' => $input['form_id'],
                    'user_id' => $user->id,
                    'isRequired' => $field['isRequired'],
                    'fieldName' => $field['fieldName'],
                    'fieldType' => $field['fieldType'],
                    'isDuplicate' => $field['isDuplicate'],
                    'isCumulative' => $field['isCumulative'],
                    'orderCount' => $field['orderCount'],
                    'fieldOptions' => $field['fieldOptions'] ?? '',
                    'tableData' => $field['tableData'] ?? '',
                ];
            }
            foreach ($fields as $field) {

                if (!empty($field['id'])){
                    StreamField::where('id',$field['id'])->update($field);
                }else{
                    $stream_field = StreamField::create($field);

                    if (!empty($field['tableData'])){

                        $grid_data = json_decode(urldecode($field['tableData']));

                        foreach ($grid_data as $grid) {
                            $table_fields = array(
                                'name' => $grid->fieldName,
                                'type' => $grid->type,
                                'is_dropdown' => $grid->tableDropdown == 'no' ? 0 : 1,
                                'field_options' => $grid->tableFieldOptions,
                                'order_count' => $grid->orderCount,
                                'stream_field_id' => $stream_field->id,
                                'cumulative_value' => null
                            );
                            StreamFieldGrid::create($table_fields);
                        }
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('dashboard.streams', [$request->form_id])->with('success', 'Stream saved successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Stream $stream
     * @return \Illuminate\Http\Response
     */

    public function show(Stream $stream)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Stream $stream
     * @return \Illuminate\Http\Response
     */

    public function edit(Stream $stream)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Stream $stream
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Stream $stream)
    {
        //
    }

    public function stream_update()
    {
        return view('streams.stream_update');
    }

    public function stream_update_two()
    {
        return view('streams.stream_update_two');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Stream $stream
     * @return \Illuminate\Http\RedirectResponse
     */

    public function destroy(Request $request)
    {
        $id = $request->id;
        try {
            StreamField::where('stream_id', $id)->delete();
            Stream::find($id)->delete();
            return back()->with('success', "Stream has been successfully deleted");
        } catch (\Exception $exception) {
            return back()->with('error', "Something went wrong");
        }
    }

    public function addUpdateStreamSummary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'summary' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $input = $request->only('summary');
            $input['updated_by'] = auth()->user()->id;

            $stream = Stream::find($request->id);
            $stream->update($input);

        } catch (\Exception $e) {

            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Summary saved successfully!');
    }

    public function render($id)
    {
        $stream = Stream::where('id', $id)->with('getFields')->first();
        return view('streams.render')->with(compact('stream'));
    }

    public function streamPost(Request $request)
    {
        //dd($request);
        $user = auth()->user();
        $stream_id = $request->stream_id;

        try {
            DB::beginTransaction();

            // start previous data change log
            $stream_field_data = StreamField::where('stream_id', $stream_id)
                ->select('stream_id', 'form_id', 'user_id', 'value', 'cumulative_value')
                ->get();

            $stream_table_data = StreamFieldGrid::leftjoin('stream_fields as sf', 'sf.id', '=', 'stream_field_grids.stream_field_id')
                ->where('sf.stream_id', $stream_id)
                ->select('stream_field_grids.id AS grid_id', 'stream_field_id', 'stream_field_grids.value', 'stream_field_grids.cumulative_value')
                ->get();

            $previous_data = array(
                'stream_field_data' => json_encode($stream_field_data),
                'stream_table_data' => json_encode($stream_table_data)

            );
            // end previous data change log

            if ($request->image) {
                foreach ($request->image as $key => $image) {
                    $imageName = time() . '.' . $image->extension();
                    $image->move(public_path('stream_answer_image'), $imageName);
                    StreamField::where('id', $key)->update(['value' => $imageName]);
                }
            }

            Stream::whereId($stream_id)->update([
                'status' => $request->submit == 'Save Only' ? 'In-progress' : 'Published'
            ]);

            // for field value
            if ($request->field) {
                foreach ($request->field as $key => $field) {
                    StreamField::where('id', $key)->update(['value' => $field]);
                }
            }

            // for cumulative value
            if ($request->cumulative_field) {
                foreach ($request->cumulative_field as $key => $cumulative_field) {
                    StreamField::where('id', $key)->update(['cumulative_value' => $cumulative_field]);
                }
            }

            // for table values
            if ($request->table_value) {
                foreach ($request->table_value as $key => $value) {
                    StreamFieldGrid::where('id', $key)->update(['value' => json_encode($value)]);
                }
            }

            // for table cumulative values
            if ($request->cumulative_table_value) {
                foreach ($request->cumulative_table_value as $key => $cumulative_table_value) {
                    StreamFieldGrid::where('id', $key)->update(['cumulative_value' => json_encode($cumulative_table_value)]);
                }
            }

            // start changed data change log
            $stream_field_data = StreamField::where('stream_id', $stream_id)
                ->select('stream_id', 'form_id', 'user_id', 'value', 'cumulative_value')
                ->get();

            $stream_table_data = StreamFieldGrid::leftjoin('stream_fields as sf', 'sf.id', '=', 'stream_field_grids.stream_field_id')
                ->where('sf.stream_id', $stream_id)
                ->select('stream_field_grids.id AS grid_id', 'stream_field_id', 'stream_field_grids.value', 'stream_field_grids.cumulative_value')
                ->get();

            $changed_data = array(
                'stream_field_data' => json_encode($stream_field_data),
                'stream_table_data' => json_encode($stream_table_data)

            );
            // end changed data change log

            $changeLog = [
                'stream_id' => $stream_id,
                'user_id' => $user->id,
                'old_data' => json_encode($previous_data),
                'new_data' => json_encode($changed_data)
            ];
            StreamChangeLog::create($changeLog);
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('dashboard')->with('success', 'Data saved successfully!');
    }

    public function UpdateStatus(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'status' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $input = $request->only('status');
            $input['updated_by'] = auth()->user()->id;

            $stream = Stream::find($request->id);
            $stream->update($input);

        } catch (\Exception $e) {

            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Status updated successfully!');
    }

    public function streamField(Request $request)
    {
        $id = $request->id;
        StreamField::where('id',$id)->delete();
        return back()->with('success','Field has been successfully saved.');
    }

    // save stream order
    public function streamOrder(Request $request)
    {
        $id = $request->stream_id;
        Stream::where('id',$id)->update(['order_count' => $request->value]);
        return back()->with('success','Field has been successfully saved.');
    }
}
