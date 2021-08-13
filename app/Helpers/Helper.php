<?php

use Illuminate\Support\Facades\Auth;
//
if (!function_exists('user_email')) {
    function user_email()
    {
        //
        $email = Auth::user()->email;
        //
        return $email;
    }
}

//
if (!function_exists('users_roles')) {
    function users_roles()
    {
        //
        $roles = ["Admin", "Manager", "User"];
        //
        return  $roles;
    }
}
//

//
if (!function_exists('user_status')) {
    function user_status()
    {
        //
        $roles = ["Active", "Disable"];
        //
        return  $roles;
    }
}

//
if (!function_exists('created_BY')) {
    function created_BY($id)
    {
        //
        $user = \DB::table("users")->select(DB::raw("CONCAT(firstname,lastname) as username"))->where("id", $id)->get()->first();

        if (!empty($user)) {
            return $user->username;
        } else {
            return null;
        }

        //
    }
}

//
if (!function_exists('updated_BY')) {
    function updated_BY($id)
    {
        //
        $user = \DB::table("users")->select(DB::raw("CONCAT(firstname,lastname) as username"))->where("id", $id)->get()->first();
        if (!empty($user)) {
            return $user->username;
        } else {
            return null;
        }
        //
    }
}
//

// check status if form is completed
if (!function_exists('formStatus')) {
    function formStatus($form_id)
    {
        $streams = \App\Models\Stream::where('form_id', $form_id)->groupBy('status')->get();

        if ($streams->count() == 1){
            if ($streams[0]->status == 'Published'){
                return 'Completed';
            }else{
                return 'In-progress';
            }
        }else{
            return 'In-progress';
        }
    }
}
