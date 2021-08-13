{{--Modal to add summary--}}
<div class="modal fade" id="changeStatus{{$stream->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('dashboard.form.update_status') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$stream->id}}">
                    <div class="row">
                        <div class="col-lg-12 col-xl-12 col-md-12 col-12">
                            <div class="mb-4">
                                <select class="form-control form-select" id="status" name="status" aria-label="Default select example" required>
                                    <option value="">Please Select</option>
                                    <option value="Draft" {{$stream->status == 'Draft' ? 'selected' : ''}}>Draft</option>
                                    <option value="In-progress" {{$stream->status == 'In-progress' ? 'selected' : ''}}>In-progress</option>
                                    <option value="Published" {{$stream->status == 'Published' ? 'selected' : ''}}>Published</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer project_modal_footer users_modal_footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button class="btn btn-light text-white" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
