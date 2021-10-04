@extends('layouts.app')

@section('title', 'Update Data')

@section('content')
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="container">
                <div class="row blue-border-bottom">
                    <div class="col-sm-6 col-md-4 col-lg-4 px-0 stream_update_title">
                        <div class="top-header pt-2">
                            <h3 class="margin-page-title">{{$stream->name}}</h3>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-4 px-0 update_stream_mid">
                        <div class="top-header pt-2">
                            <h3 class="margin-page-title">
                                status : <span class="blue_span">{{$stream->status}}</span>
                            </h3>
                        </div>
                    </div>
                    {{--<div class="col-sm-6 col-md-4 col-lg-4 px-0">
                        <div class="top-header pt-2 update_stream_right_align">
                            <a class="btn update_status_btn text-white" href="{{route('dashboard')}}">Go to Stream
                                List</a>
                        </div>
                    </div>--}}
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table_div_padding">
                            <div class="card mb-0">
                                <div class="card_header">
                                    <h5 class="header_padding_adj">{{$stream->name}}</h5>
                                </div>
                                <form method="POST" action="{{ route('dashboard.stream.stream_post') }}"
                                      class="update_stream_form" enctype="multipart/form-data" id="fields_form">
                                    <div>
                                        @csrf

                                        <input type="hidden" name="stream_id" value="{{$stream->id}}">

                                        @if($stream->getFields)
                                            @foreach($stream->getFields as $fieldKey => $field)
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="exampleFormControlTextarea1">{{$field->fieldName}} {{$field->isRequired == 'no' ? '' : "*"}}</label>
                                                            @php
                                                                $required = $field->isRequired == 'no' ? '' : "required";
                                                            @endphp
                                                            @switch($field->fieldType)
                                                                @case('text')
                                                                <input type="text" class="form-control white_input"
                                                                       name="field[{{$field->id}}]"
                                                                       value="{{$field->value}}" {{$required}}>
                                                                @break

                                                                @case('textarea')
                                                                <textarea class="form-control white_input"
                                                                          name="field[{{$field->id}}]"
                                                                          {{$required}} rows="5">{{$field->value}}</textarea>
                                                                @break

                                                                @case('number')
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <input type="number" id="field{{$field->id}}" data-cumulative="{{$field->id}}" class="form-control white_input cumulative-class"
                                                                               name="field[{{$field->id}}]"
                                                                               value="{{$field->value}}" {{$required}}>
                                                                    </div>
                                                                    <div class="col-md-6" style="margin-top: -30px">
                                                                        @php
                                                                            $previous_cumulative = \App\Models\StreamField::where('id', $field->previous_id)->value('cumulative_value');
                                                                        @endphp

                                                                        @if($field->isCumulative == 'yes')
                                                                            <label  for="exampleFormControlTextarea1">Cumulative Value</label>
                                                                            <input type="hidden" id="cumulative_field_hidden{{$field->id}}" value="{{$previous_cumulative ?? 0}}">
                                                                            <input type="number" id="cumulative_field{{$field->id}}" class="form-control white_input"
                                                                                   name="cumulative_field[{{$field->id}}]"
                                                                                   value="{{$field->cumulative_value}}" readonly>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                @break

                                                                @case('date')
                                                                <input type="date" class="form-control white_input"
                                                                       name="field[{{$field->id}}]"
                                                                       value="{{$field->value}}" {{$required}}>
                                                                @break

                                                                @case('file')
                                                                <input type="file" class="form-control white_input"
                                                                       src=""
                                                                       alt=""
                                                                       name="image[{{$field->id}}]" {{ $field->value ? "" : $required}}>
                                                                <p class="text-c-red">Image size should be less than 2MB (Max dimensions, height: 3500px - width: 2500px)</p>
                                                                <br>
                                                                <div class="text-center">
                                                                    @if(isset($field->value))
                                                                        <img
                                                                            src="{{asset('stream_answer_image')}}/{{$field->value}}"
                                                                            height="300px" width="500px" alt="No Img">
                                                                    @endif
                                                                </div>

                                                                @break

                                                                @case('select')
                                                                @php
                                                                    $options = explode(',', $field->fieldOptions);
                                                                @endphp
                                                                <select class="form-control white_input"
                                                                        name="field[{{$field->id}}]" {{$required}}>
                                                                    <option value="">Please Select</option>
                                                                    @foreach($options as $option)
                                                                        <option
                                                                            value="{{$option}}" {{( isset($field->value) && $option == $field->value) ? 'selected' : ''}}>{{$option}}</option>
                                                                    @endforeach
                                                                </select>
                                                                @break

                                                                @case('table')
                                                                @php
                                                                    $tableData = \App\Models\StreamFieldGrid::where('stream_field_id', $field->id)->orderBy('type', 'ASC')->orderBy('order_count', 'ASC')->get();
                                                                    $column_dropdown = array();
                                                                    $table_options = array();
                                                                @endphp

                                                                @if($tableData)
                                                                    <div class="table-responsive">
                                                                        <table class="table demographic_table platform_visitors rendered_table table-bordered">
                                                                            <thead>
                                                                            <tr>
                                                                                @php
                                                                                    $column_count = 0;
                                                                                @endphp
                                                                                @foreach($tableData as $table)
                                                                                    @if($table->type == 'column')
                                                                                        @php
                                                                                            if ($table->is_dropdown == 1){
                                                                                                array_push($column_dropdown, $column_count);
                                                                                                $table_options[$column_count] = explode(',',$table->field_options);
                                                                                            }
                                                                                            $column_count++;

                                                                                            $check_cumulative = \App\Models\StreamField::where('id', $table->stream_field_id)->value('isCumulative');
                                                                                        @endphp
                                                                                        @if($loop->iteration == 1)
                                                                                            <td></td>
                                                                                        @endif
                                                                                        <td>
                                                                                            {{$table->name}}
                                                                                        </td>

                                                                                        @if($check_cumulative == 'yes')
                                                                                            <td>
                                                                                                {{$table->name}} (Cumulative)
                                                                                            </td>
                                                                                        @endif
                                                                                    @endif
                                                                                @endforeach
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            @foreach($tableData as $table)
                                                                                @if($table->type == 'row')
                                                                                    @if($loop->iteration == 1)
                                                                                        <tr>
                                                                                            @for($i=0; $i<$column_count; $i++)
                                                                                                <td></td>
                                                                                            @endfor
                                                                                        </tr>
                                                                                    @endif
                                                                                    <tr>
                                                                                        <td>{{$table->name}}</td>
                                                                                        @for($i=0; $i<$column_count; $i++)
                                                                                            <td>
                                                                                                @php
                                                                                                    $value = json_decode($table->value);
                                                                                                @endphp
                                                                                                @if( in_array($i, $column_dropdown))
                                                                                                    @php
                                                                                                        $dropdowns = $table_options[$i];
                                                                                                    @endphp
                                                                                                    <select class="form-control editable_table_coloumn new_target" name="table_value[{{$table->id}}][{{$i}}]" id="">
                                                                                                        @foreach($dropdowns as $dropdown)
                                                                                                            <option value="{{$dropdown}}" {{$value ? ($dropdown == $value[$i] ? "selected" : "") : null}}>{{$dropdown}}</option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                @else
                                                                                                    <input type="text" id="current_value_{{$loop->iteration.$i}}" class="form-control editable_table_coloumn target_{{$loop->iteration}} new_target" num="{{$loop->iteration.$i}}" name="table_value[{{$table->id}}][{{$i}}]" value="{{$value ? $value[$i] : null}}">
                                                                                                @endif
                                                                                            </td>
                                                                                            @php
                                                                                                $check_cumulative = \App\Models\StreamField::where('id', $table->stream_field_id)->value('isCumulative');
                                                                                            @endphp
                                                                                            @if($check_cumulative == 'yes')
                                                                                                <td>
                                                                                                    @php
                                                                                                        $previous_cumulative_grid = \App\Models\StreamFieldGrid::where('id', $table->previous_id)->value('cumulative_value');
                                                                                                    @endphp
                                                                                                    <input type="hidden" id="for_sum{{$loop->iteration.$i}}" class="for_sum" readonly value="{{$previous_cumulative_grid ? json_decode($previous_cumulative_grid)[$i] : 0}}">
                                                                                                    <input type="text" id="cumulative_{{$loop->iteration.$i}}" class="form-control editable_table_coloumn" name="cumulative_table_value[{{$table->id}}][{{$i}}]" readonly value="{{$previous_cumulative_grid ? json_decode($previous_cumulative_grid)[$i] : ($table->cumulative_value ? json_decode($table->cumulative_value)[$i] : 0)}}">
                                                                                                </td>
                                                                                            @endif
                                                                                        @endfor
                                                                                    </tr>
                                                                                @endif
                                                                            @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                @endif
                                                            @break
                                                            @default
                                                            ..
                                                            @endswitch
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="row three_btn_margin">
                                        <div class="col-sm-12">
                                            <input type="submit" class="btn update_status_btn normal_btn text-white"
                                                   name="submit"
                                                   value="Save Only"/>
                                            <input type="submit" class="btn normal_btn save_and_submit text-white"
                                                   name="submit"
                                                   value="Save and Submit"/>
                                            @if(Auth::user()->role=="User")
                                                <a type="button" href="{{route('dashboard')}}" class="btn normal_btn cancel_modal_btn text-white">Cancel</a>
                                            @else
                                                <a type="button" href="{{route('dashboard.streams', [$stream->form_id])}}" class="btn normal_btn cancel_modal_btn text-white">Cancel</a>
                                            @endif

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

