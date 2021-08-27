<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Stream;
use App\Models\StreamAnswer;
use App\Models\StreamChangeLog;
use App\Models\StreamField;
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
                'streams.id as stream_id', 'streams.name as stream_name', 'streams.status as stream_status', 'f.name as form_name', 'p.name as project_name'
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
        //dd(json_decode(urldecode($request->fields[1]['tableData']))[0]);
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

            if (!empty($input['stream_id'])){
                Stream::where('id', $input['stream_id'])->update($stream);
                $inserted_stream = $input['stream_id'];
            }else{
                $inserted = Stream::create($stream);
                $inserted_stream = $inserted->id;
            }

            $fields = [];
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
                    'tableData' => $field['tableData'] ?? ''
                ];
            }
            foreach ($fields as $field) {
                if (!empty($field['id'])){
                    StreamField::where('id',$field['id'])->update($field);
                }else{
                    StreamField::create($field);
                }
            }
        } catch (\Exception $e) {

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
        $values = StreamFieldValue::where('stream_id', $id)->get();

        return view('streams.render')->with(compact('stream', 'values'));
    }

    public function streamPost(Request $request)
    {
        //dd($request->input());
        $user = auth()->user();
        $stream_id = $request->stream_id;
        $stream_answer_id = $request->stream_answer_id;
        $data_array = [];

        $inputs = $request->except('_token', 'stream_id', 'stream_answer_id');
        if ($request->image) {
            foreach ($request->image as $key => $image) {
                $imageName = time() . '.' . $image->extension();
                $image->move(public_path('stream_answer_image'), $imageName);

                $data_array[] = [
                    'stream_id' => $stream_id,
                    'user_id' => $user->id,
                    'form_id' => $request->form_id ?? 0,
                    'stream_field_id' => $key,
                    'value' => $imageName,
                ];
            }
        }

        Stream::whereId($stream_id)->update([
            'status' => $request->submit == 'Save Only' ? 'In-progress' : 'Published'
        ]);

        if (empty($stream_answer_id)) {
            foreach ($request->field as $key => $field) {
                $data_array[] = [
                    'stream_id' => $stream_id,
                    'user_id' => $user->id,
                    'form_id' => $request->form_id ?? 0,
                    'stream_field_id' => $key,
                    'value' => $field,
                ];
            }

            if (count($data_array)) {
                StreamFieldValue::insert($data_array);
            }
            $changeLog = [
                'stream_id' => $stream_id,
                'user_id' => $user->id,
                'new_data' => json_encode($data_array)
            ];
            StreamChangeLog::create($changeLog);
        } else {
            $streamDataOld = StreamFieldValue::where(['stream_id' => $stream_id])->get();
            foreach ($request->field as $key => $field) {
                StreamFieldValue::where(['stream_id' => $stream_id, 'stream_field_id' => $key])->update([
                    'value' => $field
                ]);
            }
            if (count($data_array)) {
                foreach ($data_array as $key => $image) {
                    StreamFieldValue::where(['stream_id' => $stream_id, 'stream_field_id' => $image['stream_field_id']])->update([
                        'value' => $image['value']
                    ]);
                }
            }
            $streamData = StreamFieldValue::where(['stream_id' => $stream_id])->get();
            $changeLog = [
                'stream_id' => $stream_id,
                'user_id' => $user->id,
                'old_data' => json_encode($streamDataOld),
                'new_data' => json_encode($streamData)
            ];
            StreamChangeLog::create($changeLog);
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
}
