<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Stream;
use App\Models\StreamAnswer;
use Illuminate\Http\Request;
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
        $stream = !empty($stream_id) ? Stream::find($stream_id) : null;
        $fields = isset($stream->fields) ? json_decode($stream->fields, true) : [];
        return view('streams.create')->with(compact('form_id', 'stream', 'fields'));
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
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $input = $request->input();
            $streamObj = !empty($input['stream_id']) ? Stream::find($input['stream_id']) : new Stream();
            $streamObj->name = $input['name'];
            $streamObj->form_id = $input['form_id'];
            $streamObj->fields = json_encode($input['fields']);
            $streamObj->status = 'Draft';
            $streamObj->save();

        } catch (\Exception $e) {

            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('dashboard.streams', [$request->form_id])->with('success', 'Stream saved successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\Http\Response
     */

    public function show(Stream $stream)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\Http\Response
     */

    public function edit(Stream $stream)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Stream $stream)
    {
        //
    }
    public function stream_update(){
        return view('streams.stream_update');
    }
    public function stream_update_two(){
        return view('streams.stream_update_two');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request)
    {
        //
        $id = $request->id;
        try {
            Stream::find($id)->delete();
            return back()->with('success', "Stream has been successfully deleted");
        }catch (\Exception $exception){
            //dd($exception);
            return back()->with('error', "Something went wrong");
        }
    }

    public function addUpdateStreamSummary(Request $request){

        $validator = Validator::make($request->all(), [
            'summary' => ['required', 'string', 'max:255'],
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

            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Summary saved successfully!');
    }

    public function render($id)
    {
        $stream = Stream::where('id', $id)->first();
        $stream_answer = StreamAnswer::where('stream_id', $id)->first();
        if ($stream_answer){
            $stream_answer_id = $stream_answer->id;
            $answer_array = (array)json_decode($stream_answer->answers);
        }else{
            $stream_answer_id = null;
            $answer_array = array();
        }

        return view('streams.render')->with(compact('stream', 'answer_array', 'stream_answer_id'));
    }

    public function streamPost(Request $request)
    {
        $stream_id = $request->stream_id;
        $stream_answer_id = $request->stream_answer_id;

        $inputs = $request->except('_token', 'stream_id', 'stream_answer_id');
        if ($request->image){
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('stream_answer_image'), $imageName);
            $inputs['image'] = $imageName;
        }

        $data_array = array(
            'stream_id' => $stream_id,
            'answers' => json_encode($inputs),
            'created_by' => auth()->user()->id,
        );

        if (empty($stream_answer_id)){
            StreamAnswer::create($data_array);
        }else{
            StreamAnswer::where('id', $stream_answer_id)->update($data_array);
        }
        return redirect()->route('dashboard')->with('success', 'Data saved successfully!');
    }
}
