<?php

namespace App\Http\Controllers;

use App\Models\SpecialForm;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            return redirect()->route('dashboard');
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255']
        ]);

        if ($validator->fails()) {
            return back() ->withErrors($validator)->withInput();
        }

        $params=$request->except('_token');
        $params["name"]=$request->name;

        Vendor::create($params);
        return back()->with('success','Vendor created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show(Vendor $vendor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit(Vendor $vendor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vendor  $vendor
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
            return back() ->withErrors($validator)->withInput();
        }

        $params = $request->except('_token');
        $id = $request->id;
        $params["name"] = $request->name;

        Vendor::where('id', $id)->update($params);
        return back()->with('success','Vendor created successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        if (Auth::user()->role=="User" || Auth::user()->role=="Vendor"){
            return redirect()->route('dashboard');
        }

        $id = decrypt($request->ref);
        Vendor::where('id', $id)->delete();
        return back()->with('success', 'Vendor deleted successfully!');
    }
}
