@extends('frontend.include.template')
@section('title','Forgot Password - Backlink Market')


@section('meta_description','Forgot Password - Backlink Market')
@section('meta_keywords','Forgot Password,Backlink Market')
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
        <div class="col-md-4"></div>
        <div class="col-lg-4 col-sm-12">
            <div class="mb-5 mt-5">
                <form class="card auth_form" action="{{ route('forget.password.post') }}" method="POST">
                    @if (Session::has('message'))
                    <div class="alert alert-success" role="alert">
                       {{ Session::get('message') }}
                   </div>
               @endif
                    @csrf
                    <div class="header text-center">

                        <h5>Forgot Password</h5>
                    </div>
                    <div class="body">
                        <div class="input-group mb-3">
                            <input type="text" id="email_address" class="form-control" name="email" required autofocus>
                                  @if ($errors->has('email'))
                                      <span class="text-danger">{{ $errors->first('email') }}</span>
                                  @endif
                                  <div class="input-group-append">
                                    <span class="input-group-text" style="height:37px"><i class="fa fa-user-circle"></i></span>
                                </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Send Password Reset Link
                        </button>

                    </div>
                </form>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div>
</div>
@endsection
