@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <div class="login-container">
                <div class="card" style="width: 65%">
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12 text-center pr-0">
                                    <img src="../assets/images/logo_new.png" alt="" class="logo header_logo images" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="email">{{ __('User Name')}}</label>
                                        <input type="text" class="form-control bg-color-login input_border @error('email') is-invalid @enderror" placeholder="hello@example.com" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus />
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="pwd">{{ __('Password')}}</label>
                                        <input type="password" class="form-control bg-color-login input_border  @error('password') is-invalid @enderror" placeholder="******" id="pwd" name="password" required autocomplete="current-password" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-7">
                                    <div class="form-group form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
                                            <span class="under-line font-weight-bold">{{ __('Remember me prefrences') }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-5 text-body font-weight-bold">
                                    <a class="text-body under-line" href="{{route('forget.password.get')}}">{{ __('Forgot Password ?') }}</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="btn sign-in-btn text-white">
                                        <span>Sign Me In</span>
                                    </button>
                                </div>
                            </div>
                             <!--<div class="row">
                                <div class="col-sm-5">
                                    <a class="text-body under-line font-weight-bold" href=""> {{ __("Don't have an account ?") }}</a>
                                </div>
                               <div class="col-sm-6">
                                    <a class="text-info under-line text-left font-weight-bold" href="">
                                        {{ __('Sign up') }}
                                    </a>
                                </div>
                            </div>-->
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
