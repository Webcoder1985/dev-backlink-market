@extends('layout.master')
@section('title', 'Orders - Transactions')
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
                <h3>Buyer - Orders</h3>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane in active" id="active_orders">
                        <?php if (count($orders) > 0){ ?>
                        <div class="table-responsive">
                            <table class="table display responsive nowrap table-hover product_item_list c_table theme-color mb-0" id="activeDatatable">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Order ID</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Invoice</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $m = 0;
                                foreach ($orders as $mainorder) {


                                    ?>

                                <tr id="tr_row_<?php echo $mainorder->id;?>">
                                    <td>
                                            <?php echo date('d.m.Y', strtotime($mainorder->created_at)); ?>
                                    </td>
                                    <td>
                                            <?php echo $mainorder->id; ?>
                                    </td>
                                    <td>
                                            <?php echo $mainorder->order_amount . Config::get('app.currency_symbol'); ?>
                                    </td>
                                    <td>
                                            <?php
                                            if ($mainorder->order_status == 0) echo '<span style="color:red;">Unpaid</span>';
                                            elseif ($mainorder->order_status == 10) echo '<span style="color:orange;">Pending</span>';
                                            elseif ($mainorder->order_status == 20) echo '<span style="color:green;">Paid</span>';
                                            ?>
                                    </td>
                                    <td>  <?php if (isset($mainorder->invoice->invoice_link)) echo '<a href="' . $mainorder->invoice->invoice_link . '">Download</a>'; ?></td>
                                        <?php /*><td><a href="javascript:void(0)" onclick="buyerStopSubscription();" alt="Stop Subscription" title="Stop Subscription"><i class="zmdi zmdi-stop"></i></a></td>*/ ?>
                                    <td>
                                            <?php if ($mainorder->order_status == 0){ ?>
                                        <button onclick="" class="btn btn-primary btn-sm"><i class="zmdi zmdi-edit"></i>Pay
                                        </button><?php }
                                                     $table_data = "";
                                                 if (count($mainorder->orderDetails) > 0) {
                                                     $table_data = "<tr id=\"orderDetails-" . $mainorder->id . "\"><td colspan=\"6\"><h4 class='text-left mt-1 mb-1'>Order Details:</h4><table class=\"order_details_table text-left table display responsive nowrap table-hover product_item_list c_table theme-color mb-0 dataTable no-footer dtr-inline\"><thead><tr><td>ID</td><td>Page URL</td><td>Promoted URL</td><td>Anchor Text</td><td>Price</td><td>Refund Status</td><td>Refund Amount</td></tr></thead><tbody>";
                                                     foreach ($mainorder->orderDetails as $orderdetail) {
                                                         $table_data .= "<tr><td>" . $orderdetail->id . "</td><td>" . $orderdetail->page_url . "</td><td>" . $orderdetail->promoted_url . "</td><td>" . $orderdetail->anchor_text . "</td><td>" . $orderdetail->page_price_buyer . "</td><td>" . $orderdetail->refund_status . "</td><td>" . $orderdetail->refund_amount_buyer . "</td></tr>";
                                                     }
                                                     $table_data .= "</td></tr></tbody></table>";
                                                     ?>
                                        <a href="javascript:void(0)" title="Click for Order Details" id="<?php echo $mainorder->id;?>" pu="{{ $table_data }}" class="btn btn-expand-col-white  btn-sm btn-plus btn-expand-col-orderdetails"><i class="zmdi zmdi-plus"></i></a>
                                            <?php
                                        }
                                            ?>

                                    </td>
                                </tr>


                                    <?php
                                    $m++;
                                } ?>

                                </tbody>

                            </table>
                                <?php if ($m == 0) {
                                echo "<center><h3>No Orders</h3></center>";
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


@stop
