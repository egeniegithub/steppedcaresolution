@extends('layouts.app')

@section('title', 'Add Stream')

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
                                                        onclick="openCity(event, 'text')"><img class="image_black "
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
                                                <button type="button" class="tablinks li_light_border numeric_white tab-numeric"
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
                                                <button type="button" class="tablinks li_light_border tab-img"
                                                        onclick="openCity(event, 'file')"><img class="image_black"
                                                                                                src="{{url('/assets/images/image_black.png')}}"/>
                                                    <img class="image_white"
                                                         src="{{url('/assets/images/image_white.png')}}"/> <span
                                                        class="light_grey_text"> Image</span></button>
                                                <button type="button" class="tablinks li_light_border tab-dropdown"
                                                        onclick="openCity(event, 'dropdown')" ><img
                                                        class="image_black"
                                                        src="{{url('/assets/images/table_black.png')}}"/> <img
                                                        class="image_white"
                                                        src="{{url('/assets/images/image_white.png')}}"/> <span
                                                        class="light_grey_text">Drop Down</span></button>
                                                <button type="button" class="tablinks li_light_border  tab-table"
                                                        onclick="openCity(event, 'table')" id="defaultOpen"><img
                                                        class="image_black"
                                                        src="{{url('/assets/images/table_black.png')}}"/> <img
                                                        class="image_white"
                                                        src="{{url('/assets/images/image_white.png')}}"/> <span
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
                                                                        <input type="radio" checked="checked"
                                                                               name="field_required" value="yes"
                                                                               id="field_required">
                                                                        <span class="checkmark"></span>
                                                                        Yes
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <label class="radio_container">No
                                                                        <input type="radio" checked="checked"
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
                                                                        <input type="radio" checked="checked"
                                                                               name="field_duplicate" value="yes"
                                                                               id="field_duplicate">
                                                                        <span class="checkmark"></span>
                                                                        Yes
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <label class="radio_container">No
                                                                        <input type="radio" checked="checked"
                                                                               name="field_duplicate"
                                                                               id="field_duplicate" value="no">
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th> Cumulative Value:</th>
                                                                <td>
                                                                    <label class="radio_container">
                                                                        <input type="radio" checked="checked"
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
                                                            <button class="btn table_btn del_modal_btn text-white"
                                                                    type="button"
                                                                    onclick="addField()"> Add
                                                            </button>
                                                            <button class="btn table_btn cancel_modal_btn text-white"
                                                                    type="button">
                                                                Reset
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-8 col-lg-8 col-md-8 col-12 tabcontent " id="dropdown">
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
                                                                        <input type="radio" checked="checked"
                                                                               name="field_required" value="yes"
                                                                               id="field_required">
                                                                        <span class="checkmark"></span>
                                                                        Yes
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <label class="radio_container">No
                                                                        <input type="radio" checked="checked"
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
                                                            <button class="btn table_btn del_modal_btn text-white"
                                                                    type="button"
                                                                    onclick="addField()"> Add
                                                            </button>
                                                            <button class="btn table_btn cancel_modal_btn text-white"
                                                                    type="button">
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
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-lg-6 cumulative_table">
                                                        <table class="radio_table" style="width:75%">
                                                            <tr>
                                                                <th> Cumulative Value</th>
                                                                <td>
                                                                    <label class="radio_container">
                                                                        <input type="radio" checked="checked"
                                                                               name="table_cumulative_value">
                                                                        <span class="checkmark"></span>
                                                                        Yes
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <label class="radio_container">No
                                                                        <input type="radio" checked="checked"
                                                                               name="table_cumulative_value">
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-sm-12 col-lg-6 add_tables_margin">
                                                        <b class=""><a class="add_icon"
                                                                       href=""><span> Build Table</span></a></b>
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
                                                            <tr class="mb-2">
                                                                <td colspan="2"><span
                                                                        style="font-weight: bold">Dropdown</span></td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <label class="radio_container">
                                                                        <input type="radio" checked="checked"
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
                                                                    class="btn table_btn del_modal_btn text-white"
                                                                    onclick="addTableField()">
                                                                    Add to Table
                                                                </button>
                                                                <button
                                                                    type="button"
                                                                    class="btn table_btn cancel_modal_btn text-white">
                                                                    Cancel
                                                                </button>
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
                                                                class="table  table-bordered stream_data_table table_margin_adj">
                                                                <thead>
                                                                <tr>
                                                                    <td> Name</td>
                                                                    <td> Type</td>
                                                                    <td> Order</td>
                                                                    <td class="add_stream_table_one"> Actions
                                                                    </td>
                                                                </tr>
                                                                </thead>
                                                                <tbody id="table-field-rows">

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
                                                    <button class="btn stream_btn del_modal_btn text-white"
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
                                        <table class="table   table-bordered stream_primary_table table_margin_adj"
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
                                            @if(count($fields))
                                                @foreach($fields as $field)
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
                                                                        class="btn table_btn  update_btn text-white">
                                                                    Update
                                                                </button>
                                                                <button type="button"
                                                                        class="btn  table_btn delete_btn text-white"
                                                                        onclick="removeFieldFromList('{{$field['orderCount']}}')">
                                                                    Delete
                                                                </button>
                                                            </div>
                                                        </td>
                                                        <input type="hidden"
                                                               name="fields[{{$field['orderCount']}}][fieldName]"
                                                               value="{{$field['fieldName']}}">
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
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-12">
                                        <div class="btn-group btn_group_padding">
                                            <button class="btn table_btn del_modal_btn text-white" type="submit"> Save
                                            </button>
                                            <button class="btn table_btn cancel_modal_btn text-white" type="reset">
                                                Cancel
                                            </button>
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

            <script type="text/javascript">

                var tableData = []
                var recordData = []

                function openCity(evt, cityName) {
                    let headingText = '';
                    let cardId = cityName === 'table' ? "table" : 'textarea';
                    if (cityName === 'table') {
                        cardId = 'table'
                    } else if (cityName === 'dropdown') {
                        cardId = 'dropdown'
                    } else {
                        cardId = 'textarea';
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
                    $("#field_type").val(cityName)
                    $("#field-card-heading").text(headingText)

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
                        $('.tab-'+cityName).addClass('active')
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
                    let fieldType = $("#field_type").val();
                    let selector = (fieldType == 'dropdown') ? '#drop_field_name' : (fieldType == 'table') ? '#table_name' : '#field_name'
                    let fieldName = $(selector).val();
                    let fieldOptions = $("#field_options").val()
                    let isRequired = $('input[name=field_required]:checked').val()
                    let isDuplicate = $('input[name=field_duplicate]:checked').val()
                    let isCumulative = $('input[name=field_cumulative]:checked').val()
                    let isTableCumulative = $('input[name=table_cumulative_value]:checked').val()
                    isCumulative = fieldType == 'table' ? isTableCumulative : isCumulative
                    let orderCount = $(".fields_table")
                    orderCount = orderCount.length + 1
                    let tableFieldData = ''

                    if (!fieldName) {
                        toastr.error('Field name is required')
                        return false
                    }


                    if (fieldType == 'dropdown') {
                        if (!fieldOptions) {
                            toastr.error("Select options are required.")
                            return false
                        }
                    }

                    for (let i = 0; i < recordData.length; i++) {
                        if (recordData[i].fieldName == fieldName){
                            toastr.error("This field is already exist!")
                            return false;
                        }
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
                    if (fieldType == 'dropdown') {
                        newRow += '<input type="hidden" name="fields[' + orderCount + '][fieldOptions]"  value="' + fieldOptions + '" >'
                    }

                    if (fieldType == 'table') {
                        tableFieldData = JSON.stringify(tableData)
                        tableFieldData = encodeURIComponent(tableFieldData)
                        newRow += '<input type="hidden" name="fields[' + orderCount + '][tableData]"  value="' + tableFieldData + '" >'
                    }
                    newRow += '</tr>'

                    recordData.push({
                        fieldType,
                        fieldName,
                        fieldOptions,
                        isRequired,
                        isDuplicate,
                        isCumulative,
                        orderCount,
                        tableFieldData
                    });

                    $("#fields_table").append(newRow)
                    $(selector).val('')
                    $("#field_options").val('')
                    for (let i = 0; i < tableData.length; i++) {
                        $('#table' + tableData[i].orderCount).remove();
                    }
                    tableData = []
                }

                const addTableField = () => {

                    let type = $('input[name=tableFieldType]:checked').val()
                    let fieldName = $("#table-field-name").val();
                    let tableDropdown = $('input[name=tableDropdown]:checked').val()
                    let tableFieldOptions = $("#table_field_options").val();
                    let tableHiddenId = $("#table_hidden_id").val()

                    if (!fieldName) {
                        toastr.error('Field name is required')
                        return false
                    }

                    for (let i = 0; i < tableData.length; i++) {
                        if (tableData[i]?.fieldName == fieldName){
                            toastr.error('This field is already exist');
                            return false
                        }
                    }

                    let orderCount = $(".table_rows_count")
                    orderCount = orderCount.length + 1
                    let rowId = 'table' + orderCount;
                    let data = {
                        orderCount: tableHiddenId?.length > 0 ? tableHiddenId : orderCount,
                        fieldName,
                        type,
                        tableDropdown,
                        tableFieldOptions
                    }
                    if (tableHiddenId) {
                        let html = '<td scope="row"> ' + fieldName + '</td>'
                        html += '<td> ' + type + '</td>'
                        html += '<td> ' + tableHiddenId + '</td>'
                        html += '<td>'
                        html += '<div class="btn-group" role="group" aria-label="Basic example">'
                        html += '<button type="button" class="btn table_btn  update_btn text-white" onclick="updateTableField(' + tableHiddenId + ')" > Update </button>'
                        html += '<button type="button" class="btn  table_btn delete_btn text-white" onclick="removeFieldFromTableList(' + tableHiddenId + ')">Delete</button>'
                        html += '</div>'
                        html += '</td>'
                        $("#table" + tableHiddenId).html(html)
                        tableData[tableHiddenId - 1] = data
                    } else {
                        let html = '<tr class="table_rows_count" id="' + rowId + '">'
                        html += '<td scope="row"> ' + fieldName + '</td>'
                        html += '<td> ' + type + '</td>'
                        html += '<td> ' + orderCount + '</td>'
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
                    $("#table-field-name").val('')
                    $("#table_field_options").val('')
                    $("#table_hidden_id").val('')
                }

                const removeFieldFromList = (id) => {
                    $('#' + id).remove()
                }

                const removeFieldFromTableList = (id) => {
                    for (let i = 0; i < tableData.length; i++) {
                        if (tableData[i].orderCount == id) {
                            tableData.splice(i, 1)
                        }
                    }
                    $('#table' + id).remove()
                }

                const updateFieldFromList = (record) => {
                    let selected = recordData[record - 1]

                    openCity(null,selected.fieldType)
                }

                const updateTableField = (orderCount) => {
                    let selector = parseInt(orderCount) - 1
                    let data = tableData[selector]
                    $("#table-field-name").val(data.fieldName);
                    $("#table_field_options").val(data.tableFieldOptions);
                    $("#table_hidden_id").val(orderCount)
                    let tableDropdown = data.tableDropdown
                    let tableFieldType = data.tableFieldType
                    if (data.tableDropdown == 'yes') {
                        $("input[name=tableDropdown][value='yes']").prop("checked", true);
                    } else {
                        $("input[name=tableDropdown][value='no']").prop("checked", true);
                    }
                    if (data.tableFieldType == 'row') {
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

            </script>


@endsection
