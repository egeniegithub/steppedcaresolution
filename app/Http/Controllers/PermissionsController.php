<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Period;
use App\Models\Permission;
use App\Models\project;
use App\Models\Stream;
use App\Models\StreamAccess;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PermissionsController extends Controller
{

    public function create()
    {
        $active_user = User::where('id', auth()->user()->id)->first();
        if ($active_user->role != 'Admin'){
            $forms = Form::where('project_id', $active_user->project_id)->get();
        }else{
            $forms = (object) array();
        }

        $periods = Period::all();
        $projects = project::all();
        $users = User::whereNotIn('role', ['Admin'])->get();

        return view("Permissions.create")->with(compact('projects','active_user', 'forms', 'users', 'periods'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        //dd(Carbon::now());
        $validator = Validator::make($request->all(), [
            'period_id' => ['required'],
            'project_id' => ['required'],
            'form_id' => ['required'],
            'stream_id' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {

            DB::beginTransaction();
            $input = $request->except('_token');
            $input['created_by'] = auth()->user()->id;

            // create permissions
            $permission = Permission::create($input);

            $assigned_users = explode(',', $request->assigned_user);
            $unassigned_users = explode(',', $request->unassigned_user);

            // declare arrays
            $assigned_user_data = array();
            $unassigned_user_data = array();

            // Construct array of assigned users
            if (!empty($request->assigned_user)){
                foreach ($assigned_users as $assigned_user) {
                    $assigned_data = array(
                        'permission_id' => $permission->id,
                        'assigned_user_id' => $assigned_user,
                        'unassigned_user_id' => NULL,
                        'created_by' => auth()->user()->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    );
                    array_push($assigned_user_data, $assigned_data);
                }
            }

            // Construct array of unassigned users
            if (!empty($request->unassigned_user)){
                foreach ($unassigned_users as $unassigned_user) {
                    $unassigned_data = array(
                        'permission_id' => $permission->id,
                        'assigned_user_id' => NULL,
                        'unassigned_user_id' => $unassigned_user,
                        'created_by' => auth()->user()->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    );
                    array_push($unassigned_user_data, $unassigned_data);
                }
            }
            $final_data = array_merge($unassigned_user_data, $assigned_user_data);
            StreamAccess::insert($final_data);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('dashboard.permissions', [$request->form_id])->with('success', 'permissions created successfully!');
    }

    public function getUsers($project_id)
    {
        $users = User::where('project_id', $project_id)->whereNotIn('role', ['Admin'])->pluck("name","id");
        return response()->json($users);
    }

    public function getForms($project_id)
    {
        $forms = Form::where('project_id', $project_id)->pluck("name","id");
        return response()->json($forms);
    }

    public function getStreams($form_id)
    {
        $streams = Stream::where('form_id', $form_id)->pluck("name","id");
        return response()->json($streams);
    }
}
