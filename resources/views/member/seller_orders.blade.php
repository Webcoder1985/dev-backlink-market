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
                <h3>Seller - Sales</h3>

                <div class="tab-content">

                    <div role="tabpanel" class="tab-pane in active" id="active_orders">
                        <?php if (count($orders) > 0){ ?>
                        <div class="table-responsive">
                            <table class="table display responsive nowrap table-hover product_item_list c_table theme-color mb-0" id="inactiveDatatable">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Order ID</th>
                                    <th>Blog Post URL</th>
                                    <th>Earning</th>
                                    <th>Withdrawal Status</th>

                                    <!--<th>UnSubscribe</th>-->
                                    <th>Show Details</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php $m = 1; foreach ($orders as $order) : ?>

                                <tr id="tr_row_<?php echo $order->id;?>">
                                    <td>
                                            <?php echo date('d.m.Y', strtotime($order->created_at)); ?>
                                    </td>
                                    <td>
                                            <?php echo $order->order_id . '-' . $order->id; ?>
                                    </td>
                                    <td>
                                        <a href="<?=$order->page_url;?>" target="_blank"><?php echo $order->page_url; ?></a>
                                    </td>
                                    <td><?php echo Config::get('app.currency_symbol'); ?><?php
                                            if (isset($order->refund_status)){
                                                if($order->refund_status==1) echo $order->refund_amount_seller; // partial refund
                                                if($order->refund_status==2) echo "0"; // full refund
                                            }

                                            else echo $order->page_price_seller;

                                            ?></td>
                                    <!-- <td></td>-->
                                    <!--<td><a href="javascript:void(0)" onclick="stopSubscription('<?php echo $order->id; ?>');" alt="Stop Subscription" title="Stop Subscription"><i class="zmdi zmdi-stop"></i></a></td>-->
                                    <td><?php
                                            if (isset($order->refund_status)) {
                                                $refund_amount =  $order->refund_amount_seller;
                                                $reason="";
                                                switch ($order->refund_reason) {
                                                        case 1:
                                                            $reason = "Cancelled by Buyer";
                                                            break;
                                                        case 2:
                                                            $reason = "Cancelled by Seller";
                                                            break;
                                                        case 3:
                                                            $reason = "Cancelled by Admin";
                                                            break;
                                                        case 4:
                                                            $reason = "Website offline";
                                                            break;
                                                    }
                                                if ($refund_amount > 0) {
                                                    echo "Added to Earning Balance: " . Config::get('app.currency_symbol').$refund_amount."<br>Partial Refund. (Reason: ".$reason.")";
                                                } else {
                                                    echo "Fully Refunded. (Reason: ".$reason.")";
                                                }
                                            } elseif (isset($order->seller_paid)) {
                                                echo "Added to Earnings Balance";
                                            } else {
                                                $earliest_payout_date = date('Y-m-d', strtotime("+1 day", strtotime('first day of next month', strtotime($order->created_at))));
                                                echo "Becomes available on " . $earliest_payout_date;
                                            }

                                            ?></td>
                                    <td>
                                        <a href="javascript:void(0)" id="<?php echo $order->id;?>" pu="<?php echo $order->promoted_url	;?>" ant="<?php echo $order->anchor_text	;?>" bet="<?php echo $order->link_content	;?>" class="btn btn-sm btn-plus btn-expand-col btn-expand-col-white"><i class="zmdi zmdi-plus"></i></a>
                                    </td>
                                </tr>


                                    <?php $m++; endforeach; ?>

                                </tbody>

                            </table>

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
