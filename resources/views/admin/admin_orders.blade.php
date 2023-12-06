@extends('layout.master')
@section('title', 'Admin Management - Orders')
@section('parentPageTitle', 'Tables')
@section('page-style')
<link rel="stylesheet" href="{{asset('assets/plugins/jquery-datatable/dataTables.bootstrap4.min.css')}}"/>
<link rel="stylesheet" href="{{asset('assets/plugins/jquery-datatable/responsive.dataTables.min.css')}}"/>
<!-- noUISlider Css -->
<link rel="stylesheet" href="{{asset('assets/plugins/ion-rangeslider/css/ion.rangeSlider.css')}}" />
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('assets/plugins/select2/select2.css')}}" />
<!-- Sweetalert -->
<link rel="stylesheet" href="{{asset('assets/plugins/sweetalert/sweetalert.css')}}" />
@stop
@section('content')
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <h3 class="mb-0">Admin - Orders</h3>
            <div class="header pt-0">
                <div class="row">
                    <div class="col-6 pt-4">
                        <h2><strong>Filters</strong></h2>
                    </div>

                </div>
            </div>
            <div class="body">
                <div class="row clearfix mb-4">

                </div>
                <div class="row clearfix mb-4">
                  <div class="col-md-4 col-sm-6 mb-sm-4">
                      <div class="irs-demo">
                          <p><b>Order Id</b></p>
                          <input type="text" id="order_id" value="" class="search_box" />
                      </div>
                  </div>
                  <div class="col-md-4 col-sm-6 mb-sm-4">
                      <div class="irs-demo">
                          <p><b>Buyer Name</b></p>
                          <input type="text" id="buyer_name" value="" class="search_box"/>
                      </div>
                  </div>
                  <div class="col-md-4 col-sm-6 mb-sm-4">
                      <div class="irs-demo">
                          <p><b>Order Date</b></p>
                          <input type="date" id="order_date" value="" class="search_box"/>
                      </div>
                  </div>
                </div>
                <div class="row clearfix">



                </div>
            </div>
        </div>

        <div class="row">
        <div class="col-lg-12 table_main">
        <div class="card">
            <div class="table-responsive">
                <table class="table display responsive nowrap table-hover product_item_list c_table theme-color mb-0" id="OrderDatatable">
                    <thead>
                        <tr>
                            <th>Order Id</th>
                            <th>Date</th>
                            <th>Buyer Mail</th>
                            <th>Seller Mail</th>
                            <th>Site URL</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
      </div>

    </div>
</div>


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
<script src="{{asset('assets/plugins/select2/select2.min.js')}}"></script> <!-- Select2 Js -->
<script src="{{asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.js')}}"></script> <!-- noUISlider Plugin Js -->
<script src="{{asset('assets/plugins/sweetalert/sweetalert.min.js')}}"></script> <!-- Sweetalert Js -->
<script src="{{asset('assets/js/pages/tables/jquery-datatable.js')}}"></script>
<script src="{{asset('assets/js/pages/marketplace.js')}}"></script>
<script src="{{asset('assets/js/pages/order.js')}}"></script>
@stop
