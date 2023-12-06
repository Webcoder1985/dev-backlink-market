@extends('frontend.include.template')
@section('title','Registration E-Mail Activation')


@section('meta_description','backlink market')
@section('meta_keywords','backlink market')
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
<x-frontend.layout.navbar :navType="3" /> {{-- Component //--}}
@endpush

@section("pagecontent")
<div class="container">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-lg-6 col-sm-12">
            <div class="mb-5 mt-5">
              <div class="mb-4 text-sm text-gray-600">
              @if (Session::has('resent'))
                  <div class="mb-4 font-medium text-sm text-green-600 text-success">
                      {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                  </div>
              @endif
 <div class="alert alert-success" role="alert">
  <h3 class="alert-heading">Registration Successful</h3>
  <p class="text-center"><br>Please click the <strong>verification link</strong> in the e-mail that was just sent to you.<br>  (Don't forget to check your spam folder.) </p>
  <hr>

  <div class="mt-4 flex text-center items-center justify-between">
                  <form method="POST" action="{{ route('verification.send') }}">
                      @csrf

                      <div>
                            <button type="submit" class="nav-link button-1 text-white btn button button-1 sub">
                              {{ __('Resend Verification Email') }}
                          </button>
                      </div>
                  </form>
                  <br />
                  <form method="POST" action="{{ route('logout') }}">
                      @csrf

                      <button type="submit" class="nav-link button-1 text-white btn button button-1 sub">
                          {{ __('Log Out') }}
                      </button>
                  </form>
              </div>


</div>




             </div>

              @if (session('status') == 'verification-link-sent')
                  <div class="mb-4 font-medium text-sm text-green-600">
                      {{ __('A new verification link has been sent to your e-mail.') }}
                  </div>
              @endif


            </div>

        </div>
    </div>
</div>
@endsection
