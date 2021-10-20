@if(Auth::user()->role=="Admin" || Auth::user()->role=="Manager")
    @include('admin')
@elseif(Auth::user()->role=="Vendor")
    @include('vendor_user_dashboard')
@else
    @include('user_dashboard')
@endif
