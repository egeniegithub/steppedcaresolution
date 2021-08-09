@extends('layouts.app')

@section('title', 'List Stream')

@section('content')
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 px-0">
                        <div class="top-header pt-2 blue-border-bottom">
                            <h3 class="margin-page-title">Form: &nbsp;<u>{{$form->name}}</u></h3>
                        </div>
                    </div>
                </div>
                <div class="row blue-border-bottom">
                    <div class="col-sm-6 col-md-10 px-0">
                        <div class="top-header pt-2 ">
                            <h4 class="margin-page-title">Streams</h4>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-2 px-0">
                        <div class="top-header pt-2 add_icon_pad right_icon_text pr-4">
                            <b class=""> <a href="{{route('dashboard.stream.create', [$form->id])}}" style="color:#1B9C53 !important"> <span ><i class="fas fa-plus-circle"></i></span><span> Add Stream</span></a></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table_div_padding">
                            @include('layouts.flash-message')
                            <div class="card mb-0">
                                <div class="table-responsive">
                                    <table class="table  forms_stream_table  table_margin_adj">
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
                                                <td scope="row">{{$stream->stream_name}}</td>
                                                <td>{{$stream->form_name}}</td>
                                                <td>{{$stream->project_name}}</td>
                                                <td>{{$stream->stream_status}}</td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                        <button type="button" class="btn table_btn  update_btn text-white">Update</button>
                                                        <button type="button" class="btn  table_btn delete_btn text-white">Delete</button>
                                                        <a type="button" class="btn table_btn permission_btn text-white" href="{{route('dashboard.permissions', [$stream->stream_id])}}" >Permissions</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="text-center">
                                                <td colspan="5">&nbsp; No Stream Added</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class=" flex-columns mob_margin_pagination flex-setting">
                                <div class="inline_block_adj show_rows_adj">
                                    <label class="my-1 mr-2" for="inlineFormCustomSelectPref">Show Rows :</label>
                                    <select class=" my-1 show_rows_count" id="inlineFormCustomSelectPref">
                                        <option value="1">10</option>
                                        <option value="2">20</option>
                                        <option selected value="3">30</option>
                                        <option value="4">40</option>
                                        <option value="5">50</option>
                                    </select>
                                </div>
                                <div class="show_rows_adj margin_top">
                                    <nav aria-label="Page navigation example ">
                                        <ul class="pagination">
                                            <li class="page-item "><a class="page-link active" href="#">Prev</a>
                                            </li>
                                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
