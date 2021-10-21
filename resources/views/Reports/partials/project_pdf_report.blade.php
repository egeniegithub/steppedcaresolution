<style>
    .report_summary_col{
        margin-bottom: 15px;
    }
    .report_over_flow_fix{
        white-space: break-spaces;
    }

    .report_modal_dark_font {
        color: black;
        font-weight: bold;
        font-size: 18px;
        margin-bottom: 5px;
    }

    .report_sub_table {
        width: 100%;
        /* border: 2px solid black !important; */
    }

    .report_sub_table .red_row td {
        color: white !important;
        text-align: center !important;
    }

    .report_sub_table tbody tr td {
        color: black !important;
    }

    .report_sub_table thead tr td {
        text-align: center !important;
        font-weight: bold;
        color: black;
    }

    .report_sub_table td,
    .report_sub_table th {
        border: 2px solid black;
    }

    .report_sub_table td:nth-child(2) {
        text-align: left;
    }

    .report_sub_table td:nth-child(3) {
        text-align: left;
    }

    .report_generated_table{
        min-width: 50% !important;
    }
    .report_generated_table{
        table-layout: fixed !important;
    }
    .report_generated_table thead tr td{
        width: 20%;
        white-space: normal !important;
        font-family: "Mada", sans-serif;
        font-size: 14px;
    }
    .report_generated_table tbody tr td{
        border-bottom: 2px solid black !important;
    }

    .table-bordered td,
    .table-bordered th {
        /* border: 2px solid #e2e5e8; */
    }

    .table-bordered tbody tr td {
        color: #9a9a9a !important;
        font-size:10px !important;
    }
    .report_generated_table tbody tr td{
        font-size:12px !important;
    }
    .report_sub_table .red_row td {
        color: white !important;
        text-align: center !important;
        font-size:10px !important;
    }
    .red_row {
        background-color: #BD2127;
        color: white;
    }
    .text-center {
        text-align: center;
    }
    .all_pdf_borders {
        border: 2px solid black
    }