@section('scripts')
    <script>
        // $(".target").on('paste', function (event) {
        //     event.preventDefault();
        //     var counter_start = $(this).attr("id");
        //     console.log(counter_start);
        //     var pastedData = event.originalEvent.clipboardData.getData('text');
        //     console.log(pastedData);
        //     var myArr = pastedData.split("\r\n");
        //     myArr.forEach((value, key) => {
        //         $("#" + counter_start).val(value);
        //         counter_start++;
        //     });
        // });

        $(".new_target").on('paste', function (event) {

            event.preventDefault();
            var counter_second = $(this).attr("num");

            var new_pastedData = event.originalEvent.clipboardData.getData('text');

            var new_myArr = new_pastedData.split("\r\n");
            if(new_myArr.length ==1){
                new_myArr = new_pastedData.split(/\W+/);
            }
            console.log(new_myArr);
            var num_value =counter_second ;

            new_myArr.forEach((value, key) => {
                if(value !=""){
                    $("#current_value_"+ num_value +" ").val(value);
                    var value = parseInt($("#current_value_"+num_value).val());
                    var cumulative = parseInt($("#for_sum"+num_value).val());
                    var total = value+cumulative;
                    if(isNaN(total)){
                        $("#cumulative_"+num_value).val(cumulative);
                    }else{
                        $("#cumulative_"+num_value).val(total);
                    }
                    num_value = +num_value+10;
                }

            });
        });

        // set cumulative for table

        // set cumulative for table
        $(".editable_table_coloumn").focusout(function (e) {
            var number = $(this).attr("num");
            //var number_plus = parseInt($(this).attr("num"))+1;
            var value = parseInt($("#current_value_"+number).val());
            var cumulative = parseInt($("#for_sum"+number).val());
            var total = value+cumulative;

            if(isNaN(total)){
                $("#cumulative_"+number).val(cumulative);
            }else{
                $("#cumulative_"+number).val(total);
            }
        });

        // set cumulative for field
        $(".cumulative-class").focusout(function (e) {
            var number = $(this).attr("data-cumulative");
            var value = parseInt($("#field"+number).val());
            var cumulative_hidden = parseInt($("#cumulative_field_hidden"+number).val());
            var total = value+cumulative_hidden;

            if(isNaN(total)){
                $("#cumulative_field"+number).val(cumulative_hidden);
            }else{
                $("#cumulative_field"+number).val(total);
            }
        });
    </script>
@endsection
