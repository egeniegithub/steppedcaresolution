@include('modals.index')
<script src="{{asset('assets/js/vendor-all.min.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/js/pcoded.min.js')}}"></script>
<script src="{{asset('assets/js/main.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>

    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    function common_helper_split_query_string() {
        var vars = [], hash;
        var q = document.URL.split('?')[1];
        if (q != undefined) {
            q = q.split('&');
            for (var i = 0; i < q.length; i++) {
                hash = q[i].split('=');
                // vars.push(hash[1]);
                vars[hash[0]] = hash[1];
            }
        }
        return vars;
    }

    function get_per_page() {
        var e = document.getElementById("show_rows");
        var value = e.options[e.selectedIndex].value;
        var interest = $('ul').find('li.active').children().attr('href');
        var url = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for (var i = 0; i < url.length; i++) {
            var urlparam = url[i].split('=');
            if (urlparam[0] == interest) {
                return urlparam[1];
            }
        }
        const vars = common_helper_split_query_string();
        window.location.href = interest + "?page=" + vars.page + "&show_rows=" + value;
    }

    $(".page-link").click(function () {
        window.location.href = $(this).attr("data-link") + "&show_rows=" + $("#show_rows option:selected").val();

    })
    $(".header_icon_image").click(function () {
        $(".pcoded-navbar").toggleClass("navbar-collapsed");
    })


</script>





