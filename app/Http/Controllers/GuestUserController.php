<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
class GuestUserController extends Controller
{
    //
    private $LoginController;
    public function __construct()
    {
        $this->LoginController = new LoginController();
    }

    public  function registration_complete(){
        return view('emails.registration_complete');
    }

    public function activate_user_account($id)
        {
            try {
            $email = decrypt($id);

            }
            catch (\RuntimeException $e) {
            return redirect('login');
        }

            $data = User::where('email', $email)->first();
            if(!empty($data)){
                $user=User::find($data->id);
                $user->email_verified_at=\Carbon\Carbon::now();
                $user->status="Active";
                $user->save();
                Flash::success('Email Verified Successfully.');
                if($user->type=="client"){

                    Session::put('username',$user->first_name.' '.$user->last_name);
                    Session::put('type',$user->type);
                    return redirect('registration-complete');

                }else{
                 return redirect('login');
                }

            }else{
            return redirect('login');
        }
    }
}
