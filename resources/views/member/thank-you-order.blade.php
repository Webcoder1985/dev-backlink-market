@extends('layout.master')
@section('title', 'Thank you for your order')
@section('parentPageTitle', 'Tables')
@section('page-style')
<link rel="stylesheet" href="{{asset('assets/plugins/jquery-datatable/dataTables.bootstrap4.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/plugins/jquery-datatable/responsive.dataTables.min.css')}}" />
<!-- noUISlider Css -->
<link rel="stylesheet" href="{{asset('assets/plugins/nouislider/nouislider.min.css')}}"/>
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('assets/plugins/select2/select2.css')}}"/>
@stop
@section('content')
    <div class="row clearfix">
        <div class="col-lg-12">
            <form class="card auth_form mb-0" id="placeorder" method="POST">
                @csrf
                <div class="card" style="text-align: center">
                    <h3 style="margin-top: 35px;">Thank you for your order. </h3>
                    <h4>You will receive a confirmation e-mail soon.</h4>
                    <a href="{{route('buyerorder')}}">See Your Order History</a>
                </div>
            </form>
        </div>
    </div>
@stop
@section('page-script')
    <script>
        var base_url = '{{url("/")}}';

    </script>
    <script src="{{asset('assets/plugins/select2/select2.min.js')}}"></script> <!-- Select2 Js -->
<script src="{{asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.js')}}"></script> <!-- noUISlider Plugin Js -->
<script src="{{asset('assets/plugins/sweetalert/sweetalert.min.js')}}"></script> <!-- Sweetalert Js -->
<script src="{{asset('assets/js/pages/cart.js')}}"></script>

@stop
