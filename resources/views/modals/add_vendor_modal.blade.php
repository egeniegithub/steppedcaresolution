<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Vendor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('dashboard.vendor.store') }}"  enctype="multipart/form-data" id="js_add_vendor">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 col-xl-12 col-md-12 col-12">
                            <div class="mb-4">
                                <label for="firstname" class="form-label"> Name *</label>
                                <input type="text" class="form-control" id="vendor_name" name="name" aria-describedby="project_name">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer project_modal_footer users_modal_footer">
                <button type="button" class="btn btn-primary" onclick="createVendor('js_add_vendor')">Add</button>
                <button class="btn btn-light text-white" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
