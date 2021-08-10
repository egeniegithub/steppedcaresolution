{{--Modal to add summary--}}
<div class="modal fade" id="addStreamSummary{{$stream->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Summary</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('dashboard.form.add_update_stream_summary') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$stream->id}}">
                    <div class="row">
                        <div class="col-lg-12 col-xl-12 col-md-12 col-12">
                            <div class="mb-4">
                                <label for="summary" class="form-label">Summary *</label>
                                <textarea type="text" class="form-control" id="summary" name="summary" required>{{$stream->summary}}</textarea>
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
