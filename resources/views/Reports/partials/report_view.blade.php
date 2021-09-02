{{--Modal to add summary--}}
<div style="color:black !important " class="modal fade" id="viewReport{{$form->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Report</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="text-center">
                    <img src="{{asset('project_images')}}/{{\App\Models\project::where('id', $form->project_id)->value('image')}}" height="300px" width="500px" alt="No Img">
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <b>Report Summary:</b>
                        <p>{!! $form->summary !!}</p>
                    </div>
                </div>

                @foreach($form->streams as $stream)
                    <div class="row">
                        <div class="col-sm-12 ">
                            <p class="report_modal_dark_font">{{$stream->name}}</p>
                            <b>Stream Summary:</b>
                            <p>{!! $stream->summary !!}</p>
                        </div>
                    </div>

                    @php
                        $stream_fields = \App\Models\StreamField::where('stream_id', $stream->id)->orderBy('orderCount', 'ASC')->get();
                    @endphp

                    @if(!empty($stream_fields))
                        @foreach($stream_fields as $field)
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1"><b>{{$field->fieldName}}</b></label>
                                        @switch($field->fieldType)
                                            @case('text')
                                            <span>{{$field->value}}</span>
                                            @break

                                            @case('textarea')
                                            <p>{{$field->value}}</p>
                                            @break

                                            @case('number')
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span>{{$field->value}}</span>
                                                </div>
                                                <div class="col-md-6" style="margin-top: -30px">
                                                    @php
                                                        $previous_cumulative = \App\Models\StreamField::where('id', $field->previous_id)->value('cumulative_value');
                                                    @endphp

                                                    @if($field->isCumulative == 'yes')
                                                        <label  for="exampleFormControlTextarea1">Cumulative Value</label>
                                                        <span>{{$field->cumulative_value}}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            @break

                                            @case('date')
                                            <span>{{$field->value}}</span>
                                            @break

                                            @case('file')
                                            <div class="text-center">
                                                @if(isset($field->value))
                                                    <img src="{{asset('stream_answer_image')}}/{{$field->value}}" height="300px" width="500px" alt="No Img">
                                                @endif
                                            </div>
                                            @break

                                            @case('select')
                                            <span>{{$field->value}}</span>
                                            @break

                                            @case('table')
                                            @php
                                                $tableData = \App\Models\StreamFieldGrid::where('stream_field_id', $field->id)->orderBy('type', 'ASC')->orderBy('order_count', 'ASC')->get();
                                                $column_dropdown = array();
                                                $table_options = array();
                                            @endphp

                                            @if($tableData)
                                                <div class="col-sm-12 col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table report_sub_table table-bordered">
                                                            <thead>
                                                            <tr class="red_row">
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
                                                                        <td class="text-white">
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
                                                                                    {{$value[$i]}}
                                                                                @else
                                                                                    {{$value ? $value[$i] : null}}
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
                                                                                    {{$table->cumulative_value ? json_decode($table->cumulative_value)[$i] : 0}}
                                                                                </td>
                                                                            @endif
                                                                        @endfor
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            @endif
                                    </div>
                                    @break

                                    @default
                                    ..
                                    @endswitch
                                </div>
                            </div>
                        @endforeach
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
