@extends('layout.master')
@section('title', 'Admin Management - Orders')
@section('parentPageTitle', 'Tables')
@section('page-style')
    <link rel="stylesheet" href="{{asset('assets/plugins/jquery-datatable/dataTables.bootstrap4.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/plugins/jquery-datatable/responsive.dataTables.min.css')}}"/>
    <!-- noUISlider Css -->
    <link rel="stylesheet" href="{{asset('assets/plugins/ion-rangeslider/css/ion.rangeSlider.css')}}"/>
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('assets/plugins/select2/select2.css')}}"/>
    <!-- Sweetalert -->
    <link rel="stylesheet" href="{{asset('assets/plugins/sweetalert/sweetalert.css')}}"/>

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
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


                        <div class="col-md-1 col-sm-2 mb-sm-4">
                            <div class="irs-demo">
                                <p><b>Start</b></p>
                                <input type="date" id="order_date_start" value="@php echo date('Y-m-d', strtotime('first day of last month')); @endphp" class="search_box"/>
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-2 mb-sm-4">
                            <div class="irs-demo">
                                <p><b>End</b></p>
                                <input type="date" id="order_date_end" value="@php echo date('Y-m-d', strtotime('last day of last month')); @endphp" class="search_box"/>
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-2 mb-sm-4">
                            <div class="irs-demo">
                                <button id="Search_Btn" class="btn btn-primary btn-md mt-lg-4" name="Search_Btn">
                                    Search
                                </button>
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
                            <div id="calc" class="text-center"></div>
                            <table class="table display responsive nowrap table-hover product_item_list c_table theme-color mb-0" id="IncomeDatatable">

                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Nachname</th>
                                    <th>Brutto</th>
                                    <th>Netto</th>
                                    <th>USt.</th>
                                    <th>Steuersatz</th>
                                    <th>Gebühren</th>
                                    <th>Land</th>
                                    <th>Kategorie</th>
                                    <th>Steuernummer</th>
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
            <!-- needed to make excel download work -->
            <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
            <script src="{{asset('assets/plugins/jquery-datatable/buttons/buttons.colVis.min.js')}}"></script>
            <script src="{{asset('assets/plugins/jquery-datatable/buttons/buttons.flash.min.js')}}"></script>
            <script src="{{asset('assets/plugins/jquery-datatable/buttons/buttons.html5.min.js')}}"></script>
            <script src="{{asset('assets/plugins/jquery-datatable/buttons/buttons.print.min.js')}}"></script>
            <script src="{{asset('assets/plugins/select2/select2.min.js')}}"></script> <!-- Select2 Js -->
            <script src="{{asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.js')}}"></script>
            <!-- noUISlider Plugin Js -->
            <script src="{{asset('assets/plugins/sweetalert/sweetalert.min.js')}}"></script> <!-- Sweetalert Js -->
            <script src="{{asset('assets/js/pages/tables/jquery-datatable.js')}}"></script>
            <script src="{{asset('assets/js/pages/marketplace.js')}}"></script>
            <script src="{{asset('assets/js/pages/order.js')}}"></script>
            <script>
                 $(document).ready(function(){ $('.dt-buttons').hide();});


                $('#order_date_start').change(function () {
                    $(this).attr('value', $('#order_date_start').val());
                });
                $('#order_date_end').change(function () {
                    $(this).attr('value', $('#order_date_end').val());
                });

            </script>

@stop
