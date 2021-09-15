{{--Modal for edit form--}}
<div class="modal fade" id="editFormModal{{$project->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Project</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('dashboard.project.update') }}"  enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$project->id}}">
                    <div class="row">
                        <div class="col-lg-12 col-xl-12 col-md-12 col-12">
                            <div class="mb-4">
                                <label for="name" class="form-label">Name *</label>
                                <input type="text" class="form-control" name="name" value="{{$project->name}}" required >
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                                <div class="mb-3">
                                    <div class="custom-file mb-3">
                                        <label>Image *</label><br>
                                        <label class="file">
                                            <input type="file" id="file" name="image" accept="image/png, image/jpg, image/JPG, image/jpeg" value="{{$project->image}}" aria-label="File browser example">
                                            <span class="file-custom"></span>
                                        </label>
                                        <p class="text-c-red">Image size should be less than 2MB</p>
                                    </div>
                                </div>
                                <br>
                            </div>
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
