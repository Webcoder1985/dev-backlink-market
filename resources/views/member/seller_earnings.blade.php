@extends('layout.master')
@section('title', 'My Earnings')
@section('parentPageTitle', 'Tables')
@section('page-style')
    <link rel="stylesheet" href="{{asset('assets/plugins/jquery-datatable/dataTables.bootstrap4.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/plugins/jquery-datatable/responsive.dataTables.min.css')}}"/>
    <!-- noUISlider Css -->
    <link rel="stylesheet" href="{{asset('assets/plugins/nouislider/nouislider.min.css')}}"/>
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('assets/plugins/select2/select2.css')}}"/>
@stop
@section('content')
    <div class="row clearfix">
        <div class="col-lg-12">
            @csrf
            <div class="card">
                <h3>Seller - Earnings by Month</h3>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane in active" id="inactive_orders">
                        <?php if (count($monthly_earning) > 0){ ?>
                        <div class="table-responsive">
                            <table class="table display responsive nowrap table-hover product_item_list c_table theme-color mb-0" id="activeDatatable">
                                <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Total Earnings</th>

                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $m = 0;
                                foreach ($monthly_earning as $item) {


                                    ?>

                                <tr id="tr_row">
                                    <td>
                                            <?php echo $item->month_year; ?>
                                    </td>
                                    <td style="text-align: left !important;">
                                            <?php echo $item->monthly_earnings; ?>
                                    </td>

                                </tr>


                                    <?php
                                    $m++;
                                } ?>

                                </tbody>

                            </table>
                                <?php if ($m == 0) {
                                echo "<center><h3>No History</h3></center>";
                            }
                                ?>
                        </div>
                        <?php }
                        else {
                            ?>
                        <center><h3>No Orders</h3></center>
                            <?php
                        }
                        ?>
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
            <script src="{{asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.js')}}"></script>
            <!-- noUISlider Plugin Js -->
            <script src="{{asset('assets/plugins/sweetalert/sweetalert.min.js')}}"></script> <!-- Sweetalert Js -->
            <script src="{{asset('assets/js/pages/order.js')}}"></script>
            <script>
                var base_url = '{{url("/")}}';
                $(document).ready(function () {
                    $('#activeDatatable').DataTable({
                        order: [[0, 'desc']],
                        stateSave: true,
                        responsive: true
                    });
                    $('#inactiveDatatable').DataTable({
                        order: [[0, 'desc']],
                        stateSave: true,
                        responsive: true
                    });


                });
            </script>

@stop
