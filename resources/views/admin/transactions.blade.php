@extends('layout.master')
@section('title', 'My Backlinks')
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
                <h3>Orders - Transactions</h3>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane in active" id="active_orders">
                        <?php if(count($orders) > 0){ ?>
                        <div class="table-responsive">
                            <table class="table display responsive nowrap table-hover product_item_list c_table theme-color mb-0" id="activeDatatable">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Order ID</th>
                                    <th>User</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Invoice</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $m = 0;
                                foreach($orders as $mainorder) {


                                ?>

                                <tr id="tr_row_<?php echo $mainorder->id;?>">
                                    <td>
                                        <?php echo date('d.m.Y', strtotime($mainorder->created_at));?>
                                    </td>
                                    <td>
                                        <?php echo $mainorder->id;?>
                                    </td>
                                    <td>
                                        <?php echo $mainorder->user->firstname." ".$mainorder->user->lastname." (".$mainorder->user->email.")" ?>
                                    </td>
                                    <td>
                                        <?php echo $mainorder->order_amount . Config::get('app.currency_symbol');?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($mainorder->order_status == 0) echo '<span style="color:red;">Unpaid</span>';
                                        elseif ($mainorder->order_status == 10) echo '<span style="color:orange;">Pending</span>';
                                        elseif ($mainorder->order_status == 20) echo '<span style="color:green;">Paid</span>';
                                        ?>
                                    </td>
                                    <td>  <?php if (isset($mainorder->invoice->invoice_link)) echo '<a href="' . $mainorder->invoice->invoice_link . '">Download</a>';?></td>
                                    <!--<td><a href="javascript:void(0)" onclick="buyerStopSubscription();" alt="Stop Subscription" title="Stop Subscription"><i class="zmdi zmdi-stop"></i></a></td>-->
                                    <td>
                                        <?php if($mainorder->order_status == 0){ ?>
                                        <button onclick="" class="btn btn-primary btn-sm"><i class="zmdi zmdi-edit"></i>Pay
                                        </button><?php }
                                        if (count($mainorder->orderDetails) > 0) {
                                            $table_data = "<tr id=\"orderDetails-" . $mainorder->id . "\"><td colspan=\"6\"><h4 class='text-left mt-1 mb-1'>Order Details:</h4><table><thead><tr><td>ID</td><td>Page URL</td><td>Promoted URL</td><td>Anchor Text</td><td>Price</td><td>Refund_status</td><td>Refund Amount</td></tr></thead><tbody>";
                                            foreach ($mainorder->orderDetails as $orderdetail) {
                                                $table_data .= "<tr><td>" . $orderdetail->id . "</td><td>" . $orderdetail->page_url . "</td><td>" . $orderdetail->promoted_url . "</td><td>" . $orderdetail->anchor_text . "</td><td>" . $orderdetail->page_price_buyer . "</td><td>" . $orderdetail->refund_status . "</td><td>" . $orderdetail->refund_amount_seller . "</td></tr>";
                                            }
                                            $table_data .= "</td></tr></tbody></table>";
                                        }
                                        ?>
                                        <a href="javascript:void(0)" title="Click for Order Details" id="<?php echo $mainorder->id;?>" pu="{{ $table_data }}" class="btn btn-sm btn-plus btn-expand-col-orderdetails"><i class="zmdi zmdi-plus"></i></a>

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
        <div class="modal fade" id="editOrderDetailPageModal" tabindex="-1" role="dialog" aria-labelledby="editOrderDetailPageModal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form class="card auth_form mb-0" id="editOrderDetail" method="POST">
                        <input type="hidden" id="id" name="id"/>
                        <input type="hidden" id="order_id" name="order_id"/>
                        @csrf

                        <div class="modal-header">
                            <h5 class="modal-title" id="addToCartPageModalLabel">Edit Selected Links</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="cart_items">
                                <div class="form-group">
                                    <label>Your URL</label>
                                    <div style="display: flex;">
                                        <input type="url" class="form-control" name="promoted_url" required id="promoted_url">
                                        <span style="width: 60px;margin-left: 15px"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Anchor</label>
                                    <div style="display: flex;">
                                        <input type="text" class="form-control" name="anchor_text" maxlength="100" onkeyup="charcounter(this,'anchor_text_counter','100');" id="anchor_text">
                                        <span id="anchor_text_counter" class="counter_text">100</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Link Content <i style="color: grey;padding-left: 3px;position: absolute;color:grey;font-size: 13px" class="zmdi zmdi-help-outline" data-toggle="tooltip" data-html="true" title="" data-original-title="<strong>Link Content</strong><br><p>This is how the link will appear on the blog post page. Ensure that the content includes your Anchor, so that we can identify where to insert your URL.</p>"></i></label>
                                    <div style="display: flex;">
                                        <input type="text" class="form-control" name="link_content" maxlength="350" onkeyup="charcounter(this,'link_content','350');" id="link_content">
                                        <span id="link_content_counter" class="counter_text">350</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input type="checkbox" class="" name="no_follow" id="no_follow" value="1">&nbsp;&nbsp;<label>No
                                        Follow</label>
                                </div>
                                <div class="form-group btn_div">
                                    <button type="button" class="btn btn-success frmUpdateOrderDetailSubmitBtn">
                                        <i class="zmdi zmdi-refresh"></i> Update
                                    </button>
                                </div>
                                <div>
                                </div>
                            </div>

                        </div>

                    </form>
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
