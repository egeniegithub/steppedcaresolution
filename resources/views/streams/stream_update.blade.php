@extends('layouts.app')

@section('title', 'List Form')

@section('content')
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="container">
                <div class="row blue-border-bottom">
                    <div class="col-sm-6 col-md-4 col-lg-4 px-0 stream_update_title">
                        <div class="top-header pt-2">
                            <h3 class="margin-page-title">Streams 1.0</h3>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-4 px-0 update_stream_mid">
                        <div class="top-header pt-2">
                            <h3 class="margin-page-title">
                                status : <span class="blue_span">In Progress</span>
                            </h3>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-4 px-0">
                        <div class="top-header pt-2 update_stream_right_align">
                            <a class="btn update_status_btn text-white" href="{{route('dashboard')}}">Go to Stream List</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table_div_padding">
                            <div class="card mb-0">
                                <div class="card_header">
                                    <h5 class="header_padding_adj">Narative</h5>
                                </div>
                                <form class="update_stream_form">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="exampleFormControlTextarea1">Stakeholder collaboration</label>
                                                <textarea class="form-control white_input" id="exampleFormControlTextarea1" rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="exampleFormControlTextarea1">Client Interface/experience design and management</label>
                                                <textarea class="form-control  white_input" id="exampleFormControlTextarea1" rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="exampleFormControlTextarea1">Stream 1 Discussion</label>
                                                <textarea class="form-control white_input" id="exampleFormControlTextarea1" rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="exampleFormControlTextarea1">Additional Narrative</label>
                                                <textarea class="form-control white_input" id="exampleFormControlTextarea1" rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row three_btn_margin">
                                        <div class="col-sm-12">
                                            <button type="button" class="btn update_status_btn normal_btn text-white">Save Only</button>
                                            <button type="button" class="btn normal_btn save_and_submit text-white">Save and Submit</button>
                                            <button type="button" class="btn normal_btn cancel_modal_btn text-white" data-toggle="modal" data-target="#exampleModal">Cancel</button>
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
