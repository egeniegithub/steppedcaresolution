<!-- Delete Modal -->
<div class="modal fade" id="period_delete_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Warning</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p> Are you sure you want to delete ? All forms and streams will be deleted</p>
                <p> Remember this action will not be reversible .</p>
            </div>
            <input type="hidden" id="target_row_period">
            <div class="modal-footer users_modal_footer">
                <a type="button" class="btn btn-primary period_delete_modal_btn text-white" data-dismiss="modal">Delete</a>
                <button type="button" class="btn cancel_modal_btn text-white" data-dismiss="modal" aria-label="Close">Cancel</button>
            </div>
        </div>
    </div>
</div>
