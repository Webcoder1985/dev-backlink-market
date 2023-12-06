@extends('layout.master')
@section('title', 'Profile')
@section('parentPageTitle', 'Tables')
@section('page-style')
    <!-- Sweetalert -->
    <link rel="stylesheet" href="{{asset('assets/plugins/sweetalert/sweetalert.css')}}"/>
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('assets/plugins/select2/select2.css')}}"/>
@stop
@section('content')
    <h3>Account - Profile</h3>
    <form class="card auth_form" data-id="" id="UserDetail" method="POST" action="{{ route('updateprofile') }}">
        @csrf
        <div class="body">
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            @if(session()->has('error_message'))
                <div class="alert alert-danger">
                    @php
                        echo '<h4 class="alert-heading">Account Update Failed!</h4>';
                        $res= session()->get('error_message');
                             foreach($res->messages() as $error=>$value){
                                 echo $error.": ".$value[0]."<br>";
                             }
                    @endphp
                </div>
            @endif
            <div class="row">
                <div class="col-md-6">
                    <label for="firstname">First Name</label>
                    <div class="input-group mb-3">
                        <input id="firstname" type="text" class="form-control" value="{{ $user['firstname'] }}" name="firstname" autofocus>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="lastname">Last Name</label>
                    <div class="input-group mb-3">
                        <input id="lastname" type="text" class="form-control" value="{{ $user['lastname'] }}" name="lastname">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="email">Email</label><input id="email" type="email" class="form-control" value="{{ $user['email'] }}" readonly="" name="email" autocomplete="email">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="paypal_email">PayPal Email</label>
                    <div class="input-group-append">
                        <span class="input-group-text" style="height:35px;"><i class="zmdi zmdi-paypal"></i></span>
                        <input type="email" class="form-control" value="{{ $user['paypal_email'] }}" placeholder="Used for Withdrawal only" id="paypal_email" name="paypal_email">
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="street">Street</label>
                    <div class="input-group mb-3">
                        <input id="street" type="text" class="form-control" value="{{ $user['street'] }}" name="street">
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="street_number">Street Number</label>
                    <div class="input-group mb-3">
                        <input id="street_number" type="text" class="form-control" value="{{ $user['street_number'] }}" name="street_number">
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="zip">Zip</label>
                    <div class="input-group mb-3">
                        <input id="zip" type="text" class="form-control" value="{{ $user['zip'] }}" name="zip">
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="city">City</label>
                    <div class="input-group mb-3">
                        <input id="city" type="text" class="form-control" value="{{ $user['city'] }}" name="city">
                    </div>
                </div>
            </div>
                <label for="country">Country</label>
                <div class="input-group mb-3">
                    <select name="country" id="country" class="form-control show-tick ms select2">
                        <option value="">Select Country</option>
                        @foreach($countryLists as $countryList)
                            <option value="{{$countryList['code']}}" {{ (($user['country']==$countryList['code'])?'selected=selected':'') }} eucountry="{{$countryList['eucountry']}}">{{$countryList['country']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group mb-1 vat_field" style="{{ (($eucountry==1)?'':'display: none;') }}">
                            <label for="vat">Vat</label>
                            <div class="input-group mb-3">
                                <input id="vat" type="text" class="form-control" name="vat" value="{{ $user['vat'] }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group mb-1 de_field" style="{{ (($user['country']=='DE' && $user['vat']!='')?'':'display: none;') }}">
                            <label for="kleinunternehmer">Kleinunternehmer nach § 19 UStG</label>
                            <div class="input-group">
                                <input id="kleinunternehmer" type="checkbox" class="form-check-input" name="kleinunternehmer" {{(($user['kleinunternehmer']=='1')?'checked':'')}} style="margin-top: 8px;margin-left: 0px;">
                            </div>
                        </div>
                    </div>
                </div>


                <label for="password">Password</label>
                <div class="input-group mb-3">
                    <input id="password" type="password" class="form-control" name="password">
                </div>
                <br/>
                <button type="submit" class="btn btn-primary">
                    {{ __('Update Profile') }}
                </button>
        </div>
    </form>
@stop
@section('page-script')
    <script src="{{asset('assets/bundles/datatablescripts.bundle.js')}}"></script>
    <script src="{{asset('assets/plugins/jquery-datatable/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('assets/plugins/jquery-datatable/buttons/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('assets/plugins/jquery-datatable/buttons/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/jquery-datatable/buttons/buttons.colVis.min.js')}}"></script>
    <script src="{{asset('assets/plugins/jquery-datatable/buttons/buttons.flash.min.js')}}"></script>
    <script src="{{asset('assets/plugins/jquery-datatable/buttons/buttons.html5.min.js')}}"></script>
    <script src="{{asset('assets/plugins/jquery-datatable/buttons/buttons.print.min.js')}}"></script>
    <script src="{{asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.js')}}"></script> <!-- noUISlider Plugin Js -->
    <script src="{{asset('assets/plugins/sweetalert/sweetalert.min.js')}}"></script> <!-- Sweetalert Js -->
    <script src="{{asset('assets/js/pages/tables/jquery-datatable.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/select2.min.js')}}"></script> <!-- Select2 Js -->

    <script src="{{asset('assets/plugins/jquery-datatable/dataTables.select.min.js')}}"></script>
    <script src="{{asset('assets/js/pages/marketplace.js')}}"></script>
@stop
