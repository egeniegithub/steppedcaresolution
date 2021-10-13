{{--Modal to add summary--}}
<div style="color:black !important " class="modal fade" id="viewReport{{$form->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog stream_report_modal modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Report</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                @php
                $image_name = \App\Models\project::where('id', $form->project_id)->value('image');
                @endphp

                @if($image_name)
                    <div class="text-center">
                        <img src="{{asset('project_images')}}/{{$image_name}}" height="300px" width="500px" alt="No Img">
                    </div>
                @else
                    Project image not added
                @endif

                <div class="row">
                    <div class="col-sm-12 report_summary_col">
                         <b>Report Summary:</b> </br>
                          <span class="report_over_flow_fix">{!! $form->summary !!}</span>
                    </div>
                </div>

                @if($form->is_special == 1)
                    @php
                        $records = \App\Models\SpecialForm::where('period_id', $form->period_id)
                            ->where('project_id', $form->project_id)
                            ->get();
                    @endphp
                    <div class="col-sm-12 col-md-12">
                        <div class="table-responsive">
                            <table class="table report_sub_table report_generated_table table-bordered">
                                <thead>
                                <tr class="red_row">
                                    <td>Which organization you are reporting for ?</td>
                                    <td>What period you are reporting for ?</td>
                                    <td>How many participants did you have in human led / moderated forums ?</td>
                                    <td>How many total registrations / unique visitors did you have for the period ?</td>
                                    <td>How many users accessed the application two or more times ?</td>
                                    <td>How many users accessed the application three or more times ?</td>
                                    <td>How many times were resources downloaded from yur site or application ? (If applicable)</td>
                                    <td>How many times were self-help resources accessed on your site or application ? (If applicable)</td>
                                    <td>Please provide demographic data (gender, age and location) for the period and cumulative date.</td>
                                    <td>What was the user satisfaction score for the period ?</td>
                                    <td>Is there any outcomes data for the period that you would like included in the report ?</td>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $forum_participants = 0;
                                    $unique_visitors = 0;
                                    $two_or_more_users = 0;
                                    $three_or_more_users = 0;
                                    $downloaded_resources = 0;
                                    $self_help_resources = 0;
                                    $demographic_data = 0;
                                    $user_satisfaction = 0;
                                    $outcomes_data = 0;
                                @endphp
                                @foreach($records as $record)
                                    @php
                                        $period_name = \App\Models\Period::where('id', $record->period_id)
                                            ->select(DB::raw("CONCAT(name,' (',start_date, ' - ', end_date, ')') as period_name"))
                                            ->first();

                                        $forum_participants += $record->forum_participants;
                                        $unique_visitors += $record->unique_visitors;
                                        $two_or_more_users += $record->two_or_more_users;
                                        $three_or_more_users += $record->three_or_more_users;
                                        $downloaded_resources += $record->downloaded_resources;
                                        $self_help_resources += $record->self_help_resources;
                                        $demographic_data += $record->demographic_data;
                                        $user_satisfaction += $record->user_satisfaction;
                                        $outcomes_data += $record->outcomes_data;
                                    @endphp
                                    <tr class="white_space">
                                        <td>{{\App\Models\Vendor::where('id', $record->vendor_id)->value('name')}}</td>
                                        <td>{{$period_name->period_name}}</td>
                                        <td>{{$record->forum_participants}}</td>
                                        <td>{{$record->unique_visitors}}</td>
                                        <td>{{$record->two_or_more_users}}</td>
                                        <td>{{$record->three_or_more_users}}</td>
                                        <td>{{$record->downloaded_resources}}</td>
                                        <td>{{$record->self_help_resources}}</td>
                                        <td>{{$record->demographic_data}}</td>
                                        <td>{{$record->user_satisfaction}}</td>
                                        <td>{{$record->outcomes_data}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="2"></td>
                                    <td>{{number_format($forum_participants, 2)}}</td>
                                    <td>{{number_format($unique_visitors, 2)}}</td>
                                    <td>{{number_format($two_or_more_users, 2)}}</td>
                                    <td>{{number_format($three_or_more_users, 2)}}</td>
                                    <td>{{number_format($downloaded_resources, 2)}}</td>
                                    <td>{{number_format($self_help_resources, 2)}}</td>
                                    <td>{{number_format($demographic_data, 2)}}</td>
                                    <td>{{number_format($user_satisfaction, 2)}}</td>
                                    <td>{{number_format($outcomes_data, 2)}}</td>
                                </tr>
                                </tbody>
                            </table>
                            <a href="{{route('dashboard.reports.stream.static_csv_download', $form->id)}}" class="btn btn-primary fa-pull-right">CSV Download</a>
                        </div>
                    </div>
                @else
                    @foreach($form->streams as $stream)
                        <div class="row">
                            <div class="col-sm-12">
                                <p class="report_modal_dark_font">{{$stream->name}}</p>
                                <b>Form Summary:</b> </br>
                                <span class="report_over_flow_fix">{!! $stream->summary !!}</span>
                            </div>
                        </div>

                        @php
                            $stream_fields = \App\Models\StreamField::where('stream_id', $stream->id)->orderBy('orderCount', 'ASC')->get();
                        @endphp

                        @if(!empty($stream_fields))
                            @foreach($stream_fields as $field)
                                <div class="row" style="white-space:normal">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="exampleFormControlTextarea1"><b>{{$field->fieldName}}</b> : </label>
                                            @switch($field->fieldType)
                                                @case('text')
                                                <span>{{$field->value}}</span>
                                                @break

                                                @case('textarea')
                                                <p>{{$field->value ?? ''}}</p>
                                                @break

                                                @case('number')
                                                <span>{{$field->value ?? ''}}</span>
                                                <div class="row">
                                                    <div class="col-md-6">

                                                    </div>
                                                    <div class="col-md-6" style="margin-top: -30px">
                                                        @php
                                                            $previous_cumulative = \App\Models\StreamField::where('id', $field->previous_id)->value('cumulative_value');
                                                        @endphp

                                                        @if($field->isCumulative == 'yes')
                                                            <label  for="exampleFormControlTextarea1"><b>Cumulative Value</b></label>
                                                            <span>{{$field->cumulative_value ?? 0}}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @break

                                                @case('date')
                                                <span>{{date('d/m/Y', strtotime($field->value)) ?? ''}}</span>
                                                @break

                                                @case('file')
                                                <div class="text-center">
                                                    @if(isset($field->value))
                                                        <img src="{{asset('stream_answer_image')}}/{{$field->value}}" height="300px" width="500px" alt="No Img">
                                                    @endif
                                                </div>
                                                @break

                                                @case('select')
                                                <span>{{$field->value ?? ''}}</span>
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
                                                            <table class="table report_sub_table report_generated_table table-bordered">
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
                                                                                {{$table->name ?? ''}}
                                                                            </td>

                                                                            @if($check_cumulative == 'yes')
                                                                                <td>
                                                                                    {{$table->name ?? ''}} (Cumulative)
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
                                                                                        {{$value ? $value[$i] : ''}}
                                                                                    @else
                                                                                        {{$value ? $value[$i] : ''}}
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
                                                                                        {{$previous_cumulative_grid ? json_decode($previous_cumulative_grid)[$i] : ($table->cumulative_value ? json_decode($table->cumulative_value)[$i] : 0)}}
                                                                                    </td>
                                                                                @endif
                                                                            @endfor
                                                                        </tr>
                                                                    @endif
                                                                @endforeach
                                                                <tr>
                                                                    <td colspan="{{$column_count}}">
                                                                        <a href="{{route('dashboard.reports.stream.csv_download', $field->id)}}" class="btn btn-primary">CSV Download</a>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
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
                    @endforeach
                @endif

            </div>
            <div class="modal-footer">
                <div id="model-footer">
                    <div>
                        <a href="{{route('dashboard.reports.stream.pdf_download', $form->id)}}" class="btn btn-success">PDF Download</a>
                        <a href="{{route('dashboard.reports.stream.doc_download', $form->id)}}" class="btn btn-primary">DOC Download</a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
