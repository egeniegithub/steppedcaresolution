@extends('layouts.app')

@section('title', 'List Stream')

@section('content')
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 px-0">
                        <div class="top-header pt-2 blue-border-bottom">
                            <h3 class="margin-page-title">Streams</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table_div_padding">
                            @include('layouts.flash-message')
                            <div class="card pt-3">
                                <form method="POST" action="{{ route('dashboard.form.store') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="container">
                                        <h4>Create Stream</h4>
                                        <div class="row report_row_top">
                                            <div class="col-xl-3 col-lg-3 col-md-3 col-12">
                                                <div class="mb-3">
                                                    <label for="newform" class="form-label">Name *</label>
                                                    <input type="text" class="form-control" id="newform" name="name" value="{{ old('name') }}" required placeholder="Name" aria-describedby="newform">
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-3 col-md-3 col-12">
                                                <div class="mb-3">
                                                    <label for="FormGroup" class="form-label">Select Period *</label>
                                                    <select class="form-control form-select" name="period_id" id="period_id" aria-label="Default select example" required>
                                                        <option value="">Select Period</option>
                                                        @foreach($periods as $period)
                                                            <option value="{{$period->id}}">{{$period->name}}
                                                                ({{date('d-m-Y', strtotime($period->start_date))}} - {{date('d-m-Y', strtotime($period->end_date))}})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            @if($active_user->role != 'Admin')
                                                <input type="hidden" name="project_id" value="{{$active_user->project_id}}">
                                            @else
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-12">
                                                    <div class="mb-3">
                                                        <label for="FormGroup" class="form-label">Select Project *</label>
                                                        <select class="form-control form-select" name="project_id" id="project_id" aria-label="Default select example" required>
                                                            <option value="">Select Project</option>
                                                            @foreach($projects as $project)
                                                                <option value="{{$project->id}}" {{old('project_id') == $project->id ? "selected" : ""}}>{{$project->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-xl-1 col-lg-1 col-md-1 col-12">
                                                <div class="mb-3" style="margin-top: 40px">
                                                    <label for="newform" class="form-label">
                                                        <input type="checkbox" name="is_special" value="1"> <b>Is special form</b>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-xl-1 col-lg-1 col-md-1 col-12">
                                                <button class="btn btn-primary" style="margin-top: 25px">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card pt-3">
                                <form method="get" action="">
                                    <div class="container">
                                        <h4>Search Stream</h4>
                                        <div class="row report_row_top ">
                                            <div class="col-xl-3 col-lg-3 col-md-3 col-12">
                                                <div>
                                                    <label for="Project" class="form-label">Search</label>
                                                    <input type="text" class="form-control" id="keyword" name="keyword" placeholder="Search Here" value="{{request()->get('keyword')}}">
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-3 col-md-3 col-12">
                                                <label for="FormGroup" class="form-label">Select Period</label>
                                                <select class="form-control form-select" name="period_id" id="period_id" aria-label="Default select example" >
                                                    <option value="">Select Period</option>
                                                    @foreach($periods as $period)
                                                        <option value="{{$period->id}}" {{request()->get('period_id') == $period->id ? "selected" : ""}}>{{$period->name}} ({{date('d-m-Y', strtotime($period->start_date))}} - {{date('d-m-Y', strtotime($period->end_date))}})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if($active_user->role != 'Admin')
                                                <input type="hidden" name="project_id" value="{{$active_user->project_id}}">
                                            @else
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-12">
                                                    <div class="mb-3">
                                                        <label for="FormGroup" class="form-label">Select Project</label>
                                                        <select class="form-control form-select" name="project_id" id="project_id" aria-label="Default select example">
                                                            <option value="">Select Project</option>
                                                            @foreach($projects as $project)
                                                                <option value="{{$project->id}}" {{request()->get('project_id') == $project->id ? "selected" : ""}}>{{$project->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-xl-2 col-lg-2 col-md-2 col-12 pl-0 report_flex_row">
                                                <div class="span_search_div">
                                                    <button class="report_search_icon span_mid"><i class="fas fa-search "></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card mb-0">
                                <div class="table-responsive">
                                    <table class="table   table_margin_adj">
                                        <thead>
                                        <tr>
                                            <td style="width: 10%">No</td>
                                            <td>Stream</td>
                                            <td>Project</td>
                                            <td>Period</td>
                                            <td >
                                                <p> Order &nbsp;&nbsp; <br><span class="edit_button stream_view_icons"><i class="fas fa-pen-square" style="color:#4A90CB"></i></span></p>

                                                {{--<span type="button" class="cancel_edit_button stream_view_icons"><i style="color:#bf1f28" class="fas fa-window-close"></i></span>
                                                <span type="button" class="add_more_button stream_view_icons"><i style="color:#1b9c53" class="fas fa-plus-square"></i></span>--}}
                                            </td>
                                            <td>Actions</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($forms as $form)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>
                                                    @if($form->is_special != 1)
                                                        <a type="button" href="{{ route('dashboard.streams', [$form->form_id]) }}" >{{$form->form_name}}</a>
                                                    @else
                                                        {{$form->form_name}} (Special)
                                                    @endif
                                                </td>
                                                <td>{{$form->project_name}}</td>
                                                <td>{{$form->period_name}}</td>
                                                <td class="stream_editable_coloumn">
                                                    <input class="form-control editable_table_coloumn stream_editable_input target stream_order" id="{{$form->form_id}}" name="order_count" type="number" readonly value="{{$form->order_count}}">
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                        @if($form->is_special != 1)
                                                            <button data-toggle="modal" data-target="#editFormModal{{$form->form_id}}" class="btn table_btn update_btn text-white">Update</button>
                                                        @endif
                                                        <button type="button" class="btn table_btn delete_btn text-white delete_form_modal" data-toggle="modal" data-deleteForm="{{route('dashboard.form.delete')}}{{'?ref='.encrypt($form->form_id)}}">Delete</button>
                                                        @if($form->is_special != 1)
                                                            <a type="button" href="{{ route('dashboard.streams', [$form->form_id]) }}" class="btn stream_button_new table_btn text-white">Forms</a>
                                                        @endif
                                                    </div>
                                                    @include('forms.partials.update_form_modal')
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No stream added</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Delete Form Modal--}}
                            @include('forms.partials.delete_modal')

                            <div class=" flex-columns flex-setting mob_margin_pagination">
                                <form>
                                    <div class="inline_block_adj show_rows_adj">
                                        <label class="my-1 mr-2" for="inlineFormCustomSelectPref">Show Rows :</label>
                                        <select name="" class="my-1 show_rows_count" id="show_rows" onchange="get_per_page()">
                                        </select>
                                    </div>
                                </form>
                                <div class="show_rows_adj margin_top">
                                    {{$forms->links('components.pagination')}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.pagination')

    @include('layouts.dynamic_dropdowns')

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
            var form_id = $(this).attr("id");
            var value = $('#'+form_id).val();

            $.ajax({
                type: "POST",
                url: "{{url('/form-order')}}",
                data: {
                    "form_id": form_id,
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
