@extends('layouts.app')

@section('title', 'View Member')

@section('content')
<div class="pcoded-wrapper">
    <div class="pcoded-content">
        <div class="container">
            <div class="row blue-border-bottom">
                <div class="col-sm-12 col-md-12 px-0">
                    <div class="top-header pt-2 ">
                        <h3 class="margin-page-title">View User</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="table_div_padding">
                        <form action="#" readonly>
                            <div class="card mb-4">
                                <div class="card-header">Basic Info</div>
                                <div class="container">
                                    <div class="row pt-4">
                                        <div class="col-lg-6 col-x-6 col-md-6 col-12">
                                            <div class="mb-4">
                                                <label for="firstname" class="form-label">First Name *</label>
                                                <input type="text"  class="form-control" id="firstname" placeholder="Month 1" aria-describedby="firstname" value="{{$user->firstname??null}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-x-6 col-md-6 col-12">
                                            <div class="mb-4">
                                                <label for="lastname" class="form-label">Last Name</label>
                                                <input type="text"  class="form-control" id="lastname" placeholder="Month 1" aria-describedby="lastname" value="{{$user->lastname??null}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-x-6 col-md-6 col-12">
                                            <div class="mb-4">
                                                <label for="email" class="form-label">Email *</label>
                                                <input type="email"  class="form-control" id="email" placeholder="Month 1" aria-describedby="email" value="{{$user->email??null}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-x-6 col-md-6 col-12">
                                            <div class="mb-4">
                                                <label for="phone" class="form-label">Phone</label>
                                                <input type="tel"  class="form-control" id="phone" placeholder="Month 1" aria-describedby="phone" value="{{$user->phone??null}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-x-6 col-md-6 col-12">
                                            <div class="mb-4">
                                                <label for="Address" class="form-label">Address</label>
                                                <input type="text"  class="form-control" id="Address" placeholder="Month 1" aria-describedby="Address" value="{{$user->address??null}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-x-6 col-md-6 col-12">
                                            <div class="mb-4">
                                                <label for="zip" class="form-label">Zip / Postal Code</label>
                                                <input type="text"  class="form-control" id="zip" placeholder="Month 1" aria-describedby="zip" value="{{$user->zip??null}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-x-6 col-md-6 col-12">
                                            <div class="mb-4">
                                                <label for="City" class="form-label">City</label>
                                                <input type="text"  class="form-control" id="City" placeholder="Month 1" aria-describedby="City" value="{{$user->city??null}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-x-6 col-md-6 col-12">
                                            <div class="mb-4">
                                                <label for="state" class="form-label">State / Province</label>
                                                <input type="text"  class="form-control" id="state" placeholder="Month 1" aria-describedby="state" value="{{$user->state??null}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-x-6 col-md-6 col-12">
                                        <div class="mb-4">
                                                <label for="Country" class="form-label">Country</label>
                                                <select class="form-control form-select" name="country" id="country" aria-label="Default select example" readonly disabled>
                                                    <option selected>Select Country</option>
                                                    @foreach($countries as $data)
                                                    <option value="{{$data->name}}" {{$user->country==$data->name?"selected":""}}>{{$data->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">Aditional Info</div>
                                <div class="container">
                                    <div class="row pt-4">
                                        <div class="col-lg-4 col-xs-12 col-md-4 col-12">
                                            <div class="mb-4">
                                                <label for="Type" class="form-label">Project</label>
                                                <select  class="form-control form-select" aria-label="Default select example" readonly disabled>
                                                    <option selected>Select Project</option>
                                                    @foreach($projects as $key=>$data)
                                                        <option value="{{$data->id}}" {{$user->project_id==$data->id?"selected":""}}>{{$data->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-xs-12 col-md-4 col-12">
                                        <div class="mb-4">
                                                <label for="Type" class="form-label">Type</label>
                                                <select class="form-control form-select" id="user_type" name="user_type" aria-label="Default select example" readonly disabled>
                                                    <option selected>Select Type</option>
                                                    @foreach(users_roles() as $data)
                                                        <option value="{{ $data}}" {{$user->role==$data?"selected":""}}>{{ $data}}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-xs-12 col-md-4 col-12">
                                            <div class="mb-4">
                                                <label for="Status" class="form-label">Status</label>
                                                <select class="form-control form-select" disabled aria-label="Default select example" readonly disabled>
                                                    <option selected>Select Status</option>
                                                    @foreach(user_status() as $data)
                                                        <option value="{{ $data}}"  {{$user->status==$data?"selected":""}}>{{ $data}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-xs-12 col-md-4 col-12">
                                            <div class="mb-4">
                                                <label for="Type" class="form-label">Vendor</label>
                                                <select class="form-control form-select" name="vendor_id" disabled id="vendor_id" aria-label="Default select example">
                                                    @foreach($vendors as $key=>$data)
                                                        <option value="{{$data->id}}" {{$user->vendor_id == $data->id ? "selected" :""}}>{{$data->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">User Details</div>
                                <div class="container">
                                    <div class="row pt-4">
                                        <div class="col-lg-2 col-xl-2 col-md-4 col-sm-6 col-12">
                                            <p class="user_details_font">Created On: {{\Carbon\Carbon::parse($user->created_at)->format("Y-m-d")}} </p>
                                        </div>
                                        <div class="col-lg-2 col-xl-2 col-md-4 col-sm-6 col-12">

                                        <p class="user_details_font">Created By: {{(created_BY($user->createdBy))}}</p>
                                        </div>
                                    </div>
                                    <div class="row pt-4">
                                        <div class="col-lg-2 col-xl-2 col-md-4 col-sm-6 col-12">
                                            <p class="user_details_font">Updated On: {{\Carbon\Carbon::parse($user->updated_at)->format("Y-m-d")}}
                                            </p>
                                        </div>
                                        <div class="col-lg-2 col-xl-2 col-md-4 col-sm-6 col-12">
                                            <p class="user_details_font"> Updated By: {{updated_BY($user->updatedBy)}}</p>
                                        </div>
                                    </div>
                                    {{--<div class="row pt-4">
                                        <div class="col-lg-2 col-xl-2 col-md-4 col-sm-6 col-12">
                                            <p class="user_details_font">Last Login: {{\Carbon\Carbon::parse($user->last_login)}}</p>
                                        </div>
                                    </div>--}}
                                </div>
                            </div>
                            <!--<button class="btn btn-primary">Back</button>
                            <a href="{{route('dashboard.users')}}" type="button" class="btn btn-light text-white">Cancel</a>-->
                            <a href="{{route('dashboard.users')}}" type="button" class="btn btn-primary">Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