</style>
<div class="">

    <div class="text-center">
        {{--<img src="{{base_path().'/public/project_images/'.\App\Models\project::where('id', $form->project_id)->value('image')}}" height="300px" width="500px" alt="No Img">--}}
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(base_path().'/public/project_images/'.$project->image)) }}" style="height:600px;width:100%" alt="No Img">
    </div>

    @forelse($report_data as $form)
        <div class="row">
            <div class="col-sm-12 report_summary_col">
                <h3>Stream Summary:</h3> </br>
                <span class="report_over_flow_fix">{!! $form->summary !!}</span>
            </div>
        </div>

        @if($form->is_special == 1)
            @php
            $records = \App\Models\SpecialForm::where('period_id', $form->period_id)
                ->where('project_id', $form->project_id)
                ->get();

            $current_period_start_date = \App\Models\Period::where('id', $form->period_id)->value('start_date');
            $period_ids = \App\Models\Period::where('start_date', '<=', $current_period_start_date)->pluck('id')->toArray();

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
            @endphp
            <div class="row">
                <div class="col-sm-12 report_summary_col">
                    <b>Narratives:</b> </br>
                    @foreach($records as $record)
                        <p>{!! $record->narrative !!}</p>
                    @endforeach
                </div>
            </div>

            <div class="col-sm-12 col-md-12">
                <div class="table-responsive">
                    <table class="table report_sub_table report_generated_table" style="border-collapse: collapse">
                        <thead>
                        <tr class="red_row">
                            <td class="all_pdf_borders"></td>
                            <td class="all_pdf_borders">Period</td>
                            <td class="all_pdf_borders">Registrations</td>
                            <td class="all_pdf_borders">Users Accessing 2X or more</td>
                            <td class="all_pdf_borders">Users Accessing 3X or more</td>
                            <td class="all_pdf_borders">Moderated Forum Participants</td>
                            <td class="all_pdf_borders">Self-Help Resources Accessed</td>
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
                                <td class="all_pdf_borders">{{\App\Models\Vendor::where('id', $record->vendor_id)->value('name')}}</td>
                                <td class="all_pdf_borders">{{$period_name->period_name}}</td>
                                <td class="all_pdf_borders">{{$record->unique_visitors}}</td>
                                <td class="all_pdf_borders">{{$record->two_or_more_users}}</td>
                                <td class="all_pdf_borders">{{$record->three_or_more_users}}</td>
                                <td class="all_pdf_borders">{{$record->forum_participants}}</td>
                                <td class="all_pdf_borders">{{$record->self_help_resources}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="all_pdf_borders" colspan="2">Total</td>
                            <td class="all_pdf_borders">{{$unique_visitors}}</td>
                            <td class="all_pdf_borders">{{$two_or_more_users}}</td>
                            <td class="all_pdf_borders">{{$three_or_more_users}}</td>
                            <td class="all_pdf_borders">{{$forum_participants}}</td>
                            <td class="all_pdf_borders">{{$self_help_resources}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <br>

            {{--cumulative table--}}
            <div class="col-sm-12 col-md-12">
                <div class="table-responsive">
                    <b>Cumulative Data</b>
                    <table class="table report_sub_table report_generated_table" style="border-collapse: collapse">
                        <thead>
                        <tr class="red_row">
                            <td class="all_pdf_borders"></td>
                            <td class="all_pdf_borders">Period</td>
                            <td class="all_pdf_borders">Cumulative Registrations</td>
                            <td class="all_pdf_borders">Cumulative Users Accessing 2X or more</td>
                            <td class="all_pdf_borders">Cumulative Users Accessing 3X or more</td>
                            <td class="all_pdf_borders">Cumulative Moderated Forum Participants</td>
                            <td class="all_pdf_borders">Cumulative Self-Help Resources Accessed</td>
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
                                <td class="all_pdf_borders">{{\App\Models\Vendor::where('id', $cumulative_record->vendor_id)->value('name')}}</td>
                                <td class="all_pdf_borders">{{$period_name->period_name}}</td>
                                <td class="all_pdf_borders">{{$cumulative_record->total_unique_visitors}}</td>
                                <td class="all_pdf_borders">{{$cumulative_record->total_two_or_more_users}}</td>
                                <td class="all_pdf_borders">{{$cumulative_record->total_three_or_more_users}}</td>
                                <td class="all_pdf_borders">{{$cumulative_record->total_forum_participants}}</td>
                                <td class="all_pdf_borders">{{$cumulative_record->total_self_help_resources}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="all_pdf_borders" colspan="2">Total</td>
                            <td class="all_pdf_borders">{{$total_unique_visitors}}</td>
                            <td class="all_pdf_borders">{{$total_two_or_more_users}}</td>
                            <td class="all_pdf_borders">{{$total_three_or_more_users}}</td>
                            <td class="all_pdf_borders">{{$total_forum_participants}}</td>
                            <td class="all_pdf_borders">{{$total_self_help_resources}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            @foreach($form->streams as $stream)
                <div class="row">
                    <div class="col-sm-12">
                        <p class="report_modal_dark_font">{{$stream->name}}</p>
                        <b>Form Summary:</b> <br>
                        <span class="report_over_flow_fix">{!! $stream->summary !!}</span>
                    </div>
                </div>
                <br>

                @php
                    $stream_fields = \App\Models\StreamField::where('stream_id', $stream->id)->orderBy('orderCount', 'ASC')->get();
                @endphp

                @if(!empty($stream_fields))
                    @foreach($stream_fields as $field)
                        <div class="row" style="white-space:normal">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1"><b>{{$field->fieldName}}</b>: </label>
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
                                                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(base_path().'/public/stream_answer_image/'.$field->value)) }}" style="height:600px;width:100%" alt="No Img">
                                                {{--<img src="{{asset('stream_answer_image')}}/{{$field->value}}" height="300px" width="500px" alt="No Img">--}}
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
                                                    <table class="table report_sub_table report_generated_table" style="border-collapse: collapse">
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
                                                                        <td class="all_pdf_borders" style="font-size:10px"></td>
                                                                    @endif
                                                                    <td class="all_pdf_borders">
                                                                        {{$table->name ?? ''}}
                                                                    </td>

                                                                    @if($check_cumulative == 'yes')
                                                                        <td class="all_pdf_borders">
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
                                                                            <td class="all_pdf_borders"></td>
                                                                        @endfor
                                                                    </tr>
                                                                @endif
                                                                <tr>
                                                                    <td class="all_pdf_borders">{{$table->name}}</td>
                                                                    @for($i=0; $i<$column_count; $i++)
                                                                        <td class="all_pdf_borders">
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
                                                                            <td class="all_pdf_borders">
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
            <div>
                <hr>
            </div>
        @endif
    @empty
        <p class="text-c-red">&nbsp; No Stream Added</p>
    @endforelse
</div>
