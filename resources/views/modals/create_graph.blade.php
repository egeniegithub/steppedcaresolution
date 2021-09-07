<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <form action="{{route('dashboard.save_graph')}}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title text-center report_modal_header" id="exampleModalLabel">Add Graph</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <h5>Select Period first, before selecting project to get accurate result</h5>
                        <br>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Period *</label>
                                <select class="form-control white_input" id="start_period_id" name="start_period_id" required="required">
                                    <option value="">Select Period</option>
                                    @foreach($periods as $period)
                                        <option value="{{$period->id}}">{{$period->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Project *</label>
                                <select class="form-control white_input" id="exampleFormControlSelect1" onchange="getForms(this.value)" name="project_id" required>
                                    <option value="">Select Project</option>
                                    @foreach($projects as $project)
                                        <option value="{{$project->id}}">{{$project->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="add-forms">Form *</label>
                                <select class="form-control white_input" id="form_id" onchange="getStreams(this.value)" name="form_id" required>
                                    <option value="">Select Form</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="add-streams">Stream *</label>
                                <select class="form-control white_input" id="add-streams" onchange="getFields(this.value)" name="stream_id" required>
                                    <option value="">Select Stream</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="add-table">Field *</label>
                                <select class="form-control white_input" id="add-field" name="field_id" required>
                                    <option value="">Select Field</option>
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
