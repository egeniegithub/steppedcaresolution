<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Permission;
use App\Models\SpecialForm;
use App\Models\Stream;
use App\Models\StreamAccess;
use App\Models\StreamAnswer;
use App\Models\StreamChangeLog;
use App\Models\StreamField;
use App\Models\StreamFieldGrid;
use App\Models\StreamFieldValue;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            return redirect()->route('dashboard');
        }

        $streams = Stream::leftjoin('forms as f', 'f.id', '=', 'streams.form_id')
            ->leftjoin('projects as p', 'p.id', '=', 'f.project_id')
            ->where('form_id', $form_id)
            ->select(
                'streams.id as stream_id', 'streams.name as stream_name', 'streams.status as stream_status', 'f.name as form_name', 'p.name as project_name', 'streams.order_count'
            )
            ->orderBy('streams.order_count', 'ASC')
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
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            return redirect()->route('dashboard');
        }

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
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            return redirect()->route('dashboard');
        }

        //dd($request);
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user = auth()->user();
            $input = $request->input();
            $previous_order_count = Stream::where('form_id', $input['form_id'])->max('order_count');

            $stream = array(
                'name' => $input['name'],
                'form_id' => $input['form_id'],
                'order_count' => $previous_order_count+1,
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
        return redirect()->route('dashboard.streams', [$request->form_id])->with('success', 'Form saved successfully!');
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
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */

    public function edit($form_id, $stream_id = null)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            return redirect()->route('dashboard');
        }

        $stream = null;
        $fields = [];
        if (!empty($stream_id)) {
            $stream = Stream::where('id', $stream_id)->with(['getFields'])->first();
            $fields = $stream->getFields;
        }
        return view('streams.edit')->with(compact('form_id', 'stream', 'fields'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Stream $stream
     * @return \Illuminate\Http\RedirectResponse
     */

    public function update(Request $request)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            return redirect()->route('dashboard');
        }

        //dd($request);
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $stream_id = $request->stream_id;
            $user = auth()->user();
            $input = $request->input();

            $stream = array(
                'name' => $input['name'],
                'form_id' => $input['form_id'],
                'fields' => null,
                'status' => 'Draft'
            );

            DB::beginTransaction();
            $updated_stream = Stream::where('id', $stream_id)->update($stream);

            $fields = [];
            foreach ($input['fields'] as $field) {

                $fields[] = [
                    'id' => !empty($field['id']) ? $field['id'] : null,
                    'stream_id' => $stream_id,
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
                if ($field['id'] == "null"){
                    $stream_field = StreamField::create($field);
                    if (!empty($field['tableData'])){

                        $grid_data = json_decode(urldecode($field['tableData']));
                        foreach ($grid_data as $grid) {
                            $table_fields = array(
                                'name' => $grid->name,
                                'type' => $grid->type,
                                'is_dropdown' => $grid->is_dropdown == 'no' ? 0 : 1,
                                'field_options' => $grid->field_options,
                                'order_count' => $grid->order_count,
                                'stream_field_id' => $stream_field->id
                            );
                            StreamFieldGrid::create($table_fields);
                        }
                    }
                }else{
                    StreamField::where('id',$field['id'])->update($field);
                    if (!empty($field['tableData'])){
                        $grid_data = json_decode(urldecode($field['tableData']));
                        foreach ($grid_data as $grid) {
                            $formatted_name =  str_replace('+', ' ',$grid->name);
                            $table_fields = array(
                                'id' => !empty($grid->id) ? $grid->id : null,
                                'name' => $formatted_name,
                                'type' => $grid->type,
                                'is_dropdown' => $grid->is_dropdown == 'no' ? 0 : 1,
                                'field_options' => $grid->field_options,
                                'order_count' => $grid->order_count,
                                'stream_field_id' => $field['id']
                            );
                            if ($grid->id == "null"){
                                StreamFieldGrid::create($table_fields);
                            }else{
                                StreamFieldGrid::where('id',$grid->id)->update($table_fields);
                            }
                        }
                    }
                }
            }

            $permission_ids = Permission::where('stream_id', $stream_id)->pluck('id')->toArray();

            $user_ids = StreamAccess::whereIn('permission_id', $permission_ids)
                ->whereNotNull('assigned_user_id')
                ->groupBy('assigned_user_id')
                ->pluck('assigned_user_id')
                ->toArray();

            foreach ($user_ids as $user_id) {
                $user = User::where('id', $user_id)->first();

                if (!empty($user)){
                    $data = array(
                        'username' => $user->firstname. ' '.$user->lastname,
                        'email' => $user->email,
                        'subject' => 'Update Form Notification',
                        'text' => 'Admin has updated the Form "'.$input['name'].'" that you have been assigned'
                    );

                    try {
                        // fire email to notify users who have permission of this stream
                        Mail::send('emails.notify_stream_update', compact('data'), function($message) use ($data){
                            $message->to($data['email'])
                                ->subject($data['subject'])
                                ->from('do-not-reply@steppedcaresolutions.com', 'SCS Team');
                        });
                    } catch (Exception $e) {
                        return back()->with('warning', 'Email configuration error');
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('dashboard.streams', [$request->form_id])->with('success', 'Form saved successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Stream $stream
     * @return \Illuminate\Http\RedirectResponse
     */

    public function destroy(Request $request)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            return redirect()->route('dashboard');
        }

        $id = $request->id;
        try {
            StreamChangeLog::where('stream_id', $id)->delete();
            StreamField::where('stream_id', $id)->delete();
            Stream::where('id', $id)->delete();
            return back()->with('success', "Form has deleted successfully ");
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            return back()->with('error', "Something went wrong");
        }
    }

    public function addUpdateStreamSummary(Request $request)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            return redirect()->route('dashboard');
        }

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
        $user = auth()->user();
        $stream_id = $request->stream_id;

        $stream = Stream::where('id', $stream_id)->first();

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

            $status = $request->submit == 'Save Only' ? 'In-progress' : 'Published';

            Stream::whereId($stream_id)->update([
                'status' => $status
            ]);

            $permission_ids = Permission::where('stream_id', $stream_id)->pluck('id')->toArray();
            $user_ids = StreamAccess::whereIn('permission_id', $permission_ids)
                ->whereNotNull('assigned_user_id')
                ->groupBy('assigned_user_id')
                ->pluck('assigned_user_id')
                ->toArray();

            if (!empty($user_ids)){
                foreach ($user_ids as $user_id) {
                    $user = User::where('id', $user_id)->first();

                    if (!empty($user)){
                        $data = array(
                            'username' => $user->firstname. ' '.$user->lastname,
                            'email' => $user->email,
                            'subject' => 'Update Form Notification',
                            'text' => 'Form "'.$stream->name.'" status has been changed to "'.$status.'"'
                        );

                        try {
                            // fire email to notify users who have permission of this stream
                            Mail::send('emails.notify_stream_update', compact('data'), function($message) use ($data){
                                $message->to($data['email'])
                                    ->subject($data['subject'])
                                    ->from('do-not-reply@steppedcaresolutions.com', 'SCS Team');
                            });
                            \Log::info('in try');
                        } catch (Exception $e) {
                            \Log::info($e->getMessage());
                            return back()->with('warning', 'Email configuration error');
                        }
                    }
                }
            }

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

        if ($user->role == 'User'){
            return redirect()->route('dashboard')->with('success', 'Data saved successfully!');
        }else{
            return redirect()->route('dashboard.streams', [$stream->form_id])->with('success', 'Data saved successfully!');
        }
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

            $permission_ids = Permission::where('stream_id', $request->id)->pluck('id')->toArray();
            $user_ids = StreamAccess::whereIn('permission_id', $permission_ids)
                ->whereNotNull('assigned_user_id')
                ->groupBy('assigned_user_id')
                ->pluck('assigned_user_id')
                ->toArray();

            if (!empty($user_ids)){
                foreach ($user_ids as $user_id) {
                    $user = User::where('id', $user_id)->first();

                    if (!empty($user)){
                        $data = array(
                            'username' => $user->firstname. ' '.$user->lastname,
                            'email' => $user->email,
                            'subject' => 'Update Form Notification',
                            'text' => 'Form "'.$stream->name.'" status has been changed to "'.$input['status'].'"'
                        );

                        try {
                            // fire email to notify users who have permission of this stream
                            Mail::send('emails.notify_stream_update', compact('data'), function($message) use ($data){
                                $message->to($data['email'])
                                    ->subject($data['subject'])
                                    ->from('do-not-reply@steppedcaresolutions.com', 'SCS Team');
                            });
                        } catch (Exception $e) {
                            return back()->with('warning', 'Email configuration error');
                        }

                    }
                }
            }

        } catch (\Exception $e) {

            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Status updated successfully!');
    }

    public function staticStream(Request $request){

        $input = $request->except('_token');
        $input['project_id'] = auth()->user()->project_id;
        $input['vendor_id'] = auth()->user()->vendor_id;
        $input['user_id'] = auth()->id();

        $data = SpecialForm::where('period_id', $input['period_id'])
            ->where('project_id', $input['project_id'])
            ->where('vendor_id', $input['vendor_id'])
            ->where('user_id', $input['user_id'])
            ->first();

        if (empty($data)){
            $data = [];
        }else{
            $data = $data->toArray();
        }

        return view('streams.static_form')->with(compact('data', 'input'));
    }

    public function specialFormPost(Request $request)
    {
        $id = $request->input('id');
        $input = $request->except('_token', 'submit', 'id');
        if ($request->submit == 'Save Only'){
            $input['status'] = 'In-progress';
        }else{
            $input['status'] = 'Published';
        }

        if (empty($id)){
            SpecialForm::create($input);
        }else{
            SpecialForm::where('id', $id)->update($input);
        }
        return redirect()->route('dashboard')->with('success','Data saved successfully!');
    }

    public function streamField(Request $request)
    {
        $id = $request->id;
        StreamFieldGrid::where('stream_field_id',$id)->delete();
        StreamField::where('id',$id)->delete();
        return back()->with('success','Field deleted successfully.');
    }

    public function deleteGridField(Request $request)
    {
        $id = $request->id;
        StreamFieldGrid::where('id',$id)->delete();
        return back()->with('success','Grid field deleted successfully.');
    }

    // save stream order
    public function streamOrder(Request $request)
    {
        $id = $request->stream_id;
        Stream::where('id',$id)->update(['order_count' => $request->value]);
        return back()->with('success','Order Saved successfully.');
    }
}
