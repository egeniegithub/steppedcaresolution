@extends('layouts.app')
@section('content')
<div class="pcoded-wrapper">
    <div class="pcoded-content">
        <div class="container">
            <div class="row blue-border-bottom">
                <div class="col-sm-6 col-md-9 col-lg-10 px-0">
                    <div class="top-header pt-2 ">
                        <h3 class="margin-page-title">Manage Users</h3>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 col-lg-2 ">
                    <div class="top-header right_icon_text search_bar_adj">
                        <b class=""><a class="add_icon" href="{{route('dashboard.user.create')}}"><span><i class="fas fa-plus-circle"></i></span><span> Add User</span></a></b>
                    </div>
                </div>
            </div>
            <form method="get" action="">
                <div class="row  blue-border-bottom">
                    <div class="col-sm-12 col-md-3 px-0">
                        <p class="pl-4 mt-2 mb-0"> Search </p>
                        <div class="form-group pl-4 pt-1 d-flex search_bar_adj">
                            <input type="text" class="form-control" id="exampleInputEmail1" name="keyword" aria-describedby="emailHelp" placeholder="Search here"  value="{{request()->get('keyword')}}">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3 px-0">
                        <p class="pl-4 mt-2 mb-0"> Type </p>
                        <div class="form-group pl-4 pt-1 d-flex search_bar_adj">
                            <select class="form-control" id="exampleFormControlSelect1" name="type">
                                <option value="all" selected>All</option>
                                @foreach(users_roles() as $data)
                                <option value="{{$data}}" {{request()->get('type')==$data?"selected":""}}>{{ $data}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3 px-0">
                        <p class="pl-4 mt-2 mb-0"> Project </p>
                        <div class="form-group pl-4 pt-1 d-flex search_bar_adj">
                            <select class="form-control form-select" id="project_id" name="project_id"
                                    aria-label="Default select example">
                                <option value="all" selected>All</option>
                                @foreach($projects as $project)
                                    <option
                                        value="{{$project->id}}" {{request()->get('project_id') == $project->id ? "selected" : ""}}>{{$project->name}}</option>
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
                        @include('layouts.flash-message')
                        <div class="card mb-0">
                            <div class="table-responsive">
                                <table class="table  user_table_layout user_table table_margin_adj">
                                    <thead>
                                        <tr>
                                            <th> Name </th>
                                            <th> Email </th>
                                            <th> Phone </th>
                                            <th> Type </th>
                                            <th> Project </th>
                                            <th class="three_btn_width"> Actions </th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach($users as $key=>$data)
                                        {{--@if(auth()->user()->id==$data->id)
                                        @else--}}
                                        <tr>
                                            <td> {{$data->name}}</td>
                                            <td> {{$data->email}} </td>
                                            <td> {{$data->phone}} </td>
                                            <td> {{$data->role}} </td>
                                            <td> {{\App\Models\project::where('id', $data->project_id)->value('name')}} </td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <a href="{{route('dashboard.user.view','ref='.encrypt($data->id))}}" type="button" class="btn  table_btn  view_btn text-white">View</a>
                                                    <a href="{{route('dashboard.user.edit','ref='.encrypt($data->id))}}" type="button" class="btn table_btn  update_btn text-white">Update</a>
                                                    <button type="button" class="btn  table_btn delete_btn text-white delete_modal" data-toggle="modal" data-deleteMember="{{route('dashboard.user.delete')}}{{'?ref='.encrypt($data->id)}}">Delete</button>

                                                </div>
                                            </td>
                                        </tr>
                                        {{--@endif--}}
                                        @endforeach
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
                            {{$users->links('components.pagination')}}
                        </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($){
    let array=[ "10", "20", "30","40","50"];
    $("#show_rows").empty();
    var show_rows='';
    @if(!empty($row_show))
    for ( var key in array) {
         if(array[key]=={{$row_show}}){
         show_rows='<option value="'+array[key]+'" " selected>'+array[key]+'</option>';
         }else{
             show_rows ='<option value="'+array[key]+'" ">'+array[key]+'</option>';
         }

        $("#show_rows").append(show_rows);
    }
    @else
    for ( var key in array) {
        show_rows ='<option value="'+array[key]+'" ">'+array[key]+'</option>';
        $("#show_rows").append(show_rows);
    }
    @endif
});
</script>
@endsection
