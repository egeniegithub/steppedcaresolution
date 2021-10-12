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
                            <h3 class="margin-page-title">Forms</h3>
                        </div>
                    </div>
                </div>
                <form method="get" action="">
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

                <div class="row">
                    <div class="col-sm-12">
                        <div class="table_div_padding">
                            <div class="card mb-0">
                                <div class="table-responsive">
                                    <table class="table user-stream-table table_margin_adj">
                                        <thead>
                                        <tr>
                                            <td>Stream Name</td>
                                            <td>Form</td>
                                            <td>Project</td>
                                            <td>Status</td>
                                            <td>Actions</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($streams as $stream)
                                            <tr>
                                                <td style="color: black !important;">{{$stream->stream_name}} </td>
                                                <td>{{$stream->form_name}}</td>
                                                <td>
                                                    @php
                                                        $project_name = \App\Models\project::where('id', $stream->project_id)->value('name')
                                                    @endphp
                                                    {{$project_name}}
                                                </td>
                                                <td>{{$stream->stream_status}}</td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                        @if($stream->stream_status != 'Published')
                                                            <a href="{{route('dashboard.stream.render', [$stream->stream_id])}}" type="button" class="btn table_btn update_btn text-white">Update</a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="text-center">
                                                <td colspan="5">No Stream Assigned</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class=" flex-columns flex-setting mob_margin_pagination">
                                <form>
                                    <div class="inline_block_adj show_rows_adj">
                                        <label class="my-1 mr-2" for="inlineFormCustomSelectPref">Show Rows :</label>
                                        <select name="" class="my-1 show_rows_count" id="show_rows" onchange="get_per_page()">
                                        </select>
                                    </div>
                                </form>
                                <div class="show_rows_adj margin_top">
                                    {{$streams->links('components.pagination')}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.pagination')
@endsection
