{{--Modal to add summary--}}
<div class="modal fade" id="addFormSummary{{$form->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Summary</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('dashboard.form.add_update_form_summary') }}"  enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$form->id}}">
                    <div class="row">
                        <div class="col-lg-12 col-xl-12 col-md-12 col-12">
                            <div class="mb-4">
                                <label for="summary" class="form-label">Summary *</label>
                                <textarea type="text" class="form-control ckeditor" id="summary" name="summary" required>{{$form->summary}}</textarea>
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
