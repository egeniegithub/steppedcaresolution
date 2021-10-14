@extends('layouts.app')

@section('title', 'Edit Period')

@section('content')
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="container">
                <div class="row blue-border-bottom">
                    <div class="col-sm-12 col-md-12 px-0">
                        <div class="top-header pt-2 ">
                            <h3 class="margin-page-title">Edit Period</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table_div_padding">
                            @include('layouts.flash-message')
                            <form  method="POST" action="{{ route('dashboard.period.update', [$period->id]) }}">
                                @csrf
                                <div class="card mb-0 pt-4 mb-4">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-lg-6 col-x-6 col-md-6 col-12">
                                                <div class="mb-4">
                                                    <label for="name" class="form-label">Name</label>
                                                    <input type="text" class="form-control" id="name" placeholder="Enter Period Name" name="name" value="{{$period->name}}" aria-describedby="name" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-x-6 col-md-6 col-12">
                                                <div class="mb-4">
                                                    <label for="status" class="form-label">Status</label>
                                                    <select class="form-control form-select" aria-label="Default select example" name="status" required>
                                                        <option selected>Select Status</option>
                                                        <option value="1" {{$period->status == 1 ? "selected" : ""}}>Active</option>
                                                        <option value="0" {{$period->status == 0 ? "selected" : ""}}>In-active</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-x-6 col-md-6 col-12">
                                                <div class="mb-4">
                                                    <label for="startdate" class="form-label">Start Date</label>
                                                    <input type="date" class="form-control" id="startdate" placeholder="Month 1" name="start_date" value="{{date('Y-m-d', strtotime($period->start_date))}}" aria-describedby="startdate" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-x-6 col-md-6 col-12">
                                                <div class="mb-4">
                                                    <label for="EndDate" class="form-label">End Date</label>
                                                    <input type="date" class="form-control" id="EndDate" placeholder="Month 1" name="end_date" value="{{date('Y-m-d', strtotime($period->end_date))}}" aria-describedby="EndDate" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{route('dashboard.periods')}}" class="btn btn-light text-white">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
