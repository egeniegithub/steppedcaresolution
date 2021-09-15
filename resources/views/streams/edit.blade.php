@extends('layouts.app')

@section('title', 'Edit Stream')

@section('content')
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 px-0">
                        <div class="top-header pt-2 blue-border-bottom">
                            <h3 class="margin-page-title">Forms</h3>
                        </div>
                    </div>
                    <div class="col-sm-12 px-0">
                        <div class="top-header pt-2 blue-border-bottom">
                            <h4 class="margin-page-title">Add Stream</h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table_div_padding">
                            <form method="POST" action="{{ route('dashboard.stream.store') }}"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-5 col-lg-6 col-md-6 col-12">
                                        <div class="mb-3">
                                            <label for="stream-name" class="form-label">Stream Name</label>
                                            <input type="text" id="stream-name" class="form-control white_input"
                                                   name="name"
                                                   value="{{old('name') ?? $stream->name ?? null}}" required/>
                                            <input type="hidden" name="form_id" value="{{$form_id}}">
                                            <input type="hidden" name="stream_id" value="{{$stream->id ?? null}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                                        <div class="card ">
                                            <ul class="vertical_nav  nav-tabs">
                                                <li class="first_vertical_nav font-weight-bold li_dark_border">Add
                                                    Fields
                                                </li>
                                            </ul>
                                            <div class="tab">
                                                <button type="button" class="tablinks li_light_border tab-text"
                                                        onclick="openCity(event, 'text')" id="defaultOpen"><img class="image_black"
                                                                                               src="{{url('/assets/images/text_black.png')}}"/>
                                                    <img class="image_white "
                                                         src="{{url('/assets/images/text_white.png')}}"/> <span
                                                        class="light_grey_text">Text</span></button>
                                                <button type="button" class="tablinks li_light_border tab-textarea"
                                                        onclick="openCity(event, 'textarea')"><img class="image_black"
                                                                                                    src="{{url('/assets/images/long_text_black.png')}}"/>
                                                    <img class="image_white"
                                                         src="{{url('/assets/images/long_text_white.png')}}"/> <span
                                                        class="light_grey_text">Long Text</span></button>
                                                <button type="button" class="tablinks li_light_border numeric_white tab-number"
                                                        onclick="openCity(event, 'number')"><img class="image_black"
                                                                                                  src="{{url('/assets/images/numeric_black.png')}}"/>
                                                    <img class="image_white"
                                                         src="{{url('/assets/images/numeric_white.png')}}"/> <span
                                                        class="light_grey_text">Numeric</span></button>
                                                <button type="button" class="tablinks li_light_border tab-date"
                                                        onclick="openCity(event, 'date')"><img class="image_black"
                                                                                               src="{{url('/assets/images/date_black.png')}}"/>
                                                    <img class="image_white"
                                                         src="{{url('/assets/images/date_white.png')}}"/> <span
                                                        class="light_grey_text">Date</span></button>
                                                <button type="button" class="tablinks li_light_border tab-file"
                                                        onclick="openCity(event, 'file')"><img class="image_black"
                                                                                                src="{{url('/assets/images/image_black.png')}}"/>
                                                    <img class="image_white"
                                                         src="{{url('/assets/images/image_white.png')}}"/> <span
                                                        class="light_grey_text"> Image</span></button>
                                                <button type="button" class="tablinks li_light_border tab-select"
                                                        onclick="openCity(event, 'select')"><img
                                                        class="image_black dropdownfiield_icon"
                                                        src="{{url('/assets/images/dropdown-black.png')}}"/> <img
                                                        class="image_white dropdownfiield_icon"
                                                        src="{{url('/assets/images/dropdown-white.png')}}"/> <span
                                                        class="light_grey_text">Drop Down</span></button>
                                                <button type="button" class="tablinks li_light_border  tab-table"
                                                        onclick="openCity(event, 'table')" ><img
                                                        class="image_black"
                                                        src="{{url('/assets/images/table_black.png')}}"/> <img
                                                        class="image_white"
                                                        src="{{url('/assets/images/icon-table-white.png')}}"/> <span
                                                        class="light_grey_text">Data Table</span></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-12 tabcontent " id="textarea">
                                        <div class="card ">
                                            <ul class="vertical_nav">
                                                <li class="first_vertical_nav font-weight-bold li_dark_border"
                                                    id="field-card-heading">Long Text Field
                                                </li>
                                            </ul>
                                            <div class="card-padding">
                                                <input type="hidden" name="field_type" id="field_type">
                                                <div class="row row_adjusted">
                                                    <div class="col-sm-12 col-lg-6">
                                                        <div class="form-group">
                                                            <label for="field_name">Name</label>
                                                            <input type="text" class="form-control white_input"
                                                                   id="field_name" aria-describedby="emailHelp">
                                                            <input type="text" name="orderCount" class="order_count" hidden>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row row_adjusted">
                                                    <div class="col-sm-12 col-lg-5">
                                                        <table class="radio_table" style="width:100%">
                                                            <tr>
                                                                <th> Required </th>
                                                                <td>
                                                                    <label class="radio_container">
                                                                        <input type="radio" class="field_required"
                                                                               name="field_required" value="yes"
                                                                               id="field_required">
                                                                        <span class="checkmark"></span>
                                                                        Yes
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <label class="radio_container">No
                                                                        <input type="radio"
                                                                               class="field_required"
                                                                               checked="checked"
                                                                               name="field_required" value="no"
                                                                               id="field_required">
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th> Allow Duplicate:</th>
                                                                <td>
                                                                    <label class="radio_container">
                                                                        <input type="radio"
                                                                               name="field_duplicate" value="yes"
                                                                               id="field_duplicate">
                                                                        <span class="checkmark"></span>
                                                                        Yes
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <label class="radio_container">No
                                                                        <input type="radio"
                                                                               checked="checked"
                                                                               name="field_duplicate"
                                                                               id="field_duplicate" value="no">
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </td>
                                                            </tr>

                                                            <tr id="only-for-number" style="display: none">
                                                                <th> Cumulative Value:</th>
                                                                <td>
                                                                    <label class="radio_container">
                                                                        <input type="radio"
                                                                               name="field_cumulative" value="yes"
                                                                               id="field_cumulative">
                                                                        <span class="checkmark"></span>
                                                                        Yes
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <label class="radio_container">No
                                                                        <input type="radio" checked="checked"
                                                                               name="field_cumulative"
                                                                               id="field_cumulative" value="no">
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="row row_adjusted">
                                                    <div class="col-sm-12">
                                                        <div class="btn-group btn_group_padding">
                                                            <button type="button" onclick="addField()" class="btn btn-primary textarea_addbtn table_btn text-white"> Add</button>
                                                            <button class="btn table_btn cancel_modal_btn text-white"
                                                                    type="button" onclick="textarea_reset()">
                                                                Reset
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-12 tabcontent " id="select">
                                        <div class="card ">
                                            <ul class="vertical_nav">
                                                <li class="first_vertical_nav font-weight-bold li_dark_border"
                                                    id="field-card-heading">Drop Down
                                                </li>
                                            </ul>
                                            <div class="card-padding">
                                                <input type="hidden" name="field_type" id="field_type">
                                                <div class="row row_adjusted">
                                                    <div class="col-sm-12 col-lg-6">
                                                        <div class="form-group">
                                                            <label for="field_name">Name</label>
                                                            <input type="text" class="form-control white_input"
                                                                   id="drop_field_name" aria-describedby="emailHelp">
                                                            <input type="text" class="dropdown_hidden_text" value="" hidden/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row row_adjusted">
                                                    <div class="col-sm-12 col-lg-5">
                                                        <table class="radio_table" style="width:100%">
                                                            <tr>
                                                                <th> Required</th>
                                                                <td>
                                                                    <label class="radio_container">
                                                                        <input type="radio"  checked="checked"
                                                                               class="dropdown_required"
                                                                               name="field_required" value="yes"
                                                                               id="field_required">
                                                                        <span class="checkmark"></span>
                                                                        Yes
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <label class="radio_container">No
                                                                        <input type="radio"  checked="checked"
                                                                               class="dropdown_required"
                                                                               name="field_required" value="no"
                                                                               id="field_required">
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="row row_adjusted">
                                                    <div class="col-sm-12 col-lg-6">
                                                        <div class="form-group">
                                                            <label for="field_name">Options</label>
                                                            <textarea type="text" class="form-control white_input"
                                                                      id="field_options"
                                                                      aria-describedby="emailHelp"></textarea>
                                                            <p class="dropdown_small_text"> Enter comma separated
                                                                values</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row row_adjusted">
                                                    <div class="col-sm-12">
                                                        <div class="btn-group btn_group_padding">
                                                            <button class="btn table_btn btn-primary dropdown_addbtn text-white"
                                                                    type="button"
                                                                    onclick="addField()"> Add
                                                            </button>
                                                            <button class="btn table_btn cancel_modal_btn text-white"
                                                                    type="button" onclick="select_reset()">
                                                                Reset
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="table" class="col-xl-8 col-lg-8 col-md-8 col-12 tabcontent">
                                        <div class="card ">
                                            <ul class="vertical_nav">
                                                <li class="first_vertical_nav font-weight-bold li_dark_border">Table
                                                </li>
                                            </ul>
                                            <div class="card-padding">
                                                <div class="row row_adjusted li_dark_border">
                                                    <div class="col-sm-12 col-lg-6 ">
                                                        <div class="form-group">
                                                            <label for="table_name">Name</label>
                                                            <input type="text" class="form-control white_input"
                                                                   id="table_name"
                                                                   aria-describedby="emailHelp">
                                                            <input type="text" class="tablehiddenfield" value="" hidden/>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-lg-6 cumulative_table">
                                                        <table class="radio_table" style="width:75%">
                                                            <tr>
                                                                <th> Cumulative Value</th>
                                                                <td>
                                                                    <label class="radio_container">
                                                                        <input type="radio" checked="checked"
                                                                               name="table_cumulative_value" value="yes">
                                                                        <span class="checkmark"></span>
                                                                        Yes
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <label class="radio_container">No
                                                                        <input type="radio" checked="checked"
                                                                               name="table_cumulative_value" value="no">
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-sm-12 col-lg-6 add_tables_margin">
                                                        <b class=""><a class="add_icon"
                                                                      ><span> Build Table</span></a></b>
                                                    </div>
                                                </div>
                                                <div class="row row_adjusted">
                                                    <div class="col-sm-12 col-lg-6 cumulative_table pt-3">
                                                        <table class="radio_table" style="width:50%">
                                                            <tr>
                                                                <td>
                                                                    <label class="radio_container">
                                                                        <input type="radio" checked="checked"
                                                                               name="tableFieldType" value="row">
                                                                        <span class="checkmark"></span>
                                                                        Row
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <label class="radio_container">Column
                                                                        <input type="radio" checked="checked"
                                                                               name="tableFieldType" value="column">
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                            <tr class="mb-2 hide_row">
                                                                <td colspan="2"><span
                                                                        style="font-weight: bold">Dropdown</span></td>
                                                            </tr>
                                                            <tr class="hide_row">
                                                                <td>
                                                                    <label class="radio_container">
                                                                        <input type="radio"
                                                                               name="tableDropdown" value="yes"
                                                                               onclick="tableDropDown('yes')">
                                                                        <span class="checkmark"></span>
                                                                        Yes
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <label class="radio_container">No
                                                                        <input type="radio" checked="checked"
                                                                               name="tableDropdown" value="no"
                                                                               onclick="tableDropDown('no')">
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>

                                                <div class="row row_adjusted pt-3">
                                                    <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                                                        <div class="mb-3">
                                                            <div class="row row_adjusted">
                                                                <div class="col-sm-12 col-lg-12">
                                                                    <div class="form-group">
                                                                        <label for="table-field-name"
                                                                               class="form-label">Name</label>
                                                                        <input type="text" id="table-field-name"
                                                                               class="form-control white_input"/>
                                                                        <input type="hidden" name=""
                                                                               id="table_hidden_id">
                                                                        <input type="hidden" name=""
                                                                               id="table_data_db_id">
                                                                        <input type="hidden" name=""
                                                                               id="stream_field_id">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row row_adjusted table-dropdown-switch"
                                                                 style="display: none">
                                                                <div class="col-sm-12 col-lg-12">
                                                                    <div class="form-group">
                                                                        <label for="field_name">Options</label>
                                                                        <textarea type="text"
                                                                                  class="form-control white_input"
                                                                                  id="table_field_options"
                                                                                  aria-describedby="emailHelp"></textarea>
                                                                        <p class="dropdown_small_text"> Enter comma
                                                                            separated
                                                                            values</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="btn-group btn_group_padding pt-4">
                                                                <button
                                                                    type="button"
                                                                    class="btn table_btn btn-primary table_addbtn text-white"
                                                                    onclick="addTableField()">
                                                                    Add to Table
                                                                </button>
                                                                <!-- <button
                                                                    type="button"
                                                                    class="btn table_btn cancel_modal_btn text-white">
                                                                    Cancel
                                                                </button> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row row_adjusted">
                                                <div class="col-sm-12">
                                                    <div class="card mb-0">
                                                        <div class="table-responsive">
                                                            <table
                                                                class="table  table-bordered stream_data_table table_margin_adj" id="myTableTwo">
                                                                <thead>
                                                                <tr>
                                                                    <td> Name</td>
                                                                    <td> Type</td>
                                                                    <td> Order</td>
                                                                    <td class="add_stream_table_one"> Actions
                                                                    </td>
                                                                </tr>
                                                                </thead>
                                                                <tbody class="ui-sortable" id="table-field-rows">

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row row_adjusted mt-2">
                                            <div class="col-sm-12">
                                                <div class="btn-group btn_group_padding">
                                                    <button class="btn stream_btn btn-primary text-white"
                                                            type="button" onclick="addField()">
                                                        Add Table to Stream
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-0">
                                    <div class="table-responsive">
                                        <table class="table  stream_primary_table table_margin_adj"
                                               id="myTable">
                                            <thead>
                                            <tr>
                                                <td> Name</td>
                                                <td> Type</td>
                                                <td> Required</td>
                                                <td> Duplicate</td>
                                                <td> Cumulative Value</td>
                                                <td> Order <img class="arrow_icon_adj"
                                                                src="{{url('/assets/images/doubleArrow.png')}}"/></td>
                                                <td class="add_stream_btn_two"> Actions</td>
                                            </tr>
                                            </thead>
                                            <tbody class="ui-sortable" id="fields_table">
                                            <?php $myArr=[];
                                                  $myTableArr=[] ;?>
                                                @if(count($fields))
                                                @foreach($fields as $fkey => $field)
                                                    @php
                                                        array_push($myArr,$field);

                                                        if($field['fieldType']=="table"){
                                                            $grids = \App\Models\StreamFieldGrid::where('stream_field_id', $field->id)->get();

                                                            $encoded_grid_data =urlencode($grids);
                                                            $fields[$fkey]["tableFieldData"] = $encoded_grid_data;

                                                        }
                                                    @endphp
                                                    <tr id="{{$field['orderCount']}}"
                                                        class="ui-sortable-handle fields_table">
                                                        <td scope="row"> {{$field['fieldName']}}</td>
                                                        <td> {{$field['fieldType']}} </td>
                                                        <td> {{$field['isRequired']}} </td>
                                                        <td> {{$field['isDuplicate']}} </td>
                                                        <td> {{$field['isCumulative']}} </td>
                                                        <td class="index">{{$field['orderCount']}}</td>
                                                        <td>
                                                            <div class="btn-group" role="group"
                                                                 aria-label="Basic example">
                                                                <button type="button"
                                                                        class="btn table_btn  update_btn text-white" onclick="updateFieldFromList( {{$field['orderCount']}} )" >
                                                                    Update
                                                                </button>

                                                                <form action="{{route('dashboard.stream.delete_field')}}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="id" value="{{$field['id']}}">
                                                                    <button type="submit"
                                                                            class="btn  table_btn delete_btn text-white" >
                                                                        Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                        <input type="hidden"
                                                               name="fields[{{$field['orderCount']}}][fieldName]"
                                                               value="{{$field['fieldName']}}">
                                                        <input type="hidden"
                                                               name="fields[{{$field['orderCount']}}][id]"
                                                               value="{{$field['id']}}">
                                                        <input type="hidden"
                                                               name="fields[{{$field['orderCount']}}][fieldType]"
                                                               value="{{$field['fieldType']}}">
                                                        <input type="hidden"
                                                               name="fields[{{$field['orderCount']}}][isRequired]"
                                                               value="{{$field['isRequired']}}">
                                                        <input type="hidden"
                                                               name="fields[{{$field['orderCount']}}][isDuplicate]"
                                                               value="{{$field['isDuplicate']}}">
                                                        <input type="hidden"
                                                               name="fields[{{$field['orderCount']}}][isCumulative]"
                                                               value="{{$field['isCumulative']}}">
                                                        <input type="hidden"
                                                               name="fields[{{$field['orderCount']}}][orderCount]"
                                                               value="{{$field['orderCount']}}">
                                                    </tr>
                                                @endforeach
                                                @php

                                                @endphp
                                            @endif
                                            </tbody>
                                        </table>         <!-- Modal -->
                                            <div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                             <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this field from database ?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary">Save changes</button>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-12">
                                        <div class="btn-group btn_group_padding">
                                            <button class="btn btn-primary table_btn text-white" type="submit"> Save</button>
                                            <a href="{{route('dashboard.streams',$form_id)}}" class="btn table_btn cancel_modal_btn text-white" >
                                                Cancel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script src="https://code.jquery.com/jquery-2.2.4.js"
                    integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
                    integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>

           <script>
               $( document ).ready(function() {
                 $("input[class=dropdown_required][value=no]").prop('checked', true);
               });
           </script>
            <script type="text/javascript">
             var db_fields_data=<?php echo json_encode($myArr) ?>;

             console.log("db_fields_data",db_fields_data);
             $( document ).ready(function() {
                $("input[class=field_required][value=no]").prop('checked', true);
             });
            $('input[name="tableFieldType"]').click(function(){
                var val=$('input[name="tableFieldType"]:checked').val();
                var dropdowntable_val=$('input[name="tableDropdown"]:checked').val();
                console.log(dropdowntable_val);
                if(val=="row"){
                    $(".hide_row").hide();
                    $(".table-dropdown-switch").hide();
                }else if(val=="row" && dropdowntable_val=="yes" ){
                    $(".table-dropdown-switch").hide();
                }else if(val=="column" && dropdowntable_val=="yes"){
                    $(".table-dropdown-switch").show();
                    $(".hide_row").show();
                }else{
                    $(".hide_row").show();
                }
            });

                var tableData = [];
                var recordData = [];
                if(db_fields_data){
                    recordData = db_fields_data;
                 }

                console.log("tableData",tableData);
                console.log("recordData",recordData);
                function openCity(evt, cityName) {
                    console.log("openCity function");
                    let headingText = '';
                    let cardId = cityName === 'table' ? "table" : 'textarea';
                    if (cityName === 'table') {
                        cardId = 'table'
                    } else if (cityName === 'select') {
                        cardId = 'select'
                    } else {
                        cardId = 'textarea';
                    }
                    if (cityName == 'number'){
                        $('#only-for-number').css('display','block')
                    }else{
                        $('#only-for-number').css('display','none')
                    }
                    switch (cityName) {
                        case 'text':
                            headingText = "Text Field";
                            break;
                        case 'textarea':
                            headingText = "Long Text Field";
                            break;
                        case 'date':
                            headingText = "Date Field";
                            break;
                        case 'file':
                            headingText = "Image Field";
                            break;
                        case 'number':
                            headingText = "Numeric Field";
                            break;
                    }
                    $("#field_type").val(cityName);
                    $("#field-card-heading").text(headingText);
                    var i, tabcontent, tablinks;
                    tabcontent = document.getElementsByClassName("tabcontent");
                    for (i = 0; i < tabcontent.length; i++) {
                        tabcontent[i].style.display = "none";
                    }
                    tablinks = document.getElementsByClassName("tablinks");
                    for (i = 0; i < tablinks.length; i++) {
                        tablinks[i].className = tablinks[i].className.replace(" active", "");
                    }
                    document.getElementById(cardId).style.display = "block";
                    if (evt) {
                        evt.currentTarget.className += " active";
                    }else{
                        $('.tab-'+cityName).addClass('active');
                    }
                }
                function updateValues(obj , cityName) {
                    console.log("updateValues function");
                    console.log("obj",obj);
                    console.log(cityName);
                    switch (cityName) {
                        case 'text':
                            $("#field_name").val(obj.fieldName);
                            $("input[class=field_required][value="+obj.isRequired+"]").prop("checked",true);
                            $("input[name=field_duplicate][value="+obj.isDuplicate+"]").prop("checked",true);
                            $(".order_count").val(obj.orderCount);
                            $(".textarea_addbtn").text("Update");
                            // $('.tab-text').addClass('active');
                            break;
                        case 'textarea':
                            $("#field_name").val(obj.fieldName);
                            $("input[class=field_required][value="+obj.isRequired+"]").prop("checked",true);
                            $("input[name=field_duplicate][value="+obj.isDuplicate+"]").prop("checked",true);
                            $(".order_count").val(obj.orderCount);
                            $(".textarea_addbtn").text("Update");
                            // $('.tab-textarea').addClass('active');
                            break;
                        case 'date':
                            $("#field_name").val(obj.fieldName);
                            $("input[class=field_required][value="+obj.isRequired+"]").prop("checked",true);
                            $("input[name=field_duplicate][value="+obj.isDuplicate+"]").prop("checked",true);
                            $(".order_count").val(obj.orderCount);
                            $(".textarea_addbtn").text("Update");
                            // $('.tab-date').addClass('active');
                            break;
                        case 'file':
                            $("#field_name").val(obj.fieldName);
                            $("input[class=field_required][value="+obj.isRequired+"]").prop("checked",true);
                            $("input[name=field_duplicate][value="+obj.isDuplicate+"]").prop("checked",true);
                            $(".order_count").val(obj.orderCount);
                            $(".textarea_addbtn").text("Update");
                            // $('.tab-img').addClass('active');
                            break;
                        case 'number':
                            $("#field_name").val(obj.fieldName);
                            $("input[class=field_required][value="+obj.isRequired+"]").prop("checked",true);
                            $("input[name=field_duplicate][value="+obj.isDuplicate+"]").prop("checked",true);
                            $("input[name=field_cumulative][value="+obj.isCumulative+"]").prop("checked",true);
                            $(".order_count").val(obj.orderCount);
                            $(".textarea_addbtn").text("Update");
                            // $('.tab-numeric').addClass('active');
                            break;
                        case 'select':
                            $("#drop_field_name").val(obj.fieldName);
                            $("input[class=dropdown_required][value="+obj.isRequired+"]").prop("checked",true);
                            $("#field_options").val(obj.fieldOptions);
                            $('.tab-select').addClass('active');
                            $(".dropdown_hidden_text").val(obj.orderCount);
                            $(".dropdown_addbtn").text("Update");
                            break;
                        case 'table':
                            $("#table-field-rows").empty();
                            $(".table_addbtn").text("Update Table");
                            $(".tablehiddenfield").val(obj.orderCount);
                            console.log("I am in table ");
                            if(obj.tableData){
                                var decodevalue= decodeURIComponent(obj.tableData);
                            }
                            if(obj.tableFieldData){
                                var decodevalue= decodeURIComponent(obj.tableFieldData);
                            }
                            console.log("decode value",decodevalue);
                            var tablevalues=JSON.parse(decodevalue);
                            // var tablevalues=JSON.parse(decodevalue.trim().replace(/"$/, '').replace(/^"/,''));
                            console.log("tablevalues",tablevalues);
                            var table_name=$("#table_name").val();
                            if(!table_name){
                                $("#table_name").val(obj.fieldName);
                              }
                            tablevalues.forEach((value,key)=>{
                              console.log("value",value);
                              let db_id = "";
                              let stream_field_id="";
                              if(value.id){
                                  db_id = value.id ;
                              }
                              if(value.stream_field_id){
                                stream_field_id = value.stream_field_id;
                              }
                              console.log("key",key);
                              let   html = ""
                              if(db_id){
                                    html =   '<tr class="ui-sortable-handle table_rows_count" db_id="'+db_id+'" stream_field_id="'+stream_field_id+'"  id="table'+ value.order_count +'">'
                                    html += '<td scope="row"> ' + value.name + '</td>'
                                    html += '<td> ' + value.type + '</td>'
                                    html += '<td class="index"> ' + value.order_count + '</td>'
                                    html += '<td>'
                                    html += '<div class="btn-group" role="group" aria-label="Basic example">'
                                    html += '<button type="button" class="btn table_btn  update_btn text-white" onclick="updateTableField(' + value.order_count + ')" > Update </button>'
                                    html += '<form action="{{route('dashboard.stream.delete_grid_field')}}" method="POST">'
                                    html +=  '@csrf'
                                    html +=  '<input type="hidden" name="id" value="'+db_id+'">'
                                    html +=  '<button type="submit" class="btn  table_btn delete_btn text-white" > Delete</button>'
                                    html +=  '</form>'
                                    html += '</div>'
                                    html += '</td>'
                                    html += '</tr>';

                              }else{
                                    html =   '<tr class="ui-sortable-handle table_rows_count" db_id="'+db_id+'" stream_field_id="'+stream_field_id+'"  id="table'+ value.order_count +'">'
                                    html += '<td scope="row"> ' + value.name + '</td>'
                                    html += '<td> ' + value.type + '</td>'
                                    html += '<td class="index"> ' + value.order_count + '</td>'
                                    html += '<td>'
                                    html += '<div class="btn-group" role="group" aria-label="Basic example">'
                                    html += '<button type="button" class="btn table_btn  update_btn text-white" onclick="updateTableField(' + value.order_count + ')" > Update </button>'
                                    html += '<button type="button" class="btn  table_btn delete_btn text-white" onclick="removeFieldFromTableList(' + value.order_count + ')">Delete</button>'
                                    html += '</div>'
                                    html += '</td>'
                                    html += '</tr>';

                              }



                                    $("#table-field-rows").append(html)
                                    // tableData.push(data);
                            });
                           break;
                    }

                }

                // Get the element with id="defaultOpen" and click on it
                document.getElementById("defaultOpen").click();
                var fixHelperModified = function (e, tr) {
                        var $originals = tr.children();
                        var $helper = tr.clone();
                        $helper.children().each(function (index) {
                            $(this).width($originals.eq(index).width())
                        });
                        return $helper;
                    },
                    updateIndex = function (e, ui) {
                        $('td.index', ui.item.parent()).each(function (i) {
                            $(this).html(i + 1);
                        });
                        $('input[type=text]', ui.item.parent()).each(function (i) {
                            $(this).val(i + 1);
                        });
                    };

                $("#myTable tbody").sortable({
                    helper: fixHelperModified,
                    stop: updateIndex
                }).disableSelection();

                $("#myTableTwo tbody").sortable({
                    helper: fixHelperModified,
                    stop: updateIndex
                }).disableSelection();

                $("tbody").sortable({
                    distance: 5,
                    delay: 100,
                    opacity: 0.6,
                    cursor: 'move',
                    update: function () {
                    }
                });
                window.alert = function () {
                };
                var defaultCSS = document.getElementById('bootstrap-css');
                function changeCSS(css) {
                    if (css) $('head > link').filter(':first').replaceWith('<link rel="stylesheet" href="' + css + '" type="text/css" />');
                    else $('head > link').filter(':first').replaceWith(defaultCSS);
                }
                $(document).ready(function () {
                    var iframe_height = parseInt($('html').height());
                    window.parent.postMessage(iframe_height, 'https://bootsnipp.com');
                });

                const addField = () => {
                    console.log("add field function");
                    console.log('recordData ',recordData)
                    console.log("table data",tableData);

                    let fieldType = $("#field_type").val();
                    let selector = (fieldType == 'select') ? '#drop_field_name' : (fieldType == 'table') ? '#table_name' : '#field_name'
                    let fieldName = $(selector).val();
                    console.log("field_name selector",fieldName);
                    let field_ordercount= "";
                    var textarea_hidden=$(".order_count").val();
                    var tablefieldhidden=$(".tablehiddenfield").val();
                    console.log("textarea_hidden",textarea_hidden);
                    var dropdown_hidden=$(".dropdown_hidden_text").val();
                    console.log("dropdown_hideen",dropdown_hidden);
                    if(textarea_hidden){
                        field_ordercount = textarea_hidden;
                    }else if(dropdown_hidden){
                        field_ordercount = dropdown_hidden;
                    }else{
                        field_ordercount = tablefieldhidden;
                        console.log("table field hidden id",field_ordercount);
                    }
                    console.log("ordercount",field_ordercount);
                    let fieldOptions = $("#field_options").val() ;
                    let isRequired = $('input[name=field_required]:checked').val();
                    let isDuplicate = $('input[name=field_duplicate]:checked').val();
                    let isCumulative = $('input[name=field_cumulative]:checked').val();
                    let isTableCumulative = $('input[name=table_cumulative_value]:checked').val();
                    isCumulative = fieldType == 'table' ? isTableCumulative : isCumulative ;
                    let orderCount = $(".fields_table");
                    orderCount = orderCount.length + 1;
                    let tableFieldData = '';
                    if (!fieldName && !field_ordercount) {
                        toastr.error('Field name is required')
                        return false;
                    }
                    if (fieldType == 'select') {
                        if (!fieldOptions) {
                            toastr.error("Select options are required.")
                            return false;
                        }
                    }
                    if(fieldType == 'table'){
                        for (let i = 0; i < recordData.length; i++) {
                        if (recordData[i].fieldName == fieldName && !tablefieldhidden){
                            toastr.error("This field is already exist!")
                            return false;
                         }
                       }
                    }else{
                        for (let i = 0; i < recordData.length; i++) {
                        if (recordData[i].fieldName == fieldName && !field_ordercount){
                            toastr.error("This field is already exist!")
                            return false;
                         }
                       }
                    }
                    console.log('tableData ',tableData);

                   let data={
                        fieldType,
                        fieldName,
                        fieldOptions,
                        isRequired,
                        isDuplicate,
                        isCumulative,
                        orderCount,
                        tableFieldData:fieldType == 'table' ? encodeURIComponent(JSON.stringify(tableData)) : '',
                    };
                      console.log('field_ordercount ',field_ordercount);
                      console.log("field data type",fieldType);
                      if(field_ordercount){
                         if(fieldType == 'table' && tableData.length==0 ){
                           console.log("table length is zero");
                           recordData.splice(field_ordercount-1,1);
                           $('#'+ field_ordercount).remove();
                           $("#table_name").val('');
                           $(".tablehiddenfield").val('');
                           $("input[name=table_cumulative_value][value=no]").prop('checked',true);
                           return false;
                         }

                         data.orderCount = field_ordercount
                        let newRow = '<td scope="row"> ' + fieldName + '</td>'
                            newRow += '<td> ' + fieldType + ' </td>'
                            newRow += '<td> ' + isRequired + ' </td>'
                            newRow += '<td> ' + isDuplicate + ' </td>'
                            newRow += '<td> ' + isCumulative + ' </td>'
                            newRow += '<td class="index">' + field_ordercount + '</td>'
                            newRow += '<td>'
                            newRow += '<div class="btn-group" role="group" aria-label="Basic example">'
                            newRow += '<button type="button" class="btn table_btn  update_btn text-white" onclick="updateFieldFromList(' + field_ordercount + ')">Update</button>'
                            newRow += '<button type="button" class="btn  table_btn delete_btn text-white" onclick="removeFieldFromList(' + field_ordercount + ')">Delete</button>'
                            newRow += '</div>'
                            newRow += '</td>'
                            newRow += '<input type="hidden" name="fields[' + field_ordercount + '][fieldName]"  value="' + fieldName + '" >'
                            newRow += '<input type="hidden" name="fields[' + field_ordercount + '][fieldType]"  value="' + fieldType + '" >'
                            newRow += '<input type="hidden" name="fields[' + field_ordercount + '][isRequired]"  value="' + isRequired + '" >'
                            newRow += '<input type="hidden" name="fields[' + field_ordercount + '][isDuplicate]"  value="' + isDuplicate + '" >'
                            newRow += '<input type="hidden" name="fields[' + field_ordercount + '][isCumulative]"  value="' + isCumulative + '" >'
                            newRow += '<input type="hidden" name="fields[' + field_ordercount + '][orderCount]"  value="' + field_ordercount + '" >'
                            if (fieldType == 'select') {
                                newRow += '<input type="hidden" name="fields[' + field_ordercount + '][fieldOptions]"  value="' + fieldOptions + '" >'
                            }
                            if (fieldType == 'table') {
                                console.log("I am in the table field");
                                tableFieldData = JSON.stringify(tableData)
                                tableFieldData = encodeURIComponent(tableFieldData)
                                newRow += '<input type="hidden" name="fields[' + field_ordercount + '][tableData]"  value="' + tableFieldData + '" >'
                            }

                        console.log('recordData ',recordData)
                        console.log('data ',data)
                        $("#"+field_ordercount).html(newRow);
                        recordData[field_ordercount - 1] = data;
                        console.log('recordData ',recordData)
                   }else{
                       if(fieldType == 'table' && tableData.length==0 ){
                           $("#table_name").val('');
                           $(".tablehiddenfield").val('');
                           $("input[name=table_cumulative_value][value=no]").prop('checked',true);
                           console.log("table length is zero and field_ordercount is zero");
                           console.log("recordData",recordData);
                        //    recordData.splice(recordData.length-1,1);
                           console.log("recordData",recordData);
                           $('#'+ field_ordercount).remove();
                           return false;
                         }
                        let newRow = '<tr id="' + orderCount + '" class="ui-sortable-handle fields_table">'
                        newRow += '<td scope="row"> ' + fieldName + '</td>'
                        newRow += '<td> ' + fieldType + ' </td>'
                        newRow += '<td> ' + isRequired + ' </td>'
                        newRow += '<td> ' + isDuplicate + ' </td>'
                        newRow += '<td> ' + isCumulative + ' </td>'
                        newRow += '<td class="index">' + orderCount + '</td>'
                        newRow += '<td>'
                        newRow += '<div class="btn-group" role="group" aria-label="Basic example">'
                        newRow += '<button type="button" class="btn table_btn  update_btn text-white" onclick="updateFieldFromList(' + orderCount + ')">Update</button>'
                        newRow += '<button type="button" class="btn  table_btn delete_btn text-white" onclick="removeFieldFromList(' + orderCount + ')">Delete</button>'
                        newRow += '</div>'
                        newRow += '</td>'
                        newRow += '<input type="hidden" name="fields[' + orderCount + '][fieldName]"  value="' + fieldName + '" >'
                        newRow += '<input type="hidden" name="fields[' + orderCount + '][fieldType]"  value="' + fieldType + '" >'
                        newRow += '<input type="hidden" name="fields[' + orderCount + '][isRequired]"  value="' + isRequired + '" >'
                        newRow += '<input type="hidden" name="fields[' + orderCount + '][isDuplicate]"  value="' + isDuplicate + '" >'
                        newRow += '<input type="hidden" name="fields[' + orderCount + '][isCumulative]"  value="' + isCumulative + '" >'
                        newRow += '<input type="hidden" name="fields[' + orderCount + '][orderCount]"  value="' + orderCount + '" >'
                        if (fieldType == 'select') {
                            newRow += '<input type="hidden" name="fields[' + orderCount + '][fieldOptions]"  value="' + fieldOptions + '" >'
                        }
                        if (fieldType == 'table') {
                            tableFieldData = JSON.stringify(tableData)
                            tableFieldData = encodeURIComponent(tableFieldData)
                            newRow += '<input type="hidden" name="fields[' + orderCount + '][tableData]"  value="' + tableFieldData + '" >'
                        }
                        newRow += '</tr>';
                        $("#fields_table").append(newRow);
                        recordData.push(data);
                   }
                   $(".order_count").val('')
                   $(".tablehiddenfield").val('');
                   $(".dropdown_hidden_text").val('');
                    $(selector).val('')
                    $("#field_options").val('')
                    for (let i = 0; i < tableData.length; i++) {
                        $('#table' + tableData[i].orderCount).remove();
                    }
                    console.log("record data",recordData);
                    tableData = [];
                    clear_table_fields();
                }

                const addTableField = () => {
                    console.log("addTableField function");
                    console.log("data in addTableField",tableData);
                    let table_name     =    $("#table_name").val();
                    let cumulative_value = $("input[name=table_cumulative_value]").val();
                    let type = $('input[name=tableFieldType]:checked').val()
                    let name = $("#table-field-name").val();
                    let is_dropdown = $('input[name=tableDropdown]:checked').val()
                    let field_options = $("#table_field_options").val();
                    let tableHiddenId = $("#table_hidden_id").val();
                    let table_data_db_id=$("#table_data_db_id").val();
                    let stream_field_id= $("#stream_field_id").val();
                    console.log("step 1");

                    if (!name && !tableHiddenId) {
                        toastr.error('Field name is required')
                        return false
                    }

                    for (let i = 0; i < tableData.length; i++) {

                        // if (table_name){
                        //     tableData[i].table_name=table_name;
                        // }
                        if (tableData[i]?.name == name && !tableHiddenId){
                            toastr.error('This field is already exist');
                            return false
                        }
                    }
                    console.log("step 2");
                    let orderCount = $(".table_rows_count")
                    orderCount = orderCount.length + 1
                    let rowId = 'table' + orderCount;

                    let data = {

                        order_count: tableHiddenId?.length > 0 ? tableHiddenId : orderCount,
                        name,
                        type,
                        is_dropdown,
                        field_options,
                        id:table_data_db_id ? table_data_db_id : '',
                        stream_field_id:stream_field_id ? stream_field_id : '',

                    }
                    console.log("step 3");
                    console.log("addTableField function data object",data);
                    if (tableHiddenId) {
                        let html = '<td scope="row"> ' + name + '</td>'
                        html += '<td> ' + type + '</td>'
                        html += '<td class="index"> ' + tableHiddenId + '</td>'
                        html += '<td>'
                        html += '<div class="btn-group" role="group" aria-label="Basic example">'
                        html += '<button type="button" class="btn table_btn  update_btn text-white" onclick="updateTableField(' + tableHiddenId + ')" > Update </button>'
                        html += '<button type="button" class="btn  table_btn delete_btn text-white" onclick="removeFieldFromTableList(' + tableHiddenId + ')">Delete</button>'
                        html += '</div>'
                        html += '</td>'
                        $("#table"+tableHiddenId).html(html);
                        tableData[tableHiddenId - 1] = data;
                    } else {
                        let html = '<tr class="ui-sortable-handle table_rows_count" id="' + rowId + '">'
                        html += '<td scope="row"> ' + name + '</td>'
                        html += '<td> ' + type + '</td>'
                        html += '<td class="index"> ' + orderCount + '</td>'
                        html += '<td>'
                        html += '<div class="btn-group" role="group" aria-label="Basic example">'
                        html += '<button type="button" class="btn table_btn  update_btn text-white" onclick="updateTableField(' + orderCount + ')" > Update </button>'
                        html += '<button type="button" class="btn  table_btn delete_btn text-white" onclick="removeFieldFromTableList(' + orderCount + ')">Delete</button>'
                        html += '</div>'
                        html += '</td>'
                        html += '</tr>';
                        $("#table-field-rows").append(html)
                        tableData.push(data)
                    }
                    $("#table-field-name").val('');
                    $("#table_field_options").val('');
                    $("#table_hidden_id").val('');
                    $("#table_data_db_id").val('');
                    $("#stream_field_id").val('');
                }

                const removeFieldFromList = (id) => {
                    $('#' + id).remove()
                }

                const removeFieldFromTableList = (id) => {
                    console.log("I am in removeFieldFromTableList");
                    console.log("table data in removeFieldFromTableList",tableData);
                    for (let i = 0; i < tableData.length; i++) {
                        if (tableData[i].order_count == id) {
                            tableData.splice(i, 1);
                        }
                    }
                    $('#table' + id).remove();
                    console.log(tableData.length);
                    clear_table_fields_on_delete();

                }
                const updateFieldFromList = (record) => {
                    console.log("updateFieldFromList function");
                    console.log('record', record);
                    console.log('record data', recordData);
                    let selected = recordData[record - 1];
                    console.log("selected",selected);
                    updateValues(selected,selected.fieldType);
                    if (selected.fieldType=="table") {
                         console.log("a gya ");
                         console.log('selected',selected);

                    //  if(selected.tableData){
                    //     let ff = decodeURIComponent(selected.tableData);
                    //  }
                      console.log("selected.tableFieldData",selected.tableFieldData);
                     let ff = decodeURIComponent(selected.tableFieldData);
                    ff = JSON.parse(ff);
                    tableData = ff;
                    }
                    openCity(null,selected.fieldType);
                    // $("#field_name").val(selected.fieldName);
                    // $("input[class=field_required][value="+selected.isRequired+"]").prop("checked",true);
                    // $("input[name=field_duplicate][value="+selected.isDuplicate+"]").prop("checked",true);
                }
                const updateTableField = (orderCount) => {
                    console.log("I am in updateTableField");
                    let selector = parseInt(orderCount) - 1 ;
                    console.log("selector",selector);
                    console.log("table data in updateTableField",tableData);
                    let data = tableData[selector];
                    console.log("table-data",data);
                    if(data.cumulative_value==null){
                        data.cumulative_value="no";
                    }
                    // $("#table_name").val(data.table_name);
                    $("input[name=table_cumulative_value][value="+data.cumulative_value+"]").prop("checked",true);
                    $("#table-field-name").val(data.name);
                    $("#table_field_options").val(data.field_options);
                    $("#table_data_db_id").val(data.id);
                    $("#stream_field_id").val(data.stream_field_id);
                    $("#table_hidden_id").val(orderCount);
                    let tableDropdown = data.is_dropdown;
                    if(tableDropdown== 0){
                        tableDropdown="no" ;
                    }else if(tableDropdown==1){
                        tableDropdown="yes" ;
                    }
                    let tableFieldType = data.tableFieldType;
                    if(data.type=="column"){
                        $(".table-dropdown-switch").show();
                        $(".hide_row").show();
                        if (tableDropdown == 'yes') {
                                $("input[name=tableDropdown][value='yes']").prop("checked", true);
                                $(".table-dropdown-switch").show();
                            } else {
                                $("input[name=tableDropdown][value='no']").prop("checked", true);
                                $(".table-dropdown-switch").hide();
                                $("#table_field_options").val('');
                            }
                    }else{
                        $(".table-dropdown-switch").hide();
                        $(".hide_row").hide();
                    }
                    // if (data.tableDropdown == 'yes') {
                    //     $("input[name=tableDropdown][value='yes']").prop("checked", true);
                    //     $(".table-dropdown-switch").show();

                    // } else {
                    //     $("input[name=tableDropdown][value='no']").prop("checked", true);
                    // }
                    if (data.type == 'row'){
                        $("input[name=tableFieldType][value='row']").prop("checked", true);
                    } else {
                        $("input[name=tableFieldType][value='column']").prop("checked", true);
                    }
                }

                const tableDropDown = (type) => {
                    if (type == 'yes') {
                        $(".table-dropdown-switch").css('display', 'block')
                    } else {
                        $(".table-dropdown-switch").css('display', 'none')
                    }
                }
                $(".tablinks").click(function(){
                    $(".dropdown_addbtn").text("Add");
                    $(".textarea_addbtn").text("Add");
                    $(".table_addbtn").text("Add to Table");
                    $("#field_name").val("");
                    $("input[class=field_required][value='no']").prop("checked",true);
                    $("input[name=field_duplicate][value='no']").prop("checked",true);
                    $("input[name=field_cumulative][value='no']").prop("checked",true);
                    // $("input[class=dropdown_required][value='no']").prop("checked",true);
                    $("#field_options").val("");
                    $("#drop_field_name").val("");
                    $("input[name=tableFieldType][value='column']").prop("checked", true);
                    $("input[name=tableDropdown][value='no']").prop("checked", true);
                })

            function textarea_reset(){
                $("#field_name").val("");
                $("input[class=field_required][value='no']").prop("checked",true);
                $("input[name=field_duplicate][value='no']").prop("checked",true);
                $(".order_count").val("");
            }
            function select_reset(){
                $("#drop_field_name").val("");
                $("input[class=dropdown_required][value='no']").prop("checked",true);
                $("#field_options").val("");
                $(".dropdown_hidden_text").val("");
            }
            function clear_table_fields(){
                $("#table_name").val('');
                $(".tablehiddenfield").val('');
                $("input[name=table_cumulative_value][value=no]").prop('checked',true);
                $("input[name=tableFieldType][value=column]").prop('checked',true);
                $("input[name=tableDropdown][value=no]").prop('checked',true);
                $("#table-field-name").val('');
                $("#table_hidden_id").val('');
                $("#table-field-rows").empty();
            }
            function clear_table_fields_on_delete(){
                // $("#table_name").val('');
                // $("input[name=table_cumulative_value][value=no]").prop('checked',true);
                $("input[name=tableFieldType][value=column]").prop('checked',true);
                $("input[name=tableDropdown][value=no]").prop('checked',true);
                $("#table-field-name").val('');
                $("#table_hidden_id").val('');

            }
            </script>


@endsection
