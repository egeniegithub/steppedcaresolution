<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\project;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
//use Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
class UserController extends Controller
{
    protected $projects_model;

    function __construct()
    {
        $this->projects_model = new project;
    }

    public function index(Request $request)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            abort(403, 'Unauthorized access.');
        }

        $searchQuery = $request->keyword ?? null;
        $type = $request->type ?? "all";
        $project = $request->project_id ?? "all";
        $perPage = $request->show_rows ?? 10;
        $active_user = User::where('id', auth()->user()->id)->first();

        if ($type == "all") {
            $roles = users_roles();
        } else {
            $roles = [$type];
        }

        $projects = $this->projects_model->search_Projects($project);

        $data = [];
        $data["users"] = User::when($searchQuery, function ($x, $q) {
            $x->where('email', 'like', '%' . $q . '%')
                ->orwhere("firstname", 'like', '%' . $q . '%')
                ->orwhere("lastname", 'like', '%' . $q . '%')
                ->orwhere("phone", 'like', '%' . $q . '%')
                ->orwhere("role", 'like', '%' . $q . '%');
        })
            ->when($roles, function ($x, $q) {
                $x->whereIn('role', $q);
            })
            ->when($projects, function ($x, $q) {
                $x->whereIn('project_id', $q);
            })
            ->where(function ($q) use($active_user) {
                if ($active_user->role == 'Admin') {

                }else{
                    $q->where('project_id', $active_user->project_id);
                }
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        $data["projects"] = $this->projects_model->all_Projects();
        $data['row_show'] = $perPage;
        return view('members.index', $data);
    }

    public function create()
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            abort(403, 'Unauthorized access.');
        }

        $data = [];
        $data["projects"] = $this->projects_model->all_Projects();
        $data["countries"] = DB::table("countries")->get();
        $data["vendors"] = Vendor::all();
        return view('members.create', $data);
    }

    public function store(Request  $request)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            abort(403, 'Unauthorized access.');
        }

        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => 'required',
            'status' => 'required',
            'project_id'=>'required'
        ],
        [
            'role.required' => 'Please choose User Type!',
            'status.required' => 'Please choose User Status!',
            'project_id.required' => 'Please choose Project!'
         ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $params = $request->except('_token');
        $params["name"] = $request->firstname . ' ' . $request->lastname;
        $params["project_id"] = $request->project_id;
        $params["role"] = $request->role;
        $params["vendor_id"] = $request->vendor_id;
        $params["password"] = bcrypt($request->password);
        $params["createdBy"] = auth()->user()->id;

        $user = new User;
        $user->create($params);

        $data['email']= $request->email;
        $token = Str::random(64);
        $data['url']=(route('reset.password.get',$token));
        $data['subject'] = "Update Your Password";
        $data['msg'] = "Welcome to Stepped Care Solutions";
        $data['username']  = $request->firstname. ' '.$request->lastname;

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
          ]);

        //dd($data);

        try {
            Mail::send('emails.reset', $data, function($message) use ($data){
                $message->to($data['email'])
                    ->subject($data['subject'])
                    ->from('ashakoor@egenienext.com', 'Stepped Care Solutions' );
            });
            return redirect()->route('dashboard.users')->with('success', 'Member created successfully! check email to update password don\'t forget to check spam. ');
        } catch (Exception $e) {
            return back()->with('warning', 'Issue with the email, But do not worry Member created successfully');
        }
    }

    public function edit(Request $request)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            abort(403, 'Unauthorized access.');
        }

        $data = [];
        $data["user"] = User::find(decrypt($request->ref));
        $data["projects"] = $this->projects_model->all_Projects();;
        $data["countries"] = DB::table("countries")->get();

        $data["additional_info"] =DB::table("users as parent")->select(
            DB::raw("CONCAT(creator.firstname,creator.lastname) AS created"),
            DB::raw("CONCAT(updator.firstname,updator.lastname) AS updated")
        )
        ->leftjoin("users as creator", "creator.createdBy", "parent.id")
        ->leftjoin("users as updator", "updator.updatedBy",  "parent.id")
        ->where("parent.id",decrypt($request->ref))->get();
        $data["vendors"] = Vendor::all();
        return view('members.edit', $data);
    }

    public function update(Request $request)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            abort(403, 'Unauthorized access.');
        }

        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'role' => ['required'],
            'status' => 'required',
            'project_id'=>'required'
        ],
        [
            'role.required' => 'Please choose User Type!',
            'status.required' => 'Please choose User Status!',
            'project_id.required' => 'Please choose Project!'
         ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $params = $request->except(['_token','password']);
        $params["name"] = $request->firstname . ' ' . $request->lastname;
        $params["role"] = $request->role;
        $params["vendor_id"] = $request->vendor_id;
        $params["project_id"] = $request->project_id;
        $params["updatedBy"] = auth()->user()->id;
        $id = $request->id;
        try {

            $user = User::find($id);
            $user->update($params);

            return redirect()->route('dashboard.users')->with('success', 'Member updated successfully!');
        } catch (Exception $e) {
            return back()->with('success', 'Member updated successfully!');
        }
    }

     public function show(Request $request)
     {
         if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
             abort(403, 'Unauthorized access.');
         }

         $data = [];
         $data["user"] = User::find(decrypt($request->ref));
         $data["projects"] = $this->projects_model->all_Projects();
         $data["countries"] = DB::table("countries")->get();
         $data["vendors"] = Vendor::all();
         return view('members.show', $data);
     }

    public function delete(Request $request)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            abort(403, 'Unauthorized access.');
        }

        try {
            User::find(decrypt($request->ref))->delete();
            return back()->with('success', 'Member deleted successfully!');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
