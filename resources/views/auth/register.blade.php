@extends('layouts.app-header')

@section('content')
<br><br><br>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    @if(Session::has('error'))
                    <p class="alert {{ Session::get('alert-class', 'alert-danger text-center') }}">
                       {{ Session::get('error') }}
                    </p>
                    @endif
                    <form method="POST" id="registerForm" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" data-parsley-checkemail data-parsley-checkemail-message="Email Address already Exists." data-parsley-trigger="focusout">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" data-parsley-minlength="8"
                                data-parsley-errors-container=".errorspannewpassinput"
                                data-parsley-required-message="Please enter your new password."
                                data-parsley-uppercase="1"
                                data-parsley-lowercase="1"
                                data-parsley-number="1"
                                data-parsley-special="1"
                                data-parsley-required>

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" data-parsley-equalto="#password" data-parsley-minlength="8"
                                    data-parsley-errors-container=".errorspannewpassinput"
                                    data-parsley-required-message="Please re-enter your new password."
                                    data-parsley-uppercase="1"
                                    data-parsley-lowercase="1"
                                    data-parsley-number="1"
                                    data-parsley-special="1"
                                    data-parsley-required> 
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-primary register-btn" id="registerBtn">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                        <br>
                        <div class="form-group row mb-0">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-8">
                                <div class="fb-login-button" data-width="380" data-size="large" data-button-type="login_with" data-layout="rounded" data-auto-logout-link="false" data-use-continue-as="false" scope="public_profile,email" onlogin="checkLoginState();" data-height="40"  ></div>
                                <!-- <fb:login-button scope="public_profile,email" onlogin="checkLoginState();" size="large" scope="public_profile,email" class="fb_button" returnscopes="true">Login with Facebook</fb:login-button> -->
                            </div>
                            <br><br>
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-6">
                                <div class="g-signin2" data-onsuccess="onSignIn" data-theme="dark" data-width="380" data-height="40" data-longtitle="true" ></div>

                            </div>
                        </div>

                        <div id="login-status">
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br><br><br>
@endsection
