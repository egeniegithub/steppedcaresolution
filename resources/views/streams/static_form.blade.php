@extends('layouts.app')

@section('title', 'Update Data')

@section('content')
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="container">
                <div class="row blue-border-bottom">
                    <div class="col-sm-6 col-md-4 col-lg-4 px-0 stream_update_title">
                        <div class="top-header pt-2">
                            <h3 class="margin-page-title">Special Form</h3>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-4 px-0 update_stream_mid">
                        <div class="top-header pt-2">
                            <h3 class="margin-page-title">
                                status: <span class="blue_span">{{$data ? $data['status'] : 'Draft'}}</span>
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="table_div_padding">
                            @include('layouts.flash-message')
                            <div class="card mb-0">
                                <div class="card_header">
                                    @php
                                        $period_name = \App\Models\Period::where('id', $data ? $data['period_id'] : $input['period_id'])
                                            ->select(DB::raw("CONCAT(name,' (',DATE_FORMAT(start_date, '%d-%m-%Y'), ' - ', DATE_FORMAT(end_date, '%d-%m-%Y'), ')') as period_name"))
                                            ->first();
                                    @endphp
                                    <h5 class="header_padding_adj">Selected Period: {{$period_name->period_name}}</h5>
                                </div>
                                <form method="POST" action="{{ route('dashboard.stream.special_form_post') }}"
                                      class="update_stream_form" enctype="multipart/form-data" id="fields_form">
                                    <div class="row">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$data ? $data['id'] : ''}}">
                                        <input type="hidden" name="period_id" value="{{$data ? $data['period_id'] : $input['period_id']}}">
                                        <input type="hidden" name="project_id" value="{{$data ? $data['project_id'] : $input['project_id']}}">
                                        <input type="hidden" name="vendor_id" value="{{$data ? $data['vendor_id'] : $input['vendor_id']}}">
                                        <input type="hidden" name="user_id" value="{{$data ? $data['user_id'] : $input['user_id']}}">

                                        <div class="col-lg-12 col-x-12 col-md-12 col-12">
                                            <div class="mb-4">
                                                <label for="summary" class="form-label">Narrative</label>
                                                <textarea type="text" class="form-control ckeditor" id="summary" name="narrative">{{$data ? $data['narrative'] : ''}}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-x-4 col-md-4 col-12">
                                            <div class="mb-4">
                                                <label for="forum_participants">How many participants did you have in human led/moderated forums ?</label>
                                                <input type="number" class="form-control white_input" name="forum_participants" value="{{$data ? $data['forum_participants'] : ''}}">
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-x-4 col-md-4 col-12">
                                            <div class="mb-4">
                                                <label for="unique_visitors">How many total registrations/unique visitors did you have for the period ?</label>
                                                <input type="number" class="form-control white_input " name="unique_visitors" value="{{$data ? $data['unique_visitors'] : ''}}">
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-x-4 col-md-4 col-12">
                                            <div class="mb-4">
                                                <label for="two_or_more_users">How many users accessed the application two or more times ?</label>
                                                <input type="number" class="form-control white_input " name="two_or_more_users" value="{{$data ? $data['two_or_more_users'] : ''}}">
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-x-4 col-md-4 col-12">
                                            <div class="mb-4">
                                                <label for="three_or_more_users">How many users accessed the application three or more times ?</label>
                                                <input type="number" class="form-control white_input " name="three_or_more_users" value="{{$data ? $data['three_or_more_users'] : ''}}">
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-x-4 col-md-4 col-12">
                                            <div class="mb-4">
                                                <label for="downloaded_resources">How many times were resources downloaded from your site or application ? (If applicable)</label>
                                                <input type="number" class="form-control white_input " name="downloaded_resources" value="{{$data ? $data['downloaded_resources'] : ''}}">
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-x-4 col-md-4 col-12">
                                            <div class="mb-4">
                                                <label for="self_help_resources">How many times were self-help resources accessed on your site or application ? (If applicable)</label>
                                                <input type="number" class="form-control white_input " name="self_help_resources" value="{{$data ? $data['self_help_resources'] : ''}}">
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-x-4 col-md-4 col-12">
                                            <div class="mb-4">
                                                <label for="demographic_data">Please provide demographic data (gender, age and location) for the period and cumulative date.</label>
                                                <input type="text" class="form-control white_input " name="demographic_data" value="{{$data ? $data['demographic_data'] : ''}}">
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-x-4 col-md-4 col-12">
                                            <div class="mb-4">
                                                <label for="user_satisfaction">What was the user satisfaction score for the period ?</label>
                                                <input type="number" class="form-control white_input " name="user_satisfaction" value="{{$data ? $data['user_satisfaction'] : ''}}">
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-x-4 col-md-4 col-12">
                                            <div class="mb-4">
                                                <label for="outcomes_data">Is there any outcomes data for the period that you would like included in the report ?</label>
                                                <input type="text" class="form-control white_input " name="outcomes_data" value="{{$data ? $data['outcomes_data'] : ''}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row three_btn_margin">
                                        <div class="col-sm-12">
                                            <input type="submit" class="btn update_status_btn normal_btn text-white"
                                                   name="submit"
                                                   value="Save Only"/>
                                            <input type="submit" class="btn normal_btn save_and_submit text-white"
                                                   name="submit"
                                                   value="Save and Submit"/>
                                            @if(Auth::user()->role=="Vendor")
                                                <a type="button" href="{{route('dashboard')}}" class="btn normal_btn cancel_modal_btn text-white">Cancel</a>
                                            @else
                                                <a type="button" href="{{route('dashboard.streams', 0)}}" class="btn normal_btn cancel_modal_btn text-white">Cancel</a>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.pagination')
@endsection

@section('scripts')
    <script>
    </script>
@endsection
