{{--Modal for edit form--}}
<div class="modal fade" id="editFormModal{{$form->form_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('dashboard.form.update') }}"  enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$form->form_id}}">
                    <div class="row">
                        <div class="col-lg-12 col-xl-12 col-md-12 col-12">
                            <div class="mb-4">
                                <label for="newform" class="form-label">Create New Form *</label>
                                <input type="text" class="form-control" id="newform" name="name" value="{{$form->form_name}}" required placeholder="Month 1" aria-describedby="newform">
                            </div>
                            @if($active_user->role != 'Admin')
                                <input type="hidden" name="project_id" value="{{$active_user->project_id}}">
                            @else
                                <div class="mb-4">
                                    <label for="FormGroup" class="form-label">Select Project *</label>
                                    <select class="form-control form-select" name="project_id" aria-label="Default select example" required>
                                        <option value="">Select Project</option>
                                        @foreach($projects as $project)
                                            <option value="{{$project->id}}" {{$form->project_id == $project->id ? "selected" : ""}}>{{$project->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer project_modal_footer users_modal_footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button class="btn btn-light text-white" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
