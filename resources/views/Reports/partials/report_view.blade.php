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
                        ->orderBy('id', 'ASC')
                        ->get();

                    $current_period_start_date = \App\Models\Period::where('id', $form->period_id)->value('start_date');
                    $period_ids = \App\Models\Period::where('start_date', '<=', $current_period_start_date)->pluck('id')->toArray();
                    //dd($period_ids);

                    $cumulative_records = \App\Models\SpecialForm::whereIn('period_id', $period_ids)
                        ->where('project_id', $form->project_id)
                        ->select('period_id', 'project_id', 'vendor_id', 'user_id',
                            DB::raw('SUM(unique_visitors) as total_unique_visitors'),
                            DB::raw('SUM(two_or_more_users) as total_two_or_more_users'),
                            DB::raw('SUM(three_or_more_users) as total_three_or_more_users'),
                            DB::raw('SUM(forum_participants) as total_forum_participants'),
                            DB::raw('SUM(self_help_resources) as total_self_help_resources')
                            )
                        ->groupBy('user_id')
                        ->orderBy('id', 'ASC')
                        ->get();
                    //dd($cumulative_records)
                    @endphp
                    <div class="row">
                        <div class="col-sm-12 report_summary_col">
                            <b>Narratives:</b> </br>
                            @foreach($records as $record)
                                <span class="report_over_flow_fix">{!! $record->narrative !!}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12">
                        <div class="table-responsive">
                            <table class="table report_sub_table report_generated_table table-bordered">
                                <thead>
                                <tr class="red_row">
                                    <td></td>
                                    <td>Period</td>
                                    <td>Registrations</td>
                                    <td>Users Accessing 2X or more</td>
                                    <td>Users Accessing 3X or more</td>
                                    <td>Moderated Forum Participants</td>
                                    <td>Self-Help Resources Accessed</td>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $unique_visitors = 0;
                                    $two_or_more_users = 0;
                                    $three_or_more_users = 0;
                                    $forum_participants = 0;
                                    $self_help_resources = 0;
                                @endphp
                                @foreach($records as $record)
                                    @php
                                        $period_name = \App\Models\Period::where('id', $record->period_id)
                                            ->select(DB::raw("CONCAT(name,' (',DATE_FORMAT(start_date, '%d-%m-%Y'), ' - ', DATE_FORMAT(end_date, '%d-%m-%Y'), ')') as period_name"))
                                            ->first();

                                        $unique_visitors += $record->unique_visitors;
                                        $two_or_more_users += $record->two_or_more_users;
                                        $three_or_more_users += $record->three_or_more_users;
                                        $forum_participants += $record->forum_participants;
                                        $self_help_resources += $record->self_help_resources;
                                    @endphp
                                    <tr class="white_space">
                                        <td>{{\App\Models\Vendor::where('id', $record->vendor_id)->value('name')}}</td>
                                        <td>{{$period_name->period_name}}</td>
                                        <td>{{$record->unique_visitors}}</td>
                                        <td>{{$record->two_or_more_users}}</td>
                                        <td>{{$record->three_or_more_users}}</td>
                                        <td>{{$record->forum_participants}}</td>
                                        <td>{{$record->self_help_resources}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="2">Total</td>
                                    <td>{{$unique_visitors}}</td>
                                    <td>{{$two_or_more_users}}</td>
                                    <td>{{$three_or_more_users}}</td>
                                    <td>{{$forum_participants}}</td>
                                    <td>{{$self_help_resources}}</td>
                                </tr>
                                </tbody>
                            </table>
                            <a href="{{route('dashboard.reports.stream.static_csv_download', $form->id)}}" class="btn btn-primary fa-pull-right">CSV Download</a>
                        </div>
                    </div>
                    <br>

                    {{--cumulative table--}}
                    <div class="col-sm-12 col-md-12">
                        <div class="table-responsive">
                            <b>Cumulative Data</b>
                            <table class="table report_sub_table report_generated_table table-bordered">
                                <thead>
                                <tr class="red_row">
                                    <td></td>
                                    <td>Period</td>
                                    <td>Cumulative Registrations</td>
                                    <td>Cumulative Users Accessing 2X or more</td>
                                    <td>Cumulative Users Accessing 3X or more</td>
                                    <td>Cumulative Moderated Forum Participants</td>
                                    <td>Cumulative Self-Help Resources Accessed</td>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $total_unique_visitors = 0;
                                    $total_two_or_more_users = 0;
                                    $total_three_or_more_users = 0;
                                    $total_forum_participants = 0;
                                    $total_self_help_resources = 0;
                                @endphp
                                @foreach($cumulative_records as $cumulative_record)
                                    @php
                                        $period_name = \App\Models\Period::where('id', $cumulative_record->period_id)
                                            ->select(DB::raw("CONCAT(name,' (',DATE_FORMAT(start_date, '%d-%m-%Y'), ' - ', DATE_FORMAT(end_date, '%d-%m-%Y'), ')') as period_name"))
                                            ->first();

                                        $total_unique_visitors += $cumulative_record->total_unique_visitors;
                                        $total_two_or_more_users += $cumulative_record->total_two_or_more_users;
                                        $total_three_or_more_users += $cumulative_record->total_three_or_more_users;
                                        $total_forum_participants += $cumulative_record->total_forum_participants;
                                        $total_self_help_resources += $cumulative_record->total_self_help_resources;
                                    @endphp
                                    <tr class="white_space">
                                        <td>{{\App\Models\Vendor::where('id', $cumulative_record->vendor_id)->value('name')}}</td>
                                        <td>{{$period_name->period_name}}</td>
                                        <td>{{$cumulative_record->total_unique_visitors}}</td>
                                        <td>{{$cumulative_record->total_two_or_more_users}}</td>
                                        <td>{{$cumulative_record->total_three_or_more_users}}</td>
                                        <td>{{$cumulative_record->total_forum_participants}}</td>
                                        <td>{{$cumulative_record->total_self_help_resources}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="2">Total</td>
                                    <td>{{$total_unique_visitors}}</td>
                                    <td>{{$total_two_or_more_users}}</td>
                                    <td>{{$total_three_or_more_users}}</td>
                                    <td>{{$total_forum_participants}}</td>
                                    <td>{{$total_self_help_resources}}</td>
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
                                                                </tbody>
                                                            </table>
                                                            <a href="{{route('dashboard.reports.stream.csv_download', $field->id)}}" class="btn btn-primary fa-pull-right">CSV Download</a>
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
