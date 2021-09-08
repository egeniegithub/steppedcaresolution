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
    }

    .report_sub_table .red_row td {
        color: white !important;
        text-align: center !important;
    }

    .red_row {
        background-color: #BD2127;
        color: white;
    }

    .text-center {
        text-align: center;
    }
</style>
<div class="">

    <div class="text-center">
        {{--<img src="{{base_path().'/public/project_images/'.\App\Models\project::where('id', $form->project_id)->value('image')}}" height="300px" width="500px" alt="No Img">--}}
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(base_path().'/public/project_images/'.\App\Models\project::where('id', $form->project_id)->value('image'))) }}" height="300px" width="500px" alt="No Img">
    </div>
    <div class="row">
        <div class="col-sm-12 report_summary_col">
             <b>Report Summary:</b> <br>
              <span class="report_over_flow_fix">{!! $form->summary !!}</span>
        </div>
    </div>

    @foreach($form->streams as $stream)
        <div class="row">
            <div class="col-sm-12">
                <p class="report_modal_dark_font">{{$stream->name}}</p>
                <b>Stream Summary:</b> <br>
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
                                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(base_path().'/public/stream_answer_image/'.$field->value)) }}" height="300px" width="500px" alt="No Img">
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
                                                                        {{$previous_cumulative_grid ? json_decode($previous_cumulative_grid)[$i] : 0}}
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
</div>
