$(document).ready(function(){
    //
    $(".project_upload_btn").click(function() {
        $(".file_upload_custom").click();
    })
    //
    $(".file_upload_input").click(function() {
        $(".file_upload_custom").click();
    })

    //

    $(".delete_modal").click(function(){
       $("#target_row").val($(this).attr('data-deleteMember'));
        $("#delete_modal").modal('show');
    })

    $(".delete_form_modal").click(function(){
        $("#target_row_form").val($(this).attr('data-deleteForm'));
        $("#form_delete_modal").modal('show');
    })
    $(".form_delete_modal_btn").click(function(){
        window.location.href=$("#target_row_form").val();
    })

    $(".delete_period_modal").click(function(){
        $("#target_row_period").val($(this).attr('data-deleteForm'));
        $("#period_delete_modal").modal('show');
    })
    $(".period_delete_modal_btn").click(function(){
        window.location.href=$("#target_row_period").val();
    })


    $(".del_modal_btn").click(function(){
        window.location.href=$("#target_row").val();
    })

    //
    $("#js_add_project_btn").click(function(){

    })

})


function AddProject(form_id){
    event.preventDefault();
    var $form = $("#" + form_id);
    var url = $form.attr('action');
    let profile_image = $("#project_image")[0].files[0];

    //Serialize the Form
    var values = {};
    $.each($("#" + form_id).serializeArray(), function (i, field) {
        values[field.name] = field.value;
       // values["project_image"]=profile_image;
    });

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        method: $form.attr("method"),
        url: url,
        data: values,
        contentType: false,
        processData: false,
        success: function(response) {
        console.log(response)
        }
    });


}
