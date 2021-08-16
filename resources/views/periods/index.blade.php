@extends('layouts.app')

@section('title', 'List Period')

@section('content')
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="container">
                <div class="row blue-border-bottom">
                    <div class="col-sm-6 col-md-9 col-lg-10 px-0">
                        <div class="top-header pt-2 ">
                            <h3 class="margin-page-title">Periods</h3>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 col-lg-2">
                        <div class="top-header right_icon_text">
                            <b class=""><a class="add_icon" href="{{route('dashboard.period.create')}}"><span ><i class="fas fa-plus-circle"></i></span><span> Add Periods</span></a></b>
                        </div>
                    </div>
                </div>
                <form method="get" action="">
                    <div class="row  blue-border-bottom">
                        <div class="col-sm-12 col-md-4 px-0 ">
                            <p class="pl-4 mt-2 mb-0"> Search </p>
                            <div class="form-group pl-4 pt-1 d-flex search_bar_adj">
                                <input type="text" class="form-control" name="keyword" placeholder="Search Here" value="{{request()->get('keyword')}}">
                                <button class="span_search span_mid"><i class="fas fa-search search_icon"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table_div_padding">
                            @include('layouts.flash-message')
                            <div class="card mb-0">
                                <div class="table-responsive">
                                    <table class="table table_margin_adj">
                                        <thead>
                                            <tr>
                                                <td>Name</td>
                                                <td>Start Date</td>
                                                <td>End Date</td>
                                                <td>Status</td>
                                                <td>Actions</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($periods as $period)
                                            <tr>
                                                <td>{{$period->name}}</td>
                                                <td>{{date('d, M Y', strtotime($period->start_date))}}</td>
                                                <td>{{date('d, M Y', strtotime($period->end_date))}}</td>
                                                <td>{{$period->status == 1 ? 'Active' : 'In-active'}}</td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                        <a href="{{route('dashboard.period.edit', [encrypt($period->id)])}}" type="button" class="btn table_btn update_btn text-white">Update</a>
                                                        <button type="button" class="btn table_btn delete_btn text-white delete_period_modal" data-toggle="modal" data-deleteForm="{{route('dashboard.period.delete')}}{{'?ref='.encrypt($period->id)}}">Delete</button>
                                                        <button type="button" data-toggle="modal" data-target="#syncData{{$period->id}}" class="btn table_btn permission_btn text-white">Sync Data</button>
                                                        @include('Periods.partials.sync_data')
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">No period added</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Delete Form Modal--}}
                            @include('Periods.partials.delete_modal')

                            <div class=" flex-columns flex-setting mob_margin_pagination">
                                <form>
                                    <div class="inline_block_adj show_rows_adj">
                                        <label class="my-1 mr-2" for="inlineFormCustomSelectPref">Show Rows :</label>
                                        <select name="" class="my-1 show_rows_count" id="show_rows" onchange="get_per_page()">
                                        </select>
                                    </div>
                                </form>
                                <div class="show_rows_adj margin_top">
                                    {{$periods->links('components.pagination')}}
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
