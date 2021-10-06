@extends('layouts.app')

@section('title', 'Create Member')

@section('content')
    <style>
        .custom-file-upload {
            border: 1px solid #ccc;
            display: inline-block;
            padding: 6px 12px;
            cursor: pointer;
        }
    </style>
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="container">

                <div class="row blue-border-bottom">
                    <div class="col-sm-12 col-md-12 px-0">
                        <div class="top-header pt-2 ">
                            <h3 class="margin-page-title">Add User</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="table_div_padding">
                            @include('layouts.flash-message')
                            <form method="POST" action="{{ route('dashboard.user.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="card mb-4">
                                    <div class="card-header">Basic Info</div>
                                    <div class="container">
                                        <div class="row pt-4">
                                            <div class="col-lg-6 col-x-6 col-md-6 col-12">
                                                <div class="mb-4">
                                                    <label for="firstname" class="form-label">First Name *</label>
                                                    <input type="text" class="form-control" name="firstname" id="firstname" placeholder="firstname" aria-describedby="firstname" value="{{ old('firstname') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-x-6 col-md-6 col-12">
                                                <div class="mb-4">
                                                    <label for="lastname" class="form-label">Last Name *</label>
                                                    <input type="text" class="form-control" name="lastname" id="lastname" placeholder="lastname" aria-describedby="lastname" value="{{ old('lastname') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-x-6 col-md-6 col-12">
                                                <div class="mb-4">
                                                    <label for="email" class="form-label">Email *</label>
                                                    <input type="email" class="form-control" name="email" id="email" placeholder="Email" aria-describedby="email" value="{{ old('email') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-x-6 col-md-6 col-12">
                                                <div class="mb-4">
                                                    <label for="email" class="form-label">Password *</label>
                                                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" aria-describedby="password" value="{{ old('password') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-x-6 col-md-6 col-12">
                                                <div class="mb-4">
                                                    <label for="phone" class="form-label">Phone *</label>
                                                    <input type="tel" class="form-control" name="phone" id="phone" placeholder="Phone" aria-describedby="phone" value="{{ old('phone') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-x-6 col-md-6 col-12">
                                                <div class="mb-4">
                                                    <label for="Address" class="form-label">Address</label>
                                                    <input type="text" class="form-control" id="address" placeholder="Address" name="address" aria-describedby="Address" value="{{ old('address') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-x-6 col-md-6 col-12">
                                                <div class="mb-4">
                                                    <label for="zip" class="form-label">Zip / Postal Code</label>
                                                    <input type="text" class="form-control" name="zip" id="zip" placeholder="Zip" aria-describedby="zip" value="{{ old('zip') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-x-6 col-md-6 col-12">
                                                <div class="mb-4">
                                                    <label for="City" class="form-label">City</label>
                                                    <input type="text" class="form-control" id="city" name="city" placeholder="City" aria-describedby="City" value="{{ old('city') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-x-6 col-md-6 col-12">
                                                <div class="mb-4">
                                                    <label for="state" class="form-label">State / Province</label>
                                                    <input type="text" class="form-control" id="state" name="state" placeholder="State" aria-describedby="state" value="{{ old('state') }}">
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-x-6 col-md-6 col-12">
                                                <div class="mb-4">
                                                    <label for="Country" class="form-label">Country</label>
                                                    <select class="form-control form-select" name="country" id="country" aria-label="Default select example" >
                                                        <option selected disabled>Select Country</option>
                                                        @foreach($countries as $data)
                                                        <option value="{{$data->name}}" {{old('country')== $data->name ? "selected" :""}}>{{$data->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">Additional Info</div>
                                    <div class="container">
                                        <div class="row pt-4">
                                            <div class="col-lg-4 col-xs-12 col-md-4 col-12">
                                                <div class="mb-4">
                                                    <label for="Type" class="form-label">Project *</label>
                                                    <select class="form-control form-select" name="project_id" id="project_id" aria-label="Default select example">
                                                        <option selected disabled>Select Project</option>
                                                        @foreach($projects as $key=>$data)
                                                            <option value="{{$data->id}}" {{old('project_id')== $data->id ? "selected" :""}}>{{$data->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-xs-12 col-md-4 col-12">
                                                <div class="mb-4">
                                                    <label for="Type" class="form-label">Type *</label>
                                                    <select class="form-control form-select" id="role" name="role" aria-label="Default select example" required>
                                                        <option selected disabled>Select Type</option>
                                                        @foreach(users_roles() as $data)
                                                        <option value="{{ $data}}" {{old('role')== $data ? "selected" :""}}>{{ $data}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-xs-12 col-md-4 col-12">
                                                <div class="mb-4">
                                                    <label for="Status" class="form-label">Status *</label>
                                                    <select class="form-control form-select" name="status" id="status" aria-label="Default select example" required>
                                                        <option selected disabled>Select Status</option>
                                                        @foreach(user_status() as $data)
                                                        <option value="{{ $data}}" {{old('status')== $data ? "selected" :""}}>{{ $data}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-xs-12 col-md-4 col-12">
                                                <div class="mb-4">
                                                    <label for="Type" class="form-label">Vendor</label>
                                                    <select class="form-control form-select" name="vendor_id" id="vendor_id" aria-label="Default select example">
                                                        <option selected disabled>Select Vendor</option>
                                                        @foreach($vendors as $key=>$data)
                                                            <option value="{{$data->id}}" {{old('vendor_id')== $data->id ? "selected" :""}}>{{$data->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-x-6 col-md-6 col-12 stream_update_title">
                                                <div>
                                                    <b data-toggle="modal" data-target="#exampleModal"><a class="add_icon" style="cursor:pointer"><span><i class="fas fa-plus-circle"></i></span><span> Add Vendor</span></a></b>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary">Save</button>
                                <a href="{{route('dashboard.users')}}" type="reset" class="btn btn-light text-white">Cancel</a>
                            </form>
                            @include('modals.add_vendor_modal')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>
        function createVendor(){
            document.getElementById("js_add_vendor").submit(function(){

            });
        }

        $('#file-upload').change(function() {
            var i = $(this).prev('label').clone();
            var file = $('#file-upload')[0].files[0].name;
            $(this).prev('label').text(file);
        });
    </script>
@endsection
