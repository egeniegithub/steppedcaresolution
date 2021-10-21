<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Period;
use App\Models\project;
use App\Models\Stream;
use App\Models\StreamField;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FormController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            abort(403, 'Unauthorized access.');
        }

        $search_keyword = $request->input('keyword') ?? null;
        $active_user = User::where('id', auth()->user()->id)->first();
        $perPage = $request->show_rows ?? 10;

        if (!empty($request->period_id)){
            $period_id = $request->period_id;
        }else{
            $period_id = null;
        }

        $forms = Form::when($search_keyword, function ($query, $value) {
            $query->where('forms.name', 'like', '%' . $value . '%')
                ->orWhere('p.name', 'like', '%' . $value . '%');
        })
            ->leftjoin('projects as p', 'p.id', '=', 'forms.project_id')
            ->leftjoin('periods as pe', 'pe.id', '=', 'forms.period_id')
            ->where(function ($q) use($active_user) {
                if ($active_user->role == 'Admin') {

                }else{
                    $q->where('p.id', $active_user->project_id);
                }
            })
            ->where(function ($q) use($period_id) {
                if ($period_id) {
                    $q->where('period_id', $period_id);
                }
            })

            ->select('forms.id AS form_id', 'forms.name as form_name', 'forms.order_count', 'p.name as project_name', 'p.id as project_id', 'forms.period_id as period_id', 'pe.name as period_name', 'is_special')
            ->orderBy('project_id', 'DESC')
            ->orderBy('order_count', 'ASC')
            ->paginate($perPage);

        $projects = project::all();
        $row_show = $perPage;
        $periods = Period::all();
        return view('forms.index')->with(compact('projects', 'forms', 'active_user', 'row_show', 'periods'));
    }


    public function store(Request $request)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            abort(403, 'Unauthorized access.');
        }
        //dd($request->input());
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $current_period = Period::all()->filter(function($item) {
                if (Carbon::now()->between($item->start_date, $item->end_date)) {
                    return $item;
                }
            })->first();

            /*if (!empty($current_period)){
                $period_id = $current_period->id;
            }else{
                return back()->with('error', 'Add period which contains current date before adding stream!');
            }*/

            $input = $request->except('_token');
            if ($request->exists('is_special')){
                $input['is_special'] = 1;
            }else{
                $input['is_special'] = null;
            }

            //previous order count
            $previous_order_count = Form::where('period_id', $input['period_id'])->where('project_id', $input['project_id'])->max('order_count');
            $input['order_count'] = $previous_order_count+1;
            $input['period_id'] = $input['period_id'] ?? $current_period;
            $input['created_by'] = auth()->user()->id;

            $check_is_special = Form::where('project_id', $input['project_id'])->where('period_id', $input['period_id'])->where('is_special', 1)->count();

            if ($check_is_special > 0 && $input['is_special'] != null){
                return back()->with('warning', 'Special Form is already exist for current period and project');
            }else{
                Form::create($input);
            }

        } catch (\Exception $e) {

            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Stream created successfully!');
    }

    public function update(Request $request)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            abort(403, 'Unauthorized access.');
        }
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $input = $request->except('_token');
            $input['updated_by'] = auth()->user()->id;

            Form::where('id', $request->id)->update($input);

        } catch (\Exception $e) {

            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Stream updated successfully!');
    }

    public function delete(Request $request)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            abort(403, 'Unauthorized access.');
        }
        try {
            $id = decrypt($request->ref);
            $stream_ids = Stream::where('form_id', $id)->pluck('id')->toArray();

            DB::beginTransaction();
            // delete all previous data
            StreamField::whereIn('stream_id', $stream_ids)->delete();
            Stream::whereIn('id', $stream_ids)->delete();
            Form::where('id', $id)->delete();

            DB::commit();
            return back()->with('success', 'Stream deleted successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function addUpdateFormSummary(Request $request)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            abort(403, 'Unauthorized access.');
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

            $stream = Form::find($request->id);
            $stream->update($input);
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Summary saved successfully!');
    }

    // save form order
    public function formOrder(Request $request)
    {
        $id = $request->form_id;
        Form::where('id',$id)->update(['order_count' => $request->value]);
        return back()->with('success','Order Saved successfully.');
    }
}
