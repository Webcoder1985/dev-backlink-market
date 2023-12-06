@extends('frontend.include.template')
@section('title','Reset Password - Backlink Market')

@section('meta_description','Reset Password - Backlink Market')
@section('meta_keywords','Reset Password,Backlink Market')
@section('robots')<meta name="robots" content="noindex, nofollow">@endsection

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
<x-frontend.layout.navbar :navType="3" /> <!-- Component //-->
@endpush


@section('pagecontent')
<div class="container">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-lg-6 col-sm-12">
            <div class="mb-5 mt-5">
                    <div class="header text-center pb-0">
                       <h4 class="mb-2">Reset Password</h4>
                        <span style="font-size: 14px;">Enter your e-mail address and new password below to reset your password.</span>
                    </div>
                      <form action="{{ route('reset.password.post') }}" method="POST">
                          @csrf
                          <input type="hidden" name="token" value="{{ $token }}">

                          <div class="form-group row">
                              <label for="email_address" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                              <div class="col-md-6">
                                  <input type="text" id="email_address" class="form-control" name="email" required autofocus>
                                  @if ($errors->has('email'))
                                      <span class="text-danger">{{ $errors->first('email') }}</span>
                                  @endif
                              </div>
                          </div>

                          <div class="form-group row">
                              <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                              <div class="col-md-6">
                                  <input type="password" id="password" class="form-control" name="password" required autofocus>
                                  @if ($errors->has('password'))
                                      <span class="text-danger">{{ $errors->first('password') }}</span>
                                  @endif
                              </div>
                          </div>

                          <div class="form-group row">
                              <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                              <div class="col-md-6">
                                  <input type="password" id="password-confirm" class="form-control" name="password_confirmation" required autofocus>
                                  @if ($errors->has('password_confirmation'))
                                      <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                  @endif
                              </div>
                          </div>

                          <div class="col-md-6 offset-md-4">
                              <button type="submit" class="cus-btn-2">
                                 Save Password
                              </button>
                          </div>
                      </form>
</div>
            <div class="col-md-4"></div>
        </div>
    </div>
</div>


@endsection
