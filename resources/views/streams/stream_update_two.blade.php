@extends('layouts.app')

@section('title', 'List Form')

@section('content')
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="container">
                <div class="row blue-border-bottom">
                    <div class="col-sm-6 col-md-4 col-lg-4 px-0 stream_update_title">
                        <div class="top-header pt-2">
                            <h3 class="margin-page-title">Streams 1.1</h3>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-4 px-0 update_stream_mid">
                        <div class="top-header pt-2">
                            <h3 class="margin-page-title">
                                status : <span class="completed-text-color">Completed</span>
                            </h3>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-4 px-0">
                        <div class="top-header pt-2 update_stream_right_align">
                            <button class="btn update_status_btn text-white"
                                    onclick="location.href = '../default/user-stream.html'">
                                Go to Stream List
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="table_div_padding">
                            <div class="card mb-0">
                                <div class="card_header">
                                    <h5 class="header_padding_adj">Data</h5>
                                    <h5 class="header_padding_adj no_margin_bottom">
                                        Visitor Demographics
                                    </h5>
                                </div>
                                <div class="table-responsive">
                                    <table class="table demographic_table table_margin_adj">
                                        <thead>
                                        <tr>
                                            <td>Name</td>
                                            <td>Reporting Period</td>
                                            <td>Cumulative Value</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>10 to 18</td>
                                            <td class="editable_td"><input
                                                    class="form-control editable_table_coloumn target" id="0"
                                                    type="text" value="15%"></td>
                                            <td>9%</td>
                                        </tr>
                                        <tr>
                                            <td>19 to 29</td>
                                            <td class="editable_td"><input
                                                    class="form-control editable_table_coloumn target" id="1"
                                                    type="text" value="36%"></td>
                                            <td>32%</td>
                                        </tr>
                                        <tr>
                                            <td>30 to 39</td>
                                            <td class="editable_td"><input
                                                    class="form-control editable_table_coloumn target" id="2"
                                                    type="text" value="21%"></td>
                                            <td>24%</td>
                                        </tr>
                                        <tr>
                                            <td>40 to 49</td>
                                            <td class="editable_td"><input
                                                    class="form-control editable_table_coloumn target" id="3"
                                                    type="text" value="12%"></td>
                                            <td>16%</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <h5 class="header_padding_adj no_margin_bottom">
                                    Platform Visitors by Province
                                </h5>
                                <div class="table-responsive">
                                    <table class="
                          table
                          demographic_table
                          platform_visitors
                          table_margin_adj
                        ">
                                        <thead>
                                        <tr>
                                            <td>Name</td>
                                            <td>Reporting Period</td>
                                            <td>Cumulative Value</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Ontario</td>
                                            <td class="editable_td "><input
                                                    class="form-control editable_table_coloumn target_0 new_target"
                                                    num="0" type="text" value="39.6%"></td>
                                            <td>41.4%</td>
                                        </tr>
                                        <tr>
                                            <td>Quebec
                                            </td>
                                            <td class="editable_td"><input
                                                    class="form-control editable_table_coloumn target_1 new_target"
                                                    num="1" type="text" value="22.4%"></td>
                                            <td>21.6%</td>
                                        </tr>
                                        <tr>
                                            <td>British Columbia
                                            </td>
                                            <td class="editable_td"><input
                                                    class="form-control editable_table_coloumn target_2 new_target"
                                                    num="2" type="text" value="12.6%"></td>
                                            <td>12.5%</td>
                                        </tr>
                                        <tr>
                                            <td>Alberta
                                            </td>
                                            <td class="editable_td"><input
                                                    class="form-control editable_table_coloumn target_3 new_target"
                                                    num="3" type="text" value="11.4%"></td>
                                            <td>11.6%</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row three_btn_margin">
                                <div class="col-sm-12">
                                    <button type="button" class="btn update_status_btn normal_btn text-white">
                                        Save Only
                                    </button>
                                    <button type="button" class="btn normal_btn save_and_submit text-white">
                                        Save and Submit
                                    </button>
                                    <button type="button" class="btn normal_btn cancel_modal_btn text-white"
                                            data-toggle="modal" data-target="#exampleModal">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>

        // const target = document.querySelector('.target');
        // target.addEventListener('paste', (event) => {
        //   event.preventDefault();
        //   let paste = (event.clipboardData || window.clipboardData).getData('text');
        //   console.log(paste);
        //   var myArr = paste.split("\r\n");
        //   console.log(myArr.length);
        //   myArr.forEach((value,key)=>{
        //     $("#"+key).val(value);
        //   });
        // });


        $(".target").on('paste', function (event) {
            event.preventDefault();
            var counter_start = $(this).attr("id");
            console.log(counter_start);
            var pastedData = event.originalEvent.clipboardData.getData('text');
            console.log(pastedData);
            var myArr = pastedData.split("\r\n");
            myArr.forEach((value, key) => {
                $("#" + counter_start).val(value);
                counter_start++;
            });
        });

        $(".new_target").on('paste', function (event) {
            event.preventDefault();
            var counter_second = $(this).attr("num");
            console.log(counter_second);
            var new_pastedData = event.originalEvent.clipboardData.getData('text');
            console.log(new_pastedData);
            var new_myArr = new_pastedData.split("\r\n");
            new_myArr.forEach((value, key) => {
                // $("num"+counter_second).val(value);
                $(".target_" + counter_second).val(value);
                counter_second++;
            });
        });


    </script>

    @include('layouts.pagination')
@endsection
