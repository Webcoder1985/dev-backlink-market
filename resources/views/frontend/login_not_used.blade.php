@extends('frontend.include.template')
@section('title','Login - Backlink Market')


@section('meta_description','Backlink Market Login')
@section('meta_keywords','Backlink Market Login')

@push('vendorcss')


<style>
    .auth_form {
        -moz-transition: all 0.5s;
        -o-transition: all 0.5s;
        -webkit-transition: all 0.5s;
        transition: all 0.5s;
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        -ms-border-radius: 10px;
        border-radius: 10px;
        background: #f5f5f5;
        border: 5px solid #fff;
        box-shadow: 0 0 16px rgb(41 42 51 / 6%), 0 6px 20px rgb(41 42 51 / 2%);
    }

    .auth_form h5 {
        margin-top: 10px;
        margin-bottom: 0;
    }

    .auth_form .body {
        background: transparent;
    }

    .body {
        font-weight: 400;
        border-radius: 0.35rem;
        background: #fff;
        font-size: 14px;
        color: #222;
        padding: 20px;
    }

    .header {
        color: #444;
        padding: 20px 0 10px 0;
        position: relative;
        box-shadow: none;
    }

    .auth_form .checkbox label {
        font-size: 13px;
    }

    .checkbox,
    .radio {
        margin-bottom: 12px;
    }

    .btn.facebook {
        background: #3a579b;
        border: none;
    }

    .btn.twitter {
        background: #3c94fe;
        border: none;
    }

    .btn.google {
        background: #d5472f;
        border: none;
    }

    .btn i {
        font-size: 14px;
    }

    .btn-round {
        border-width: 1px;
        border-radius: 30px !important;
        padding: 8px 23px;
    }

    .btn.btn-icon {
        height: 2.375rem;
        min-width: 2.375rem;
        width: 2.375rem;
        padding: 0;
        font-size: .9375rem;
        overflow: hidden;
        position: relative;
        line-height: 2.375rem;
        margin: 5px 1px;
    }

    .form-check-input {
        height: 24px !important;
        width: 24px !important;
    }

    .rem {
        position: relative;
        cursor: pointer;
        padding-left: 10px;
        line-height: 32px;
    }

</style>
@endpush



@push("navtype")
<x-frontend.layout.navbar :navType="3" /> {{-- Component //--}}
@endpush

@section("pagecontent")
<div class="container">
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-lg-4 col-sm-12">
            <div class="mb-5 mt-5">
                <form class="auth_form" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="header text-center">

                        <h3>Log in</h3>
                    </div>
                    <div class="body">
                        <div class="input-group mb-3">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" placeholder="email" required
                                autocomplete="email" autofocus>
                                <div class="input-group-append">
                                    <span class="input-group-text" style="height:37px"><i class="fa fa-user-circle"></i></span>
                                </div>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>
                        <div class="input-group mb-3">
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password"
                                placeholder="password" required autocomplete="current-password">
                                <div class="input-group-append">
                                    <span class="input-group-text" style="height:37px"><i class="fa fa-lock"></i></span>
                                </div>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>
                        <div class="input-group mb-1">
                            <div class="checkbox d-flex">
                                <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label for="remember" class="rem">Remember Me</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            {{ __('Login') }}
                        </button>


                        <div class="signin_with text-center mt-3">
                 <p class="mb-0"><a class="small" href="{{ route('forget.password.get') }}">Forgot Password</a> <span class="small">or</span> <a class="small" href="{{ route('register') }}">Sign Up</a></p>
                </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div>
</div>
@endsection
