@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="container">
                @include('layouts.flash-message')
                <div class="row blue-border-bottom">
                    <div class="col-sm-6 col-md-9 col-lg-10 px-0">
                        <div class="top-header pt-2">
                            <h3 class="margin-page-title">Vendor Dashboard</h3>
                        </div>
                    </div>
                </div>
                <form method="POST" action="{{ route('dashboard.stream.static_stream') }}">
                    @csrf
                    <div class="row  blue-border-bottom">
                        <div class="col-sm-12 col-md-3 px-0">
                            <p class="pl-4 mt-2 mb-0"> Select Period </p>
                            <div class="form-group pl-4 pt-1 d-flex search_bar_adj">
                                <select class="form-control form-select white_input" name="period_id" aria-label="Default select example">
                                    {{--<option value="" selected>All</option>--}}
                                    @foreach($periods as $period)
                                        <option value="{{$period->id}}" {{($current_period_id ? $current_period_id : request()->get('period_id')) == $period->id?"selected":""}}>{{$period->name}} ({{$period->start_date}} - {{$period->end_date}})</option>
                                    @endforeach
                                </select>
                                <button class="span_search span_mid"><i class="fas fa-search search_icon"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('layouts.pagination')
@endsection
