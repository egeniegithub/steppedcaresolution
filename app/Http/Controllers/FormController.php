<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormController extends Controller
{
    public function index(){
        return view("forms.index");
    }
    public function create(){
        return view("forms.create");
    }
    public function edit(){
        return view("forms.edit");
    }
    public function show(){
        return view("forms.show");
    }
    public function stream(Request $request){
        $stream=$request->id??null;
        if(!empty($stream)){
            return view("forms.stream");
        }else{
            return view("forms.index");   
        }
    }
    public function addstream(){
        return view("forms.addstream");
    }
    
}
