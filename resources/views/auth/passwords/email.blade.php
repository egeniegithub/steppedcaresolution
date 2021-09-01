@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <div class="login-container">

                <div class="card" style="width: 65%">
                    <div class="card-body">
                        <form method="POST" action="{{ route('forget.password.post')}}">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12 text-center pr-0">
                                    <img src="../assets/images/logo_new.png" alt="" class="logo header_logo images" />
                                </div>
                            </div>
                            @include('layouts.flash-message')
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="email">{{ __('E-Mail Address') }}</label>
                                        <input type="email" class="form-control bg-color-login input_border @error('email') is-invalid @enderror" placeholder="hello@example.com" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus />
                                        @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="btn sign-in-btn text-white">
                                        <span>  {{ __('Send Password Reset Link') }}</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 bg-color-login image_container">
        <div class="login-container">
            <div class="login_image">
                <div class="img-div">
                    <img class="login_image_width" src="../assets/images/login.PNG" />
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

@endsection
