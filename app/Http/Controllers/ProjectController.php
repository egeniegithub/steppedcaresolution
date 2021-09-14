<?php

namespace App\Http\Controllers;

use App\Models\project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class ProjectController extends Controller
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

        $projects = project::when($search_keyword, function ($query, $value) {
            $query->where('name', 'like', '%' . $value . '%');
        })
            ->orderBy('id', 'DESC')
            ->paginate($perPage);

        $project_dropdown = project::all();
        $row_show = $perPage;
        return view('projects.index')->with(compact('projects', 'project_dropdown', 'row_show'));
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
            'image' => 'required|image|mimes:png,jpg,JPG,jpeg|max:4048',
        ]);

        if ($validator->fails()) {
            return back() ->withErrors($validator)->withInput();
        }

        $params=$request->except('_token');
        $params["name"]=$request->name;

        if ($request->file('image')) {
            $photo = $request->file('image');

            $image_name = time().'.'.$photo->extension();
            $photo->move(public_path('project_images'), $image_name);
            $params['image'] = $image_name;
        }
        project::create($params);
        return back()->with('success','Project created successfully!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        //dd($request);
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back() ->withErrors($validator)->withInput();
        }

        $params = $request->except('_token');
        $id = $request->id;
        $params["name"] = $request->name;

        if ($request->file('image')) {
            $photo = $request->file('image');

            $image_name = time().'.'.$photo->extension();
            $photo->move(public_path('project_images'), $image_name);
            $params['image'] = $image_name;
        }
        project::where('id', $id)->update($params);
        return back()->with('success','Project created successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $id = decrypt($request->ref);
        project::where('id', $id)->delete();
        return back()->with('success', 'Project deleted successfully!');
    }
}
