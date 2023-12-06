@extends('layout.master')
@section('title', 'Withdrawal Request')
@section('parentPageTitle', 'Tables')
@section('page-style')
<!-- Sweetalert -->
<link rel="stylesheet" href="{{asset('assets/plugins/sweetalert/sweetalert.css')}}"/>
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('assets/plugins/select2/select2.css')}}"/>
@stop
@section('content')
    <form class="card auth_form" data-id="" id="UserDetail" method="POST" action="{{ route('savewithdrawrequest') }}">
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
                        echo '<h4 class="alert-heading">Withdrawal Request Failed!</h4>';
                        $res= session()->get('error_message');
                             foreach($res->messages() as $error=>$value){
                                 echo $error.": ".$value[0]."<br>";
                             }
                    @endphp
                </div>
            @endif
            @if(isset($missing) && count($missing)>0)
                <div role="alert" class="alert alert-danger">
                    <h4 class="alert-heading mt-0 mb-0">Please complete your Profile details before you request a
                        withdrawal </h4>
                    <hr>
                    <p class="mb-0">

                    <ul>
                        @php
                            foreach ($missing as $elmt){ echo "<li><strong>".$elmt."</strong> is missing</li>";}
                        @endphp
                    </ul>
                    </p>
                </div>
                <br>
                <a href="{{ route('editprofile') }}">Edit Profile</a>
            @elseif(\Auth::user()->balance_seller >= 50)
                <h2>Withdrawal Request</h2>
                <div class="row">
                    <div class="col-md-6">
                        <label for="amount">Please enter the amount you would like to withdraw:</label>
                        <div class="input-group mb-3">
                            <input id="amount" type="number" required class="form-control" value="{{ floor(\Auth::user()->balance_seller) }}" name="amount" placeholder="max. {{ \Auth::user()->balance_seller }}" step="1" min="1" max="{{ \Auth::user()->balance_seller }}">
                        </div>
                        <label for="amount"> PayPal Email</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" style="height:35px;"><i class="zmdi zmdi-paypal"></i></span>
                            <input id="paypal_email" type="email" class="form-control" required value="{{ \Auth::user()->paypal_email }}" name="paypal_email" placeholder="Your PayPal Account Email">
                        </div>
                    </div>
                </div>
                <br/>
                <button type="submit" class="btn btn-primary">
                    {{ __('Request') }}
                </button>
            @else
                <div role="alert" class="alert alert-danger">
                    <h4 class="alert-heading mt-0 mb-0">The minimum payout amount has not yet been reached!</h4>
                    <hr>
                    <p class="mb-0">You can request a withdrawal when your Earnings Balance has reached the minimum
                        payout amount of 50
                        Credits.</p>
                </div>

            @endif
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
