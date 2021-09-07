@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="container">
                <div class="row blue-border-bottom">
                    <div class="col-sm-6 col-md-9 col-lg-10 px-0">
                        <div class="top-header pt-2 ">
                            <h3 class="margin-page-title">Dashboard</h3>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 col-lg-2">
                        <div class="top-header right_icon_text">
                            <b class=""><a class="add_icon" href="" data-toggle="modal"
                                           data-target="#exampleModal"><span><i
                                            class="fas fa-plus-circle"></i></span><span> Add Graph</span></a></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="table_div_padding">
                            <div class="card mb-0">
                                <div class="card_header">
                                    <div class=" li_dark_border">
                                        <div class="summary_text_center">
                                            <h5>Summary of Period</h5>

                                            <div class="summary_view_more ml-2">
                                                <form method="get" action="{{route('dashboard.forms')}}">
                                                    <input type="hidden" name="period_id" value="{{$period_id}}">
                                                    <button type="submit" class="btn update_status_btn text-white">View
                                                        More
                                                    </button>
                                                </form>
                                            </div>

                                        </div>
                                        <div class="cross_image">
                                            {{--<img class="cross_imgae_width" src="../assets/images/cross_new.png" />--}}
                                        </div>
                                    </div>
                                    <form method="get" action="">
                                        <div class="row new_row_adjusted li_dark_border">
                                            <div class="col-sm-12 sumary_select_list ">
                                                <div class="form-group  pt-1 d-flex ">
                                                    <select class="form-control form-select white_input" name="period_id" id="period_id" aria-label="Default select example">
                                                        @foreach($periods as $period)
                                                            <option
                                                                value="{{$period->id}}" {{$period_id == $period->id ? "selected" : ""}}>{{$period->name}}
                                                                ({{$period->start_date}} - {{$period->end_date}})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <button class="dashboard_span_search span_mid"><i
                                                            class="fas fa-search search_icon"></i></button>
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                                <div class="table-responsive summary_period_card">
                                    <table class="table  period_summary_table  table_margin_adj">
                                        <thead>
                                        <tr>
                                            <td> Form</td>
                                            <td> Status</td>
                                        </tr>
                                        </thead>
                                        <tbody class="summary_period_body">
                                        @forelse($forms as $form)
                                            <tr>
                                                <td><a class="form_anchor_text">{{$form->name}}</a></td>
                                                <td>
                                                    {{formStatus($form->id)}}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="text-center">
                                                <td colspan="2">No Data exist</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    @foreach($non_cumulative_graph as $graph_data)
                        <div class="col-sm-12 col-md-6 parent_col">
                            <div class="table_div_padding">
                                <div class="card mb-0">
                                    <div class="card_header grid_container li_dark_border">
                                        <div class=" row new_row_adjusted  text-center">
                                            <div class="col-sm-12">
                                                <h5 class="chart_heading">{{$graph_data['graph_info']['name']}}</h5>
                                            </div>
                                            <div class="col-sm-12">
                                                <p class="chart_sub_heading">
                                                    Duration: {{$graph_data['graph_info']['duration']}}</p>
                                            </div>
                                        </div>
                                        <div class="cross_image">
                                            <form action="{{route('dashboard.delete_graph')}}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$graph_data['graph_info']['graph_id']}}">
                                                <button type="submit" style="background: #fff; border: 0">
                                                    <img class="cross_imgae_width" src="{{asset('assets/images/cross_new.png')}}"/>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="row new_row_adjusted">
                                        <div class="col-sm-12">

                                            <div class="chart_padding">
                                                <p class="yaxis_chart_label">Value</p>
                                                <figure class="highcharts-figure">
                                                    <div id="container{{$loop->iteration}}"></div>
                                                </figure>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <script>
                            Highcharts.chart("container{{$loop->iteration}}", {
                                chart: {
                                    type: 'column'
                                },
                                exporting: {
                                    enabled: true
                                },
                                credits: {
                                    enabled: false
                                },

                                title: {
                                    text: ''
                                },
                                subtitle: {
                                    text: ''
                                },
                                /*xAxis: {
                                    categories: ['Period'],
                                    title: {
                                        text: "Period",
                                        color: "black",
                                    },
                                    crosshair: true
                                },*/
                                yAxis: {
                                    gridLineDashStyle: 'longdash',
                                    // min: 0,
                                    // title: {
                                    //     align: 'high',
                                    //     offset: 1,
                                    //     text: 'Value',
                                    //     rotation: 0,
                                    //     y: -5,
                                    // }
                                },
                                tooltip: {
                                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                        '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
                                    footerFormat: '</table>',
                                    shared: true,
                                    useHTML: true
                                },
                                plotOptions: {
                                    column: {
                                        pointPadding: 0,
                                        borderWidth: 0
                                    }
                                },
                                series: [
                                    @foreach($graph_data['data'] as $data)
                                        {
                                            name: "{{$data['row_name']}}",
                                            data: {{$data['row_values']}}
                                        },
                                    @endforeach
                                ]
                            });
                        </script>
                    @endforeach

                    @foreach($cumulative_graph as $graph_data)
                        <div class="col-sm-12 col-md-6 parent_col">
                            <div class="table_div_padding">
                                <div class="card mb-0">
                                    <div class="card_header grid_container li_dark_border">
                                        <div class=" row new_row_adjusted  text-center">
                                            <div class="col-sm-12">
                                                <h5 class="chart_heading">{{$graph_data['graph_info']['name']}}</h5>
                                            </div>
                                            <div class="col-sm-12">
                                                <p class="chart_sub_heading">
                                                    Duration: {{$graph_data['graph_info']['duration']}}</p>
                                            </div>
                                        </div>
                                        <div class="cross_image">
                                            <form action="{{route('dashboard.delete_graph')}}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$graph_data['graph_info']['graph_id']}}">
                                                <button type="submit" style="background: #fff; border: 0">
                                                    <img class="cross_imgae_width" src="{{asset('assets/images/cross_new.png')}}"/>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="row new_row_adjusted">
                                        <div class="col-sm-12">

                                            <div class="chart_padding">
                                                <p class="yaxis_chart_label">Value</p>
                                                <figure class="highcharts-figure">
                                                    <div id="area_container{{$loop->iteration}}"></div>
                                                </figure>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <script>
                            Highcharts.chart("area_container{{$loop->iteration}}", {
                                chart: {
                                    type: 'area'
                                },
                                exporting: {
                                    enabled: true
                                },
                                credits: {
                                    enabled: false
                                },

                                title: {
                                    text: ''
                                },
                                subtitle: {
                                    text: ''
                                },
                                /*xAxis: {
                                    categories: ['Period'],
                                    title: {
                                        text: "Period",
                                        color: "black",
                                    },
                                    crosshair: true
                                },*/
                                yAxis: {
                                    gridLineDashStyle: 'longdash',
                                    // min: 0,
                                    // title: {
                                    //     align: 'high',
                                    //     offset: 1,
                                    //     text: 'Value',
                                    //     rotation: 0,
                                    //     y: -5,
                                    // }
                                },
                                tooltip: {
                                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                        '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
                                    footerFormat: '</table>',
                                    shared: true,
                                    useHTML: true
                                },
                                plotOptions: {
                                    area: {
                                        marker: {
                                            enabled: true,
                                            symbol: 'circle',
                                            radius: 5,
                                            states: {
                                                hover: {
                                                    enabled: true
                                                }
                                            }
                                        }
                                    }
                                },
                                series: [
                                        @foreach($graph_data['data'] as $data)
                                    {
                                        name: "{{$data['row_name']}}",
                                        data: {{$data['row_values']}}
                                    },
                                    @endforeach
                                ]
                            });
                        </script>
                    @endforeach
                </div>
                @include('modals.create_graph')
            </div>
        </div>
    </div>
    <script>
        $(".cross_imgae_width").click(function () {
            $(this).closest("div.parent_col").hide();
        })

        function getForms(project_id) {

            var period_id = $('#start_period_id').val();
            $.ajax({
                url: '{{url("get-forms")}}/' + project_id+'/'+period_id,
                method: 'GET',
                success: function (data) {
                    $('#form_id').empty();
                    $('#form_id').append('<option value="">Select Form</option>');
                    $.each(data,function(key,value){
                        $('#form_id').append('<option value="'+key+'">'+value+'</option>');
                    });
                },
                error: function (error) {

                }
            });
        }

        function getStreams(id) {
            $.ajax({
                url: '{{url("dashboard/get-streams")}}/' + id,
                method: 'GET',
                success: function (data) {
                    data = data.data
                    let html = '<option>Select Stream</option>';
                    for (let i = 0; i < data.length; i++) {
                        html += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                    }
                    $('#add-streams').html(html);
                },
                error: function (error) {

                }
            });
        }

        function getFields(id) {
            $.ajax({
                url: '{{url("dashboard/get-fields")}}/' + id,
                method: 'GET',
                success: function (data) {
                    data = data.data
                    console.log('data', data)
                    let html = '<option>Select Field</option>';
                    for (let i = 0; i < data.length; i++) {
                        html += '<option value="' + data[i].id + '">' + data[i].fieldName + '</option>';
                    }
                    $('#add-field').html(html);
                },
                error: function (error) {

                }
            });
        }
    </script>
@endsection
