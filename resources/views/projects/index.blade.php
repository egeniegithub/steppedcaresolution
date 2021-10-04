@extends('layouts.app')

@section('title', 'List Project')

@section('content')

    <style>

        .file {
            position: relative;
            display: inline-block;
            cursor: pointer;
            height: 2.5rem;
        }
        .file input {
            min-width: 25rem;
            margin: 0;
            filter: alpha(opacity=0);
            opacity: 0;
        }
        .file-custom {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            z-index: 5;
            height: 2.5rem;
            padding: .5rem 1rem;
            line-height: 1.5;
            color: #555;
            background-color: #fff;
            border: .075rem solid #ddd;
            border-radius: .25rem;
            box-shadow: inset 0 .2rem .4rem rgba(0,0,0,.05);
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        .file-custom:after {
            content: "Choose image...";
        }
        .file-custom:before {
            position: absolute;
            top: -.075rem;
            right: -.075rem;
            bottom: -.075rem;
            z-index: 6;
            display: block;
            content: "Browse";
            height: 2.5rem;
            padding: .5rem 1rem;
            line-height: 1.5;
            color: #555;
            background-color: #eee;
            border: .075rem solid #ddd;
            border-radius: 0 .25rem .25rem 0;
        }

        /* Focus */
        .file input:focus ~ .file-custom {
            box-shadow: 0 0 0 .075rem #fff, 0 0 0 .2rem #0074d9;
        }

    </style>

    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 px-0">
                        <div class="top-header pt-2 blue-border-bottom">
                            <h3 class="margin-page-title">Projects</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table_div_padding">
                            @include('layouts.flash-message')
                            <div class="card pt-3">
                                <form method="POST" action="{{ route('dashboard.project.store') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="container">
                                        <h4>Create Project</h4>
                                        <div class="row report_row_top">
                                            <div class="col-xl-5 col-lg-5 col-md-5 col-12">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Name *</label>
                                                    <input type="text" class="form-control" id="new_project" name="name" value="{{ old('name') }}" required placeholder="Month 1" aria-describedby="new_project">
                                                </div>
                                            </div>
                                            <div class="col-xl-5 col-lg-5 col-md-5 col-12">
                                                <div class="mb-3">
                                                    <div class="custom-file mb-3">
                                                        <label>Image *</label><br>
                                                        <label class="file">
                                                            <input class="file-upload" type="file" id="file" accept="image/png, image/jpg, image/JPG, image/jpeg" name="image" aria-label="File browser example">
                                                            <span class="file-custom"></span>
                                                        </label>
                                                        <p class="text-c-red">Image size should be less than 2MB (Max dimensions, height: 3500px - width: 2500px)</p>
                                                        {{--<div class="row">
                                                            <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                                                                <input class="file_upload_custom file-upload" name="image" type="file">
                                                            </div>
                                                        </div>--}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-2 col-lg-2 col-md-2 col-12">
                                                <label for="newform" class="form-label hide-on-mobile" style="visibility: hidden;display: block;">Create New Form</label>
                                                <button class="btn btn-primary">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card pt-3">
                                <form method="get" action="">
                                    <div class="container">
                                        <h4>Search Project</h4>
                                        <div class="row report_row_top ">
                                            <div class="col-xl-5 col-lg-5 col-md-6 col-12">
                                                <div>
                                                    <label for="Project" class="form-label">Search</label>
                                                    <input type="text" class="form-control" id="keyword" name="keyword" placeholder="Search Here" value="{{request()->get('keyword')}}">
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-lg-2 col-md-2 col-12 pl-0 report_flex_row">
                                                <div class="span_search_div">
                                                    <button class="report_search_icon span_mid"><i class="fas fa-search "></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card mb-0">
                                <div class="table-responsive">
                                    <table class="table   table_margin_adj">
                                        <thead>
                                            <tr>
                                                <td style="width: 10%">No</td>
                                                <td>Name</td>
                                                <td>image</td>
                                                <td>Actions</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($projects as $project)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$project->name}}</td>
                                                <td><img src="{{asset('project_images')}}/{{$project->image}}" height="50px" width="50px" alt=""></td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                        <button data-toggle="modal" data-target="#editFormModal{{$project->id}}" class="btn table_btn update_btn text-white">Update</button>
                                                        <button type="button" class="btn table_btn delete_btn text-white delete_project_modal" data-toggle="modal" data-deleteProject="{{route('dashboard.project.delete')}}{{'?ref='.encrypt($project->id)}}">Delete</button>
                                                    </div>
                                                    @include('projects.partials.update_project_modal')
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No form added</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Delete Form Modal--}}
                            @include('projects.partials.delete_modal')

                            <div class=" flex-columns flex-setting mob_margin_pagination">
                                <form>
                                    <div class="inline_block_adj show_rows_adj">
                                        <label class="my-1 mr-2" for="inlineFormCustomSelectPref">Show Rows :</label>
                                        <select name="" class="my-1 show_rows_count" id="show_rows" onchange="get_per_page()">
                                        </select>
                                    </div>
                                </form>
                                <div class="show_rows_adj margin_top">
                                    {{$projects->links('components.pagination')}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.pagination')

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function(){
            $('input[type="file"]').change(function(e){
                var fileName = e.target.files[0].name;
                $(".file-custom").html(fileName)
                //alert('The file "' + fileName +  '" has been selected.');
            });
        });
    </script>

    <script>
        $(".file-upload").change(function() {
            var i = $(this).prev('label').clone();
            var file = $(".file-upload")[0].files[0].name;
            $(this).prev('label').text(file);
        });
    </script>
@endsection
