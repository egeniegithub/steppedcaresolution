@extends('layouts.app')

@section('title', 'List Stream')

@section('content')
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 px-0">
                        <div class="top-header pt-2 blue-border-bottom">
                            <h3 class="margin-page-title">Form: &nbsp;<u>{{$form->name}}</u></h3>
                        </div>
                    </div>
                </div>
                <div class="row blue-border-bottom">
                    <div class="col-sm-6 col-md-10 px-0">
                        <div class="top-header pt-2 ">
                            <h4 class="margin-page-title">Streams</h4>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-2 px-0">
                        <div class="top-header pt-2 add_icon_pad right_icon_text pr-4">
                            <b class=""> <a href="{{route('dashboard.stream.create', [$form->id])}}" style="color:#1B9C53 !important"> <span ><i class="fas fa-plus-circle"></i></span><span> Add Stream</span></a></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table_div_padding">
                            @include('layouts.flash-message')
                            <div class="card mb-0">
                                <div class="table-responsive">
                                    <table class="table  forms_stream_table  table_margin_adj" id="myTable">
                                        <thead>
                                        <tr>
                                            <td>Stream Name</td>
                                            <td>Form</td>
                                            <td>Project</td>
                                            <td>Status</td>
                                            <td>Actions</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($streams as $stream)
                                            <tr  >
                                                <td scope="row">{{$stream->stream_name}}</td>
                                                <td>{{$stream->form_name}}</td>
                                                <td>{{$stream->project_name}}</td>
                                                <td>{{$stream->stream_status}}</td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                        <a href="{{route('dashboard.stream.create', [$form->id, $stream->stream_id])}}" type="button" class="btn table_btn  update_btn text-white">Update</a>
                                                        <a href="{{route('dashboard.stream.delete', $stream->stream_id)}}" type="button" class="btn  table_btn delete_btn text-white">Delete</a>
                                                        <a type="button" class="btn table_btn permission_btn text-white" href="{{route('dashboard.permissions', [$stream->stream_id])}}" >Permissions</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="text-center">
                                                <td colspan="5">&nbsp; No Stream Added</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{--pagination will come here--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<script src="https://code.jquery.com/jquery-2.2.4.js"
                    integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
                    integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>

<script>
//    $(".sub_table").parents("tr").
  var fixHelperModified = function (e, tr) {
      
                        var $originals = tr.children();
                        var $helper = tr.clone();
                        $helper.children().each(function (index) {
                            $(this).width($originals.eq(index).width())
                        });
                        return $helper;
                    },
                    updateIndex = function (e, ui) {
                        $('td.index', ui.item.parent()).each(function (i) {
                            $(this).html(i + 1);
                        });
                        $('input[type=text]', ui.item.parent()).each(function (i) {
                            $(this).val(i + 1);
                        });
                    };
                $("#myTable tbody").sortable({
                    helper: fixHelperModified,
                    stop: updateIndex
                }).disableSelection();

                $("tbody").sortable(
                    {
                    distance: 5,
                    delay: 100,
                    opacity: 0.6,
                    cursor: 'move',
                    update: function () {
                    }
                });
</script>
@endsection