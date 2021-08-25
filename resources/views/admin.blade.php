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
                                                <select class="form-control form-select white_input" name="period_id"
                                                        aria-label="Default select example">
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
                    @foreach($graphs as $graph)
                        @if($graph->is_cumulative == 1)
                            <div class="col-sm-12 col-md-6 parent_col">
                                <div class="table_div_padding">
                                    <div class="card mb-0">
                                        <div class="card_header grid_container li_dark_border">
                                            <div class="row new_row_adjusted  text-center">
                                                <div class="col-sm-12">
                                                    <h5 class="chart_heading">HC Reports - {{$graph->stream->name}}</h5>
                                                </div>
                                                <div class="col-sm-12">
                                                    <p class="chart_sub_heading">Duration: {{$graph->period->start_date}} to {{$graph->period->end_date}}</p>
                                                </div>
                                            </div>
                                            <div class="cross_image">
                                                <form action="{{route('dashboard.delete_graph')}}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$graph->id}}">
                                                    <button type="submit" style="background: #fff; border: 0"><img class="cross_imgae_width" src="../assets/images/cross_new.png"/></button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="row new_row_adjusted">
                                            <div class="col-sm-12">
                                                <div class="chart_padding">
                                                    <p class="yaxis_chart_label">Value</p>
                                                    <figure class="highcharts-figure">
                                                        <div id="area_container"></div>
                                                    </figure>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @else

                            <div class="col-sm-12 col-md-6 parent_col">
                                <div class="table_div_padding">
                                    <div class="card mb-0">
                                        <div class="card_header grid_container li_dark_border">
                                            <div class=" row new_row_adjusted  text-center">
                                                <div class="col-sm-12">
                                                    <h5 class="chart_heading">HC Reports - {{$graph->stream->name}}</h5>
                                                </div>
                                                <div class="col-sm-12">
                                                    <p class="chart_sub_heading">Duration: {{$graph->period->start_date}} to {{$graph->period->end_date}}</p>
                                                </div>
                                            </div>
                                            <div class="cross_image">
                                                <form action="{{route('dashboard.delete_graph')}}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$graph->id}}">
                                                    <button type="submit" style="background: #fff; border: 0"><img class="cross_imgae_width" src="../assets/images/cross_new.png"/></button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="row new_row_adjusted">
                                            <div class="col-sm-12">

                                                <div class="chart_padding">
                                                    <p class="yaxis_chart_label">Value</p>
                                                    <figure class="highcharts-figure">
                                                        <div id="container"></div>
                                                    </figure>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            @endif
                    @endforeach

                    {{--<div class="col-sm-12 col-md-6 parent_col">
                        <div class="table_div_padding">
                            <div class="card mb-0">
                                <div class="card_header grid_container li_dark_border">
                                    <div class="row new_row_adjusted  text-center">
                                        <div class="col-sm-12">
                                            <h5 class="chart_heading">HC Reports - Stream 1.0 - Device</h5>
                                        </div>
                                        <div class="col-sm-12">
                                            <p class="chart_sub_heading">Duration: Mar 21 to Aug 21 </p>
                                        </div>
                                    </div>
                                    <div class="cross_image">
                                        <img class="cross_imgae_width" src="../assets/images/cross_new.png" />
                                    </div>
                                </div>
                                <div class="row new_row_adjusted">
                                    <div class="col-sm-12">
                                        <div class="chart_padding">
                                            <p class="yaxis_chart_label">Value</p>
                                            <figure class="highcharts-figure">
                                                <div id="second_coloumn_chart"></div>
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>--}}

                </div>
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content ">
                            <form action="{{route('dashboard.save_graph')}}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title text-center report_modal_header" id="exampleModalLabel">Add
                                        Graph</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="exampleFormControlSelect1">Period</label>
                                                <select class="form-control white_input" id="exampleFormControlSelect1"
                                                        name="start_period_id">
                                                    <option>Select Period</option>
                                                    @foreach($periods as $period)
                                                        <option value="{{$period->id}}">{{$period->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        {{--<div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="exampleFormControlSelect1">Ending Period</label>
                                                <select class="form-control white_input" id="exampleFormControlSelect1">
                                                    <option>Month 2</option>
                                                    <option>2</option>
                                                    <option>3</option>
                                                    <option>4</option>
                                                    <option>5</option>
                                                </select>
                                            </div>
                                        </div>--}}
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="exampleFormControlSelect1">Project</label>
                                                <select class="form-control white_input" id="exampleFormControlSelect1"
                                                        onchange="getForms(this.value)" name="project_id">
                                                    <option>Select Project</option>
                                                    @foreach($projects as $project)
                                                        <option value="{{$project->id}}">{{$project->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="add-forms">Form</label>
                                                <select class="form-control white_input" id="add-forms"
                                                        onchange="getStreams(this.value)" name="form_id">
                                                    <option>Select Form</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="add-streams">Stream</label>
                                                <select class="form-control white_input" id="add-streams"
                                                        onchange="getFields(this.value)" name="stream_id">
                                                    <option>Select Stream</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="add-table">Field</label>
                                                <select class="form-control white_input" id="add-field" name="field_id">
                                                    <option>Select Field</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <table class="radio_table" style="width:75%">
                                                <tr>
                                                    <th> Cumulative Value</th>
                                                    <td>
                                                        <label class="radio_container">
                                                            <input type="radio" checked="checked" name="is_cumulative"
                                                                   value="1">
                                                            <span class="checkmark"></span>
                                                            Yes
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label class="radio_container">No
                                                            <input type="radio" checked="checked" name="is_cumulative"
                                                                   value="0">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <script src="../../assets/js/highchart.js"></script>
    <!-- <script src="https://code.highcharts.com/highcharts.js"></script> -->
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script>

        Highcharts.chart('container', {
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
            xAxis: {
                categories: [
                    'Mar 21',
                    'Apr 21',
                    'May 21',
                    'Jun 21',
                    'Jul 21',
                    'Aug 21',
                ],
                title: {
                    text: "Period",
                    color: "black",
                },
                crosshair: true
            },
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
            series: [{
                name: 'Registered Users',
                color: "#4472C4",
                data: [40, 37, 42, 20, 45, 28]

            }, {
                name: 'Unique Visitors',
                color: "#ED7D31",
                data: [18, 12, 38, 28, 5, 12]

            }]
        });
    </script>
    <script>
        Highcharts.chart('second_coloumn_chart', {
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
            xAxis: {
                categories: [
                    'Mar 21',
                    'Apr 21',
                    'May 21',
                    'Jun 21',
                    'Jul 21',
                    'Aug 21',
                ],
                title: {
                    text: "Period",
                    color: "black",
                },
                crosshair: true
            },
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
            series: [{
                name: 'Mobile',
                color: "#44C477",
                data: [40, 37, 42, 20, 45, 28]

            }, {
                name: 'Computer',
                color: "#4A90CB",
                data: [18, 12, 38, 28, 5, 12]

            }]
        });
    </script>
    <script>
        Highcharts.chart('area_container', {
            chart: {
                type: 'area'
            },
            accessibility: {},
            credits: {
                enabled: false,
            },
            exporting: {
                enabled: true,
            },
            title: {
                // text: 'US and USSR nuclear stockpiles'
            },
            subtitle: {
                // text: 'Sources: <a href="https://thebulletin.org/2006/july/global-nuclear-stockpiles-1945-2006">' +
                //     'thebulletin.org</a> &amp; <a href="https://www.armscontrol.org/factsheets/Nuclearweaponswhohaswhat">' +
                //     'armscontrol.org</a>'
            },
            xAxis: {
                title: {
                    text: "Period",
                    color: "black",
                },
                categories: [
                    'Mar 21',
                    'Apr 21',
                    'May 21',
                    'Jun 21',
                    'Jul 21',
                    'Aug 21',
                ],
                allowDecimals: false,
                // labels: {
                // formatter: function() {
                //     return this.value; // clean, unformatted number for year
                // }
                // },
                accessibility: {
                    // rangeDescription: 'Range: 1940 to 2017.'
                }
            },
            yAxis: {
                gridLineDashStyle: 'longdash',
                // title: {
                // text: 'Nuclear weapon states'
                // },
                // labels: {
                //     formatter: function() {
                //         return this.value / 1000 + 'k';
                //     }
                // }
            },
            tooltip: {
                // pointFormat: '{series.name} had stockpiled <b>{point.y:,.0f}</b><br/>warheads in {point.x}'
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
            series: [{
                name: 'Registered Users',
                color: "#BF1F2B",
                data: [2.1, 1, 2, 3, 3.4, 4.6],
            }, {
                name: 'Unique Visitors',
                color: "#4A90CB",
                data: [1.6, 0.2, 1, 2.2, 2.8, 4],
            }]
        });

        $(".cross_imgae_width").click(function () {
            $(this).closest("div.parent_col").hide();
        })

        function getForms(id) {
            $.ajax({
                url: '{{url("dashboard/get-forms")}}/' + id,
                method: 'GET',
                success: function (data) {
                    data = data.data
                    let html = '<option>Select Form</option>';
                    for (let i = 0; i < data.length; i++) {
                        html += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                    }
                    $('#add-forms').html(html);
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
