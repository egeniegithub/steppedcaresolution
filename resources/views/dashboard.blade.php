@if(Auth::user()->role=="Admin" || Auth::user()->role=="Manager")
    @include('admin')
@else
    @if(!empty(Auth::user()->vendor_id))
        @include('streams.static_form')
    @else
        @include('user_dashboard')
    @endif
@endif
