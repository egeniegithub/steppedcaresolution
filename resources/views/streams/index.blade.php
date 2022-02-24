@extends('layouts.app')

@section('title', 'List Form')

@section('content')
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 px-0">
                        <div class="top-header pt-2 blue-border-bottom">
                            <h3 class="margin-page-title">Stream: &nbsp;<u>{{$form->name}}</u></h3>
                        </div>
                    </div>
                </div>
                <div class="row blue-border-bottom">
                    <div class="col-sm-6 col-md-10 px-0">
                        <div class="top-header pt-2 ">
                            <h4 class="margin-page-title">Forms</h4>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-2 px-0">
                        <div class="top-header pt-2 add_icon_pad right_icon_text pr-4">
                            <b class=""> <a href="{{route('dashboard.stream.create', [$form->id])}}" style="color:#1B9C53 !important"> <span ><i class="fas fa-plus-circle"></i></span><span> Add Form</span></a></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table_div_padding">
                            @include('layouts.flash-message')
                            <div class="card mb-0">
                                <div class="table-responsive">
                                    <table class="table forms_stream_table table_margin_adj" id="myTable">
                                        <thead>
                                        <tr>
                                            <td>Name</td>
                                            <td>Stream</td>
                                            <td>Project</td>
                                            <td>Status</td>
                                            <td >
                                                <p> Order &nbsp;&nbsp; <br><span class="edit_button stream_view_icons"><i class="fas fa-pen-square" style="color:#4A90CB"></i></span></p>

                                                {{--<span type="button" class="cancel_edit_button stream_view_icons"><i style="color:#bf1f28" class="fas fa-window-close"></i></span>
                                                <span type="button" class="add_more_button stream_view_icons"><i style="color:#1b9c53" class="fas fa-plus-square"></i></span>--}}
                                            </td>
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
                                                <td class="stream_editable_coloumn">
                                                    <input class="form-control editable_table_coloumn stream_editable_input target stream_order" id="{{$stream->stream_id}}" name="order_count" type="number" readonly value="{{$stream->order_count}}">
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                        <a type="button" class="btn table_btn update_btn text-white" href="{{route('dashboard.stream.edit', [$form->id, $stream->stream_id])}}">Update</a>
                                                        <a type="button" class="btn table_btn delete_btn text-white" href="{{route('dashboard.stream.delete', $stream->stream_id)}}">Delete</a>
                                                        <a type="button" class="btn table_btn permission_btn text-white" href="{{route('dashboard.permissions', [$stream->stream_id])}}">Permissions</a>
                                                        <a type="button" class="btn btn-light" href="{{route('dashboard.stream.render', [$stream->stream_id])}}">View Form</a>

                                                        <button type="button" data-toggle="modal" data-target="#syncFormData{{$stream->stream_id}}" class="btn permission_btn text-white">Sync</button>
                                                        @include('streams.partials.sync_form')
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="text-center">
                                                <td colspan="5">&nbsp; No Form Added</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
    <script>
        $(".cancel_edit_button").hide();
        $(".add_more_button").hide();

        $(".edit_button").click(function(){
            $(".edit_button").hide();
            /*$(".cancel_edit_button").show();
            $(".add_more_button").show();*/
            $(".editable_table_coloumn").attr("readonly",false);
        });

        /*$(".cancel_edit_button").click(function(){
            $(".cancel_edit_button").hide();
            $(".add_more_button").hide();
            $(".edit_button").show();
            $(".editable_table_coloumn").attr("readonly",true);
        });*/

        $(".stream_order").focusout(function (e) {
            var stream_id = $(this).attr("id");
            var value = $('#'+stream_id).val();

            $.ajax({
                type: "POST",
                url: "{{url('/stream-order')}}",
                data: {
                    "stream_id": stream_id,
                    "value": value,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(result) {
                    location.reload();
                },
                error: function(result) {
                    alert('error');
                }
            });
        });

    </script>
@endsection
