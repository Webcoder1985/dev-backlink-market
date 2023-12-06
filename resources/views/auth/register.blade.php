@extends('frontend.include.template')
@section('title','Create an Account - Registration')

{{-- SEO --}}
@section('meta_description','Backlink Market Registration')
@section('meta_keywords','Backlink Market Registration')
@section('robots')<meta name="robots" content="noindex, nofollow">@endsection
{{-- SEO--}}
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

.cus-btn{
    background: #019BDB !important;
    border:1px solid #019BDB !important;
}
.member{
    color: #5d5779;
}
.member:hover{
    color: #019BDB !important;
}

</style>
@endpush



@push("navtype")
<x-frontend.layout.navbar :navType="3" /> <!-- Component //-->
@endpush

@section("pagecontent")
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="account-wrapper mb-4 mt-5 pt-3">
                <div class="account-body" style="padding:10px 20px !important">
                    <h4 class="subtitle">Advantages for Link Buyer </h4>
                </div>
                <ul class="adv">
                    <li class="list-item wow fadeInDown d-flex mb-2" data-wow-duration="0.4s" data-wow-delay="0.4s">
                        <span class="check"><img src="{{asset('images/check.png')}}" alt=""></span>
                        <p class="text">Get access to thousands
                            of link opportunities
                        </p>
                    </li>
                    <li class="list-item wow fadeInDown d-flex mb-2" data-wow-duration="0.4s" data-wow-delay="0.4s">
                        <span class="check"><img src="{{asset('images/check.png')}}" alt=""></span>
                        <p class="text">Links from real, active websites with traffic</p>
                    </li>
                    <li class="list-item wow fadeInDown d-flex mb-2" data-wow-duration="0.4s" data-wow-delay="0.4s">
                        <span class="check"><img src="{{asset('images/check.png')}}" alt=""></span>
                        <p class="text"> Google friendly Backlinks &nbsp;&nbsp;&nbsp;&nbsp;</p>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-lg-4 col-sm-12">
            <div class="mb-5 mt-5">
                <form class="auth_form" method="POST" action="{{ route('register') }}">
                    <input type="hidden" name="action_register" id="action_register" value="">
                    @csrf
                    <div class="header text-center">

                        <h5>Register</h5>
                    </div>
                    <div class="body">
                        <div class="input-group mb-3">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" placeholder="email" required
                                autocomplete="email" autofocus><div class="input-group-append">
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
                                class="form-control @error('password')is-invalid @enderror" name="password"
                                placeholder="password (min. 8 length)" required autocomplete="current-password">
                                <div class="input-group-append">
                                    <span class="input-group-text" style="height:37px"><i class="fa fa-lock"></i></span>
                                </div>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                      @if(!$country)
                  <div class="input-group mb-3">
                      <select name="country" id="country" class="form-control @error('password')is-invalid @enderror">
                        <option value="">Select Country</option>
                         @foreach($countryLists as $countryList)
                            <option value="{{$countryList['code']}}">{{$countryList['country']}}</option>
                         @endforeach
                      </select>


                      @error('country')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                  </div>
                @endif
                        <div class="input-group mb-3">
                            <div class="checkbox d-flex mb-0">

                                    <input type="checkbox" class="form-check-input @error('terms') is-invalid @enderror"  name="terms" id="terms" value="{{ !old('terms') ?: '1' }}">
                                <label for="terms" class="rem">I read and agree to the
                                    <a href="{{route('terms')}}" style="font-size: 13px;">terms of usage</a></label>

                            </div>
                            @error('terms')
                                <span class="invalid-feedback d-block" role="alert" >
                                    <strong>{{ $errors->first('terms') }}</strong>
                                </span>
                            @enderror
                        </div>
                          @if(config('services.recaptcha.key'))
    <div class="g-recaptcha"
        data-sitekey="{{config('services.recaptcha.key')}}">
    </div>
            <div id="signup_id" class="d-flex justify-content-center"></div>
                        {!! GoogleReCaptchaV2::render('signup_id') !!}

                            @error('g-recaptcha-response')
                                    <span class="invalid-feedback d-block text-center" role="alert" >
                                        <strong>reCAPTCHA: {{ $errors->first('g-recaptcha-response') }}</strong>
                                    </span>
                                @enderror
@endif


                        <div class="">
                            <button type="submit" class="mt-4 cus-btn-2">
                                {{ __('Register') }}
                            </button>
                        </div>
                          <div class="signin_with mt-3 text-center">
                    You already have a membership?<br>
                    <a class="link member" href="{{route('login')}}">Login</a>
                </div>

                    </div>

            </form>

            </div>
        </div>
        <div class="col-md-4">
            <div class="account-wrapper mt-5 pt-3">
                <div class="account-body">
                    <h4 class="subtitle" style="padding:10px 20px !important">Advantages for Link Seller </h4>
                </div>
                <ul class="adv">
                    <li class="list-item wow fadeInDown d-flex mb-2" data-wow-duration="0.4s" data-wow-delay="0.4s">
                        <span class="check"><img src="{{asset('images/check.png')}}" alt=""></span>
                        <p class="text">Get access to thousands
                            of clients
                        </p>
                    </li>
                    <li class="list-item wow fadeInDown d-flex mb-2" data-wow-duration="0.4s" data-wow-delay="0.4s">
                        <span class="check"><img src="{{asset('images/check.png')}}" alt=""> </span>
                        <p class="text">Earn monthly, recurring,
                            passive income
                        </p>
                    </li>
                    <li class="list-item wow fadeInDown d-flex mb-2" data-wow-duration="0.4s" data-wow-delay="0.4s">
                        <span class="check"><img src="{{asset('images/check.png')}}" alt=""></span>
                        <p class="text"> Very easy interface via
                            WordPress Plugin
                        </p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
