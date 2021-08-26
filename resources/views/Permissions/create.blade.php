@extends('layouts.app')

@section('title', 'Add Permissions')

@section('content')

    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="container">
                <div class="row blue-border-bottom">
                    <div class="col-sm-12 col-md-12 px-0">
                        <div class="top-header pt-2 ">
                            <h3 class="margin-page-title">Permissions</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table_div_padding">
                            @include('layouts.flash-message')
                            <form method="POST" action="{{ route('dashboard.permission.store') }}">
                                @csrf
                                <div class="container">
                                    <div class="row mb-5">
                                        <input type="hidden" id="pre_selected_stream" value="{{$prefilled_data['project_id']}}">
                                        <input type="hidden" id="stream_from_form" value="{{$prefilled_data['stream_id']}}">
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                                            <div class="mb-3">
                                                <label for="FormGroup" class="form-label">Select Period *</label>
                                                <select class="form-control form-select" name="period_id" id="period_id" aria-label="Default select example" required>
                                                    <option value="">Select Period</option>
                                                    @foreach($periods as $period)
                                                        <option value="{{$period->id}}" {{$prefilled_data['period_id'] == $period->id ? "selected" : ""}}>{{$period->name}} ({{$period->start_date}} - {{$period->end_date}})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        @if($active_user->role != 'Admin')
                                            <input type="hidden" name="project_id" id="project_id" value="{{$active_user->project_id}}">
                                        @else
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                                                <div class="mb-4" id="project_div">
                                                    <label for="FormGroup" class="form-label">Select Project *</label>
                                                    <select class="form-control form-select" id="project_id" name="project_id" aria-label="Default select example" required>
                                                        <option value="">Select Project</option>
                                                        @foreach($projects as $project)
                                                            <option value="{{$project->id}}" {{$prefilled_data['project_id'] == $project->id ? "selected" : ""}}>{{$project->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                                            <div class="mb-4">

                                                @if($prefilled_data['form_id'])
                                                    <input type="hidden" id="selected_form_id" value="{{$prefilled_data['form_id']}}">
                                                    <input type="hidden" id="selected_form_name" value="{{$prefilled_data['form_name']}}">
                                                @endif

                                                <label for="FormGroup" class="form-label">Select Form *</label>
                                                <select class="form-control form-select" id="form_id" name="form_id" required aria-label="Default select example">
                                                    <option value="">Select Form</option>

                                                    @if(!empty($forms))
                                                        @foreach($forms as $form)
                                                            <option value="{{$form->id}}" {{$prefilled_data['form_id'] == $form->id ? "selected" : ""}} >{{$form->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                                            <div class="mb-4">

                                                @if($prefilled_data['stream_id'])
                                                    <input type="hidden" id="selected_stream_id" value="{{$prefilled_data['stream_id']}}">
                                                    <input type="hidden" id="selected_stream_name" value="{{$prefilled_data['stream_name']}}">
                                                @endif

                                                <label for="Stream" class="form-label">Select Stream *</label>
                                                <select class="form-control form-elect" id="stream_id" name="stream_id" required aria-label="Default select example">
                                                    <option value="">Select Stream</option>
                                                    @if($prefilled_data['stream_id'])
                                                        <option value="{{$prefilled_data['stream_id']}}" selected>{{$prefilled_data['stream_name']}}</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row permission">
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                            <h3 class="text-center">Assigned User</h3>
                                            <div class="card mb-0">
                                                <ul class="list-group" id="assign_user_section" data-draggable="target">
                                                    <span id="assigned_users"></span>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-12 text-center">
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <h5>Drag User from one box to other</h5>

                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                            <h3 class="text-center">Unassigned User</h3>
                                            <div class="card mb-0">
                                                <ul class="list-group" id="unassign_user_section" data-draggable="target">
                                                    <span id="all_users"></span>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="assign_user" name="assigned_user" value="">
                                    <input type="hidden" id="unassign_user" name="unassigned_user" value="">
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <button class="btn btn-primary">Save</button>
                                            <a href="{{route('dashboard.permissions', [0])}}" class="btn btn-light text-white">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.dynamic_dropdowns')
    <script type="text/javascript">
        (function()
        {
            //exclude older browsers by the features we need them to support
            //and legacy opera explicitly so we don't waste time on a dead browser
            if
            (
                !document.querySelectorAll
                ||
                !('draggable' in document.createElement('span'))
                ||
                window.opera
            )
            { return; }

            //get the collection of draggable items and add their draggable attribute
            for(var
                    items = document.querySelectorAll('[data-draggable="item"]'),
                    len = items.length,
                    i = 0; i < len; i ++)
            {
                items[i].setAttribute('draggable', 'true');
            }

            //variable for storing the dragging item reference
            //this will avoid the need to define any transfer data
            //which means that the elements don't need to have IDs
            var item = null;

            //dragstart event to initiate mouse dragging
            document.addEventListener('dragstart', function(e)
            {
                //set the item reference to this element
                item = e.target;

                //we don't need the transfer data, but we have to define something
                //otherwise the drop action won't work at all in firefox
                //most browsers support the proper mime-type syntax, eg. "text/plain"
                //but we have to use this incorrect syntax for the benefit of IE10+
                e.dataTransfer.setData('text', '');

            }, false);

            //dragover event to allow the drag by preventing its default
            //ie. the default action of an element is not to allow dragging
            document.addEventListener('dragover', function(e)
            {
                if(item)
                {
                    e.preventDefault();
                }

            }, false);

            //drop event to allow the element to be dropped into valid targets
            document.addEventListener('drop', function(e)
            {
                //if this element is a drop target, move the item here
                //then prevent default to allow the action (same as dragover)
                if(e.target.getAttribute('data-draggable') == 'target')
                {
                    e.target.appendChild(item);

                    e.preventDefault();
                }

            }, false);

            //dragend event to clean-up after drop or abort
            //which fires whether or not the drop target was valid
            document.addEventListener('dragend', function(e)
            {
                item = null;
                const assign_array = [];
                const unassign_array = [];

                $("#assign_user_section").children('li').each(function(i,v){
                    assign_array.push($(this).children('input').val());
                });

                $("#unassign_user_section").children('span').children('li').each(function(i,v){
                    unassign_array.push($(this).children('input').val());
                });

                $("#assign_user").val(assign_array);
                $("#unassign_user").val(unassign_array);
                console.log(assign_array);
                console.log(unassign_array);

            }, false);

        })();
    </script>

@endsection
