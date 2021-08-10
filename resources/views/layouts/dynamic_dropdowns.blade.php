<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>

    // get forms
    $('select#project_id').change(function(){
        $(this).find("option:selected").each(function(){
            var selected_option = $(this).attr("value");
            var pre_selected_stream = $("#pre_selected_stream").val();
            var selected_form_id = $("#selected_form_id").val();
            var selected_form_name = $("#selected_form_name").val();

            if(selected_option){
                $.ajax({
                    type:"get",
                    url:"{{url('/get-forms')}}/"+selected_option,
                    success:function(response)
                    {
                        if(response)
                        {
                            if (pre_selected_stream){
                                $("#pre_selected_stream").val('');
                                $('#form_id').append('<option value="'+selected_form_id+'" selected>'+selected_form_name+'</option>');
                            }else {
                                $('#form_id').empty();
                                $('#form_id').append('<option value="">Select Form</option>');
                                $.each(response,function(key,value){
                                    $('#form_id').append('<option value="'+key+'">'+value+'</option>');
                                });
                            }
                        }
                    }
                });

                $.ajax({
                    type:"get",
                    url:"{{url('/get-users')}}/"+selected_option,
                    success:function(response)
                    {
                        if(response)
                        {
                            $('#all_users').html('');

                            var html = '';
                            const unassign_array = [];

                            $.each(response,function(key,value){
                                html += '<li class="list-group-item" data-draggable="item" draggable="true">'+
                                        '<input type="hidden" name="all_users[]" value="'+key+'"><span>'+value+'</span>'
                                    +'</li>';

                                unassign_array.push(key);
                            });

                            console.log(unassign_array);
                            $("#all_users").html(html)
                            $("#unassign_user").val(unassign_array)
                        }
                    }
                });
            }
        });
    }).change();

    // get streams
    $('select#form_id').change(function(){
        $(this).find("option:selected").each(function(){
            var selected_option = $(this).attr("value");
            var pre_selected_stream = $("#pre_selected_stream").val();
            /*var selected_stream_id = $("#selected_stream_id").val();
            var selected_stream_name = $("#selected_stream_name").val();*/

            if(selected_option){
                $.ajax({
                    type:"get",
                    url:"{{url('/get-streams')}}/"+selected_option,
                    success:function(response)
                    {
                        if(response)
                        {
                            if (pre_selected_stream){
                                $("#pre_selected_stream").val('');
                                /*$('#stream_id').append('<option value="'+selected_stream_id+'" selected>'+selected_stream_name+'</option>');*/
                            }else {
                                $('#stream_id').empty();
                                $('#stream_id').append('<option value="">Select Stream</option>');
                                $.each(response,function(key,value){
                                    $('#stream_id').append('<option value="'+key+'">'+value+'</option>');
                                });
                            }
                        }
                    }
                });
            }
        });
    }).change();
</script>
