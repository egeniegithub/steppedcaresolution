<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FormController extends Controller
{
    public function index(Request $request)
    {
        $search_keyword = $request->input('keyword') ?? null;
        $active_user = User::where('id', auth()->user()->id)->first();
        $perPage = $request->show_rows ?? 10;

        $forms = Form::when($search_keyword, function ($query, $value) {
            $query->where('forms.name', 'like', '%' . $value . '%')
                ->orWhere('p.name', 'like', '%' . $value . '%');
        })
            ->leftjoin('projects as p', 'p.id', '=', 'forms.project_id')
            ->where(function ($q) use($active_user) {
                if ($active_user->role == 'Admin') {

                }else{
                    $q->where('forms.created_by', $active_user->id);
                }
            })
            ->select('forms.id AS form_id', 'forms.name as form_name', 'p.name as project_name', 'p.id as project_id')
            ->orderBy('form_id', 'DESC')
            ->paginate($perPage);

        $projects = project::all();
        $row_show = $perPage;
        return view('forms.index')->with(compact('projects', 'forms', 'active_user', 'row_show'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $input = $request->except('_token');
            $input['created_by'] = auth()->user()->id;

            Form::create($input);

        } catch (\Exception $e) {

            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Form created successfully!');
    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $input = $request->only('name', 'project_id');
            $input['updated_by'] = auth()->user()->id;

            Form::where('id', $request->id)->update($input);

        } catch (\Exception $e) {

            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Form updated successfully!');
    }

    public function delete(Request $request)
    {
        try {
            Form::find(decrypt($request->ref))->delete();
            return back()->with('success', 'Form deleted successfully!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
