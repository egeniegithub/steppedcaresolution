@extends('layouts.app')

@section('title', 'Report')

@section('content')

    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="container">
                <div class="row blue-border-bottom">
                    <div class="col-sm-6 col-md-9 col-lg-10 px-0">
                        <div class="top-header pt-2 ">
                            <h3 class="margin-page-title">Report</h3>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 col-lg-2">
                        <div class="top-header right_icon_text">
                            <b class="">
                                <a class="add_icon" href="{{route('dashboard.period.create')}}"><span><i class="fas fa-plus-circle"></i></span><span> Add Periods</span></a>
                            </b>
                        </div>
                    </div>
                </div>
                <div class="row report_row_top blue-border-bottom">
                    <div class="col-xl-5 col-lg-5 col-md-6 col-12">
                        <div class="select_project_width">
                            <label for="Project" class="form-label">Select Period</label>
                            <select class="form-control form-select white_input" aria-label="Default select example">
                                <option selected> Month 1</option>
                                <option value="1">Active</option>
                                <option value="2">Disable</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-5 col-md-6 col-12 report_flex_row">
                        <div class=" select_project_width">
                            <label for="FormGroup" class="form-label">Select Project</label>
                            <select class="form-control form-select white_input" aria-label="Default select example">
                                <option selected>Child Health Care</option>
                                <option value="1">Active</option>
                                <option value="2">Disable</option>
                            </select>
                        </div>
                        <div class="span_search_div">
                            <button class="report_search_icon span_mid"><i class="fas fa-search "></i></button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="table_div_padding">

                            <div class="card mb-0">
                                <div class="table-responsive">
                                    <table class="table report_table table_margin_adj">
                                        <thead>
                                        <tr>
                                            <td class="forward_icon_td"></td>
                                            <td> Form</td>
                                            <td> Status</td>
                                            <td> Actions</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($form_streams as $form)
                                            <tr data-toggle="collapse" data-target="#accordion_{{$loop->iteration}}" class="clickable collapsed">
                                                <td><img class="forward_icon" src="{{asset('assets/images/forward_icon.PNG')}}"/></td>
                                                <td><a href="#" class="form_anchor_text">{{$form->name}}</a></td>
                                                <td>Completed</td>
                                                <td class="no-open">
                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                        <button type="button" class="btn update_status_btn table_btn text-white">Update Status</button>
                                                        <button type="button" data-toggle="modal" data-target="#addFormSummary{{$form->id}}" class="btn table_btn update_btn text-white">Add Summary</button>
                                                        @include('Reports.partials.add_form_summary')
                                                        <button type="button" class="btn  table_btn view_report_btn text-white" data-toggle="modal" data-target="#exampleModal">View Report</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:0px"></td>
                                                <td colspan="3" style="padding:0px  !important">
                                                    <div id="accordion_{{$loop->iteration}}" class="collapse">
                                                        <table class="table sub_table table_margin_adj">
                                                            <thead style="background-color: #EFEFEF;">
                                                            <tr>
                                                                <td style="font-weight: 700;color:black  !important;width:39%">Stream</td>
                                                                <td style="font-weight: 700;color:black !important">Status</td>
                                                                <td style="font-weight: 700;color:black !important">Actions</td>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @forelse($form->streams as $stream)
                                                                <tr>
                                                                    <td>{{$stream->name}}</td>
                                                                    <td>{{$stream->status}}</td>
                                                                    <td>
                                                                        <div class="btn-group" role="group" aria-label="Basic example">
                                                                            <button type="button" class="btn update_status_btn table_btn text-white">Update Status</button>
                                                                            <button type="button" data-toggle="modal" data-target="#addStreamSummary{{$stream->id}}" class="btn table_btn update_btn text-white">Add Summary</button>
                                                                            @include('Reports.partials.add_stream_summary')
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="3">No Stream Added</td>
                                                                </tr>
                                                            @endforelse
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">No Stream Added</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <h5></h5>
                                            </div>
                                        </div>
                                        <div class="modal-header">
                                            <h5 class="modal-title text-center report_modal_header" id="exampleModalLabel"> HC Summary Report</h5>
                                            <button type="button" class="close report_cross_btn" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-12 ">
                                                    <p class="report_modal_dark_font">STREAM 01</p>
                                                    <p class="report_modal_dark_font">Updated for deliverables</p>
                                                    <p class="report_modal_dark_font">Stream 1 - Virtual portal,triage resources and Self-Monitoring tools</p>
                                                    <p class="report_modal_dark_font">1.2: Stakeholder collaboration</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <p class="report_modal_light_font">1.3 Client interface/experience design and management</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <p class="report_modal_light_font">
                                                        A more detailed portal assessment and resource usage data is
                                                        currently being developed and harvested with the support of
                                                        Greenspace.</br>
                                                        SCS continues to collaborate with TDL in user engagement which
                                                        includes collecting, analyzing and implementing feedback from
                                                        people with lived </br>
                                                        experience.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <p class="report_modal_light_font">
                                                        SCS is in transition from one development vendor to another. In
                                                        this transition the new vendor is adapting the old vendor's
                                                        processes and work to their </br> worflow. They are also in the
                                                        process of hiring additional team members to supp As the new
                                                        development vendor is onbaording, our UI/UX vendor is </br>
                                                        completing various audits of the site to date. These include: an
                                                        SEO audit, landing page feature audit, and an audit of alignment
                                                        with the Stepped </br>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table report_sub_table table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <td></td>
                                                                <td>Reporting Period</td>
                                                                <td>Cumulative</td>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr class="red_row">
                                                                <td class="text-white">item</td>
                                                                <td class="text-white">Apr 15 - May 14,2021</td>
                                                                <td class="text-white">Apr 15,2020 - May </br>14,2021</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Unique Site Visitors</td>
                                                                <td>148,125</td>
                                                                <td>1,336,831</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Percentage of users who </br> completed a self assessment</br>at sign-up</td>
                                                                <td>6,401</td>
                                                                <td>124,266</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Percentage of users who </br> accessed at least one</td>
                                                                <td>6,401</td>
                                                                <td>124,266</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-primary">Download</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class=" flex-columns flex-setting mob_margin_pagination">
                                <form>
                                    <div class="inline_block_adj show_rows_adj">
                                        <label class="my-1 mr-2" for="inlineFormCustomSelectPref">Show Rows :</label>
                                        <select name="" class="my-1 show_rows_count" id="show_rows" onchange="get_per_page()">
                                        </select>
                                    </div>
                                </form>
                                <div class="show_rows_adj margin_top">
                                    {{$form_streams->links('components.pagination')}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.pagination')

    <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
    <script>
        var table = $('.report_table');
        $(".clickable").click(function () {
            setTimeout(() => {
                if ($(this).hasClass('collapsed')) {
                    $(this).find(".forward_icon").css("transform", "rotate(0deg)");
                } else {
                    $(this).find(".forward_icon").css("transform", "rotate(90deg)");
                }
            }, 100);
        })
        $('.report_table tbody tr.clickable').each(function(){
            if( $(this).next('tr').length != 0)
            {
                $(this).find('td').css('border-bottom','0');
            }
        });

        /*table.find(".no-open").click(function(e) {
            e.stopPropagation();
            return false;
        })*/
    </script>
@endsection
