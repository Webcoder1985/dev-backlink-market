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

</style>
@endpush



@push("navtype")
<x-frontend.layout.navbar :navType="3" /> {{-- Component //--}}
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
                        <span class="check"><img src="images/check.png" alt=""></span>
                        <p class="text">Get access to thousands
                            of link opportunities
                        </p>
                    </li>
                    <li class="list-item wow fadeInDown d-flex mb-2" data-wow-duration="0.4s" data-wow-delay="0.4s">
                        <span class="check"><img src="images/check.png" alt=""></span>
                        <p class="text">Links from real, active websites with traffic</p>
                    </li>
                    <li class="list-item wow fadeInDown d-flex mb-2" data-wow-duration="0.4s" data-wow-delay="0.4s">
                        <span class="check"><img src="images/check.png" alt=""></span>
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
                        <div class="input-group mb-3">
                            <select name="country" id="country" class="form-select" >
                                <option value="">Select Country</option>
                                <option value="AF">Afghanistan</option>
                                <option value="AX">Åland Islands</option>
                                <option value="AL">Albania</option>
                                <option value="DZ">Algeria</option>
                                <option value="AS">American Samoa</option>
                                <option value="AD">Andorra</option>
                                <option value="AO">Angola</option>
                                <option value="AI">Anguilla</option>
                                <option value="AQ">Antarctica</option>
                                <option value="AG">Antigua and Barbuda</option>
                                <option value="AR">Argentina</option>
                                <option value="AM">Armenia</option>
                                <option value="AW">Aruba</option>
                                <option value="AU">Australia</option>
                                <option value="AT">Austria</option>
                                <option value="AZ">Azerbaijan</option>
                                <option value="BS">Bahamas</option>
                                <option value="BH">Bahrain</option>
                                <option value="BD">Bangladesh</option>
                                <option value="BB">Barbados</option>
                                <option value="BY">Belarus</option>
                                <option value="BE">Belgium</option>
                                <option value="BZ">Belize</option>
                                <option value="BJ">Benin</option>
                                <option value="BM">Bermuda</option>
                                <option value="BT">Bhutan</option>
                                <option value="BO">Bolivia, Plurinational State of</option>
                                <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
                                <option value="BA">Bosnia and Herzegovina</option>
                                <option value="BW">Botswana</option>
                                <option value="BV">Bouvet Island</option>
                                <option value="BR">Brazil</option>
                                <option value="IO">British Indian Ocean Territory</option>
                                <option value="BN">Brunei Darussalam</option>
                                <option value="BG">Bulgaria</option>
                                <option value="BF">Burkina Faso</option>
                                <option value="BI">Burundi</option>
                                <option value="KH">Cambodia</option>
                                <option value="CM">Cameroon</option>
                                <option value="CA">Canada</option>
                                <option value="CI">Canary Islands</option>
                                <option value="CV">Cape Verde</option>
                                <option value="KY">Cayman Islands</option>
                                <option value="CF">Central African Republic</option>
                                <option value="TD">Chad</option>
                                <option value="CL">Chile</option>
                                <option value="CN">China</option>
                                <option value="CX">Christmas Island</option>
                                <option value="CC">Cocos (Keeling) Islands</option>
                                <option value="CO">Colombia</option>
                                <option value="KM">Comoros</option>
                                <option value="CG">Congo</option>
                                <option value="CD">Congo, the Democratic Republic of the</option>
                                <option value="CK">Cook Islands</option>
                                <option value="CR">Costa Rica</option>
                                <option value="CI">Côte d'Ivoire</option>
                                <option value="HR">Croatia</option>
                                <option value="CU">Cuba</option>
                                <option value="CW">Curaçao</option>
                                <option value="CY">Cyprus</option>
                                <option value="CZ">Czech Republic</option>
                                <option value="DK">Denmark</option>
                                <option value="DJ">Djibouti</option>
                                <option value="DM">Dominica</option>
                                <option value="DO">Dominican Republic</option>
                                <option value="EC">Ecuador</option>
                                <option value="EG">Egypt</option>
                                <option value="SV">El Salvador</option>
                                <option value="GQ">Equatorial Guinea</option>
                                <option value="ER">Eritrea</option>
                                <option value="EE">Estonia</option>
                                <option value="ET">Ethiopia</option>
                                <option value="FK">Falkland Islands (Malvinas)</option>
                                <option value="FO">Faroe Islands</option>
                                <option value="FJ">Fiji</option>
                                <option value="FI">Finland</option>
                                <option value="FR">France</option>
                                <option value="GF">French Guiana</option>
                                <option value="PF">French Polynesia</option>
                                <option value="TF">French Southern Territories</option>
                                <option value="GA">Gabon</option>
                                <option value="GM">Gambia</option>
                                <option value="GE">Georgia</option>
                                <option value="DE">Germany</option>
                                <option value="GH">Ghana</option>
                                <option value="GI">Gibraltar</option>
                                <option value="GR">Greece</option>
                                <option value="GL">Greenland</option>
                                <option value="GD">Grenada</option>
                                <option value="GP">Guadeloupe</option>
                                <option value="GU">Guam</option>
                                <option value="GT">Guatemala</option>
                                <option value="GG">Guernsey</option>
                                <option value="GN">Guinea</option>
                                <option value="GW">Guinea-Bissau</option>
                                <option value="GY">Guyana</option>
                                <option value="HT">Haiti</option>
                                <option value="HM">Heard Island and McDonald Islands</option>
                                <option value="VA">Holy See (Vatican City State)</option>
                                <option value="HN">Honduras</option>
                                <option value="HK">Hong Kong</option>
                                <option value="HU">Hungary</option>
                                <option value="IS">Iceland</option>
                                <option value="IN">India</option>
                                <option value="ID">Indonesia</option>
                                <option value="IR">Iran, Islamic Republic of</option>
                                <option value="IQ">Iraq</option>
                                <option value="IE">Ireland</option>
                                <option value="IM">Isle of Man</option>
                                <option value="IL">Israel</option>
                                <option value="IT">Italy</option>
                                <option value="JM">Jamaica</option>
                                <option value="JP">Japan</option>
                                <option value="JE">Jersey</option>
                                <option value="JO">Jordan</option>
                                <option value="KZ">Kazakhstan</option>
                                <option value="KE">Kenya</option>
                                <option value="KI">Kiribati</option>
                                <option value="KP">Korea, Democratic People's Republic of</option>
                                <option value="KR">Korea, Republic of</option>
                                <option value="KW">Kuwait</option>
                                <option value="KG">Kyrgyzstan</option>
                                <option value="LA">Lao People's Democratic Republic</option>
                                <option value="LV">Latvia</option>
                                <option value="LB">Lebanon</option>
                                <option value="LS">Lesotho</option>
                                <option value="LR">Liberia</option>
                                <option value="LY">Libya</option>
                                <option value="LI">Liechtenstein</option>
                                <option value="LT">Lithuania</option>
                                <option value="LU">Luxembourg</option>
                                <option value="MO">Macao</option>
                                <option value="MK">Macedonia, the former Yugoslav Republic of</option>
                                <option value="MG">Madagascar</option>
                                <option value="MW">Malawi</option>
                                <option value="MY">Malaysia</option>
                                <option value="MV">Maldives</option>
                                <option value="ML">Mali</option>
                                <option value="MT">Malta</option>
                                <option value="MH">Marshall Islands</option>
                                <option value="MQ">Martinique</option>
                                <option value="MR">Mauritania</option>
                                <option value="MU">Mauritius</option>
                                <option value="YT">Mayotte</option>
                                <option value="MX">Mexico</option>
                                <option value="FM">Micronesia, Federated States of</option>
                                <option value="MD">Moldova, Republic of</option>
                                <option value="MC">Monaco</option>
                                <option value="MN">Mongolia</option>
                                <option value="ME">Montenegro</option>
                                <option value="MS">Montserrat</option>
                                <option value="MA">Morocco</option>
                                <option value="MZ">Mozambique</option>
                                <option value="MM">Myanmar</option>
                                <option value="NA">Namibia</option>
                                <option value="NR">Nauru</option>
                                <option value="NP">Nepal</option>
                                <option value="NL">Netherlands</option>
                                <option value="NC">New Caledonia</option>
                                <option value="NZ">New Zealand</option>
                                <option value="NI">Nicaragua</option>
                                <option value="NE">Niger</option>
                                <option value="NG">Nigeria</option>
                                <option value="NU">Niue</option>
                                <option value="NF">Norfolk Island</option>
                                <option value="MP">Northern Mariana Islands</option>
                                <option value="NO">Norway</option>
                                <option value="OM">Oman</option>
                                <option value="PK">Pakistan</option>
                                <option value="PW">Palau</option>
                                <option value="PS">Palestinian Territory, Occupied</option>
                                <option value="PA">Panama</option>
                                <option value="PG">Papua New Guinea</option>
                                <option value="PY">Paraguay</option>
                                <option value="PE">Peru</option>
                                <option value="PH">Philippines</option>
                                <option value="PN">Pitcairn</option>
                                <option value="PL">Poland</option>
                                <option value="PT">Portugal</option>
                                <option value="PR">Puerto Rico</option>
                                <option value="QA">Qatar</option>
                                <option value="RE">Réunion</option>
                                <option value="RO">Romania</option>
                                <option value="RU">Russian Federation</option>
                                <option value="RW">Rwanda</option>
                                <option value="BL">Saint Barthélemy</option>
                                <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
                                <option value="KN">Saint Kitts and Nevis</option>
                                <option value="LC">Saint Lucia</option>
                                <option value="MF">Saint Martin (French part)</option>
                                <option value="PM">Saint Pierre and Miquelon</option>
                                <option value="VC">Saint Vincent and the Grenadines</option>
                                <option value="WS">Samoa</option>
                                <option value="SM">San Marino</option>
                                <option value="ST">Sao Tome and Principe</option>
                                <option value="SA">Saudi Arabia</option>
                                <option value="SN">Senegal</option>
                                <option value="RS">Serbia</option>
                                <option value="SC">Seychelles</option>
                                <option value="SL">Sierra Leone</option>
                                <option value="SG">Singapore</option>
                                <option value="SX">Sint Maarten (Dutch part)</option>
                                <option value="SK">Slovakia</option>
                                <option value="SI">Slovenia</option>
                                <option value="SB">Solomon Islands</option>
                                <option value="SO">Somalia</option>
                                <option value="ZA">South Africa</option>
                                <option value="GS">South Georgia and the South Sandwich Islands</option>
                                <option value="SS">South Sudan</option>
                                <option value="ES">Spain</option>
                                <option value="LK">Sri Lanka</option>
                                <option value="SD">Sudan</option>
                                <option value="SR">Suriname</option>
                                <option value="SJ">Svalbard and Jan Mayen</option>
                                <option value="SZ">Swaziland</option>
                                <option value="SE">Sweden</option>
                                <option value="CH">Switzerland</option>
                                <option value="SY">Syrian Arab Republic</option>
                                <option value="TW">Taiwan, Province of China</option>
                                <option value="TJ">Tajikistan</option>
                                <option value="TZ">Tanzania, United Republic of</option>
                                <option value="TH">Thailand</option>
                                <option value="TL">Timor-Leste</option>
                                <option value="TG">Togo</option>
                                <option value="TK">Tokelau</option>
                                <option value="TO">Tonga</option>
                                <option value="TT">Trinidad and Tobago</option>
                                <option value="TN">Tunisia</option>
                                <option value="TR">Turkey</option>
                                <option value="TM">Turkmenistan</option>
                                <option value="TC">Turks and Caicos Islands</option>
                                <option value="TV">Tuvalu</option>
                                <option value="UG">Uganda</option>
                                <option value="UA">Ukraine</option>
                                <option value="AE">United Arab Emirates</option>
                                <option value="GB">United Kingdom</option>
                                <option value="US">United States</option>
                                <option value="UM">United States Minor Outlying Islands</option>
                                <option value="UY">Uruguay</option>
                                <option value="UZ">Uzbekistan</option>
                                <option value="VU">Vanuatu</option>
                                <option value="VE">Venezuela, Bolivarian Republic of</option>
                                <option value="VN">Viet Nam</option>
                                <option value="VG">Virgin Islands, British</option>
                                <option value="VI">Virgin Islands, U.S.</option>
                                <option value="WF">Wallis and Futuna</option>
                                <option value="EH">Western Sahara</option>
                                <option value="YE">Yemen</option>
                                <option value="ZM">Zambia</option>
                                <option value="ZW">Zimbabwe</option>
                            </select>

                            @error('country')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        </div>
                        <div class="input-group mb-3">
                            <div class="checkbox d-flex">

                                    <input type="checkbox" class="form-check-input"  name="terms" id="terms" value="{{ !old('terms') ?: '1' }}">
                                <label for="terms" class="rem">I read and agree to the
                                    <a href="terms" style="font-size:13px">terms of usage</a></label>

                            </div>
                            @error('terms')
                            <div class="checkbox d-flex">

                                <span class="invalid-feedback d-block" role="alert" >
                                    <strong>{{ $errors->first('terms') }}</strong>
                                </span>

                            </div>
                            @enderror
                        </div>
                        <div id="signup_id" class="d-flex justify-content-center"></div>
                        {!! GoogleReCaptchaV2::render('signup_id') !!}
                        <div class="">
                            <button type="submit" class="btn btn-primary mt-4">
                                {{ __('Register') }}
                            </button>
                        </div>
                          <div class="signin_with mt-3 text-center">
                    You already have a membership?<br>
                    <a class="link" href="{{route('userlogin')}}">Login</a>
                </div>

                    </div>
            </div>
            </form>
            {{-- <div class="copyright text-center">
            &copy;
            <script>document.write(new Date().getFullYear())</script>,
            <span>Designed by <a href="https://thememakker.com/" target="_blank">ThemeMakker</a></span>
        </div> --}}
        </div>
        <div class="col-md-4">
            <div class="account-wrapper mt-5 pt-3">
                <div class="account-body">
                    <h4 class="subtitle" style="padding:10px 20px !important">Advantages for Link Buyer </h4>
                </div>
                <ul class="adv">
                    <li class="list-item wow fadeInDown d-flex mb-2" data-wow-duration="0.4s" data-wow-delay="0.4s">
                        <span class="check"><img src="images/check.png" alt=""></span>
                        <p class="text">Get access to thousands
                            of clients
                        </p>
                    </li>
                    <li class="list-item wow fadeInDown d-flex mb-2" data-wow-duration="0.4s" data-wow-delay="0.4s">
                        <span class="check"><img src="images/check.png" alt=""> </span>
                        <p class="text">Earn monthly, recurring,
                            passive income
                        </p>
                    </li>
                    <li class="list-item wow fadeInDown d-flex mb-2" data-wow-duration="0.4s" data-wow-delay="0.4s">
                        <span class="check"><img src="images/check.png" alt=""></span>
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
