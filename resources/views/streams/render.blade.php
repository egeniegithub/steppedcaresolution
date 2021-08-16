@extends('layouts.app')

@section('title', 'List Form')

@section('content')
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="container">
                <div class="row blue-border-bottom">
                    <div class="col-sm-6 col-md-4 col-lg-4 px-0 stream_update_title">
                        <div class="top-header pt-2">
                            <h3 class="margin-page-title">Streams 1.0</h3>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-4 px-0 update_stream_mid">
                        <div class="top-header pt-2">
                            <h3 class="margin-page-title">
                                status : <span class="blue_span">In Progress</span>
                            </h3>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-4 px-0">
                        <div class="top-header pt-2 update_stream_right_align">
                            <a class="btn update_status_btn text-white" href="{{route('dashboard')}}">Go to Stream
                                List</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table_div_padding">
                            <div class="card mb-0">
                                <div class="card_header">
                                    <h5 class="header_padding_adj">{{$stream->name}}</h5>
                                </div>
                                <form method="POST" action="{{ route('dashboard.stream.stream_post') }}"
                                      class="update_stream_form" enctype="multipart/form-data">
                                    @csrf

                                    <input type="hidden" name="stream_id" value="{{$stream->id}}">
                                    <input type="hidden" name="stream_answer_id" value="{{$stream_answer_id}}">

                                    @if($stream->getFields)
                                        @foreach($stream->getFields as $fieldKey => $field)


                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label
                                                            for="exampleFormControlTextarea1">{{$field->fieldName}} {{$field->isRequired == 'no' ? '' : "*"}}</label>
                                                        @php
                                                            $name = preg_replace('/\s+/', '_', strtolower($field->fieldName));
                                                            if (array_key_exists($name, $answer_array)){
                                                                $value = $answer_array[$name];
                                                            }else{
                                                                $value = '';
                                                            }
                                                            $required = $field->isRequired == 'no' ? '' : "required";
                                                        @endphp
                                                        @switch($field->fieldType)
                                                            @case('text')
                                                            <input type="text" class="form-control white_input"
                                                                   name="field[{{$field->id}}]"
                                                                   value="{{$values[$fieldKey]->value}}" {{$required}}>
                                                            @break

                                                            @case('textarea')
                                                            <textarea class="form-control white_input"
                                                                      name="field[{{$field->id}}]"
                                                                      {{$required}} rows="5">{{$values[$fieldKey]->value}}</textarea>
                                                            @break

                                                            @case('number')
                                                            <input type="number" class="form-control white_input"
                                                                   name="field[{{$field->id}}]"
                                                                   value="{{$values[$fieldKey]->value}}" {{$required}}>
                                                            @break

                                                            @case('date')
                                                            <input type="date" class="form-control white_input"
                                                                   name="field[{{$field->id}}]"
                                                                   value="{{$values[$fieldKey]->value}}" {{$required}}>
                                                            @break

                                                            @case('file')
                                                            <input type="file" class="form-control white_input" src=""
                                                                   alt="" name="image[{{$field->id}}]" {{$required}}>
                                                            <br>
                                                            @if (array_key_exists('image', $answer_array))
                                                                <div class="text-center">
                                                                    <img
                                                                        src="{{asset('stream_answer_image')}}/{{$answer_array['image']}}"
                                                                        height="300px" width="500px" alt="No Img">
                                                                </div>
                                                            @endif
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
                                                                        value="{{$option}}" {{($option == $value) ? 'selected' : ''}}>{{$option}}</option>
                                                                @endforeach
                                                            </select>
                                                            @break

                                                            @case('table')
                                                            @php
                                                                $tableData =json_decode(urldecode($field->tableData));
                                                            @endphp

                                                            {{--<pre>
                                                                {{print_r($tableData)}}
                                                            </pre>--}}

                                                            <h5 class="header_padding_adj no_margin_bottom">
                                                                {{$field->fieldName}}
                                                            </h5>
                                                            <div class="table-responsive">
                                                                <table
                                                                    class="table demographic_table platform_visitors table_margin_adj">
                                                                    <thead>
                                                                    <tr>
                                                                        @foreach($tableData as $table)
                                                                            @if($table->type == 'column')
                                                                                <td>
                                                                                    {{$table->fieldName}}
                                                                                    @if($table->tableDropdown == 'yes')
                                                                                        @php
                                                                                            $dropdowns = explode(',',$table->tableFieldOptions)
                                                                                        @endphp
                                                                                        <select name="" id="">
                                                                                            @foreach($dropdowns as $dropdown)
                                                                                                <option
                                                                                                    value="">{{$dropdown}}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    @endif
                                                                                </td>
                                                                            @endif
                                                                        @endforeach
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @foreach($tableData as $table)
                                                                        @if($table->type == 'row')
                                                                            <td>{{$table->fieldName}}</td>
                                                                        @endif
                                                                    @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                    </div>
                                                    @break

                                                    @default
                                                    ..
                                                    @endswitch
                                                </div>
                                            </div>
                            </div>
                            @endforeach
                            @endif

                            <div class="row three_btn_margin">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn update_status_btn normal_btn text-white"
                                            value="save">Save
                                        Only
                                    </button>
                                    <button type="submit" class="btn normal_btn save_and_submit text-white"
                                            value="submit">Save and
                                        Submit
                                    </button>
                                    <a type="button" href="{{route('dashboard')}}"
                                       class="btn normal_btn cancel_modal_btn text-white">Cancel</a>
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
