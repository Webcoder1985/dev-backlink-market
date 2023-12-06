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
    <link rel="stylesheet" href="{{asset('assets/plugins/jquery-highlight-within-textarea/jquery.highlight-within-textarea.css')}}"/>
@stop
@section('content')
    <div class="row clearfix">
        <div class="col-lg-12">
            @csrf
            <div class="card">
                <h3>Buyer - Links</h3>
                <ul class="nav nav-tabs p-0 mb-3">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#active_orders">Active
                            Links</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#inactive_orders">Inactive
                            Links</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane in active" id="active_orders">
                        <?php if (count($orders_active) > 0){
                            $total_costs = 0;
                            foreach ($orders_active as $order) {
                                if ($order->status == 20) $total_costs += $order->page_price_buyer;
                            }
                            ?>
                        <div class="text-center">Total costs next
                            month: <?= Config::get('app.currency_symbol') . $total_costs ?></div>

                        <div class="table-responsive">
                            <table class="table display responsive nowrap table-hover product_item_list c_table theme-color mb-0" id="activeDatatable">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Order Date</th>
                                    <th>Blog Page</th>
                                    <th>Promoted URL</th>
                                    <th>Anchor</th>
                                    <th>Nofollow</th>
                                    <th>Price</th>
                                    <th>DA</th>
                                    <th>PA</th>
                                    <th>TF</th>
                                    <th>CF</th>
                                    <th>Links</th>
                                    <th>RD</th>
                                    <th>OBL</th>
                                    <th>Country</th>
                                    <th>Language</th>
                                    <th>tld</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $m = 0; foreach ($orders_active as $order) {

                                    ?>

                                <tr id="tr_row_<?php echo $order->id;?>">
                                    <td>
                                            <?php echo $order->id; ?>
                                    </td>
                                    <td>
                                            <?php echo date('d.m.Y', strtotime($order->created_at)); ?>
                                    </td>

                                    <td>
                                        <a href="<?=$order->page_url;?>" target="_blank"><?php echo $order->page_url; ?></a>
                                    </td>
                                    <td>
                                        <a href="<?=$order->promoted_url;?>" target="_blank"><?php echo $order->promoted_url; ?></a>
                                    </td>
                                    <td>
                                            <?php echo $order->anchor_text; ?>
                                    </td>
                                    <td>
                                            <?php echo ($order->no_follow == 0) ? "no" : "yes"; ?>
                                    </td>
                                    <td><?php echo Config::get('app.currency_symbol'); ?><?php echo $order->page_price_buyer; ?></td>
                                    <td><?php echo $order->page->moz_da; ?></td>
                                    <td><?php echo $order->page->moz_pa; ?></td>
                                    <td><?php echo $order->page->maj_tf; ?></td>
                                    <td><?php echo $order->page->maj_cf; ?></td>
                                    <td><?php echo $order->page->maj_bl; ?></td>
                                    <td><?php echo $order->page->rd; ?></td>
                                    <td><?php echo $order->page->obl; ?></td>
                                    <td><?php echo $order->page->country; ?></td>
                                    <td><?php echo $order->page->language; ?></td>
                                    <td><?php echo $order->page->tld; ?></td>
                                    <td>
                                            <?php if ($order->status == 20){ ?>
                                        <a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="buyerStopSubscription(<?=$order->id?>);" alt="Stop" title="Stop"><i class="zmdi zmdi-stop"></i></a>
                                            <?php if ($order->updated_at->addDays(7) < date(now())){ ?>
                                        <button onclick="editOrderDetail(this)" data-id="<?=$order->id?>" data-order-id="<?=$order->order_id?>" data-pu="<?=$order->promoted_url?>" data-bat="<?=$order->link_content;?>" data-at="<?=$order->anchor_text;?>" data-nf="<?=$order->no_follow?>" class="btn btn-primary btn-sm">
                                            <i class='zmdi zmdi-edit'></i></button>
                                        <?php }else { ?>
                                        <button title="One change per 7 days is allowed.&#013;The next change becomes available on:&#013; <?php echo $order->updated_at->addDays(7);?>" disabled class="btn btn-disabled btn-sm disabled">
                                            <i class='zmdi zmdi-edit'></i></button>
                                        <?php }
                                        }
                                            /*elseif($order->status == 10){ ?>
                                            <a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="buyerResumeSubscription(<?=$order->id?>);" alt="Resume" title="Resume"><i class="zmdi zmdi-play"></i></a>

                                            <?php } */
                                            ?>

                                            <?php /*  <a href="javascript:void(0)" id="<?php echo $order->id;?>" pu="<?php echo $order->promoted_url;?>" ant="<?php echo $order->anchor_text;?>" bet="<?php echo $order->content_before_anchor_text;?>" aft="<?php echo $order->content_after_anchor_text;?>" class="btn btn-sm btn-plus btn-expand-col"><i class="zmdi zmdi-plus"></i></a> */ ?>
                                    </td>
                                </tr>


                                <?php } ?>
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

                    <div role="tabpanel" class="tab-pane in" id="inactive_orders">
                        <?php if (count($orders_inactive) > 0){ ?>
                        <div class="text-center">&nbsp;</div>

                        <div class="table-responsive">
                            <table class="table display responsive nowrap table-hover product_item_list c_table theme-color mb-0" id="inactiveDatatable">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Order Date</th>
                                    <th>Blog Page</th>
                                    <th>Promoted URL</th>
                                    <th>Anchor</th>
                                    <th>Nofollow</th>
                                    <th>Price</th>
                                    <th>DA</th>
                                    <th>PA</th>
                                    <th>TF</th>
                                    <th>CF</th>
                                    <th>Links</th>
                                    <th>RD</th>
                                    <th>OBL</th>
                                    <th>Country</th>
                                    <th>Language</th>
                                    <th>tld</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $m = 0; foreach ($orders_inactive as $order) {


                                    ?>

                                <tr id="tr_row_<?php echo $order->id;?>">
                                    <td>
                                            <?php echo $order->id; ?>
                                    </td>
                                    <td>
                                            <?php echo date('d.m.Y', strtotime($order->created_at)); ?>
                                    </td>

                                    <td>
                                        <a href="<?=$order->page_url;?>" target="_blank"><?php echo $order->page_url; ?></a>
                                    </td>
                                    <td>
                                        <a href="<?=$order->promoted_url;?>" target="_blank"><?php echo $order->promoted_url; ?></a>
                                    </td>
                                    <td>
                                            <?php echo $order->anchor_text; ?>
                                    </td>
                                    <td>
                                            <?php echo ($order->no_follow == 0) ? "no" : "yes"; ?>
                                    </td>
                                    <td><?php echo Config::get('app.currency_symbol'); ?><?php echo $order->page_price_seller; ?></td>
                                    <td><?php echo $order->page->moz_da; ?></td>
                                    <td><?php echo $order->page->moz_pa; ?></td>
                                    <td><?php echo $order->page->maj_tf; ?></td>
                                    <td><?php echo $order->page->maj_cf; ?></td>
                                    <td><?php echo $order->page->maj_bl; ?></td>
                                    <td><?php echo $order->page->rd; ?></td>
                                    <td><?php echo $order->page->obl; ?></td>
                                    <td><?php echo $order->page->country; ?></td>
                                    <td><?php echo $order->page->language; ?></td>
                                    <td><?php echo $order->page->tld; ?></td>
                                    <td>
                                        <button onclick="AddToCart(this)" data-page="<?=$order->page_id?>" data-price="<?=$order->page->page_price_buyer?>" data-id="<?=$order->id?>" data-order-id="<?=$order->order_id?>" data-pu="<?=$order->promoted_url?>" data-bat="<?=$order->link_content;?>" data-at="<?=$order->anchor_text;?>" data-nf="<?=$order->no_follow?>" class="btn btn-primary btn-sm AddtoCartBtn">
                                            <i class='zmdi zmdi-shopping-cart-plus' style="margin-right: 5px;margin-left: -5px;"></i>Add
                                            to Cart
                                        </button>
                                            <?php /*  <a href="javascript:void(0)" id="<?php echo $order->id;?>" pu="<?php echo $order->promoted_url;?>" ant="<?php echo $order->anchor_text;?>" bet="<?php echo $order->content_before_anchor_text;?>" aft="<?php echo $order->content_after_anchor_text;?>" class="btn btn-sm btn-plus btn-expand-col"><i class="zmdi zmdi-plus"></i></a> */ ?>
                                    </td>
                                </tr>


                                    <?php $m++;
                                } ?>

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
                                    <label>Your
                                        URL<i style="color: grey;padding-left: 3px;position: absolute;color:grey;font-size: 13px" class="zmdi zmdi-help-outline" data-toggle="tooltip" data-html="true" title="" data-original-title="<strong>Your URL</strong><br><p>Please enter the full URL including 'https://'</p>"></i></label>

                                    <div style="display: flex;">
                                        <input type="url" class="form-control" name="promoted_url" required id="promoted_url">
                                        <span style="width: 60px;margin-left: 15px"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Anchor<i style="color: grey;padding-left: 3px;position: absolute;color:grey;font-size: 13px" class="zmdi zmdi-help-outline" data-toggle="tooltip" data-html="true" title="" data-original-title="<strong>Anchor</strong><br><p>This is the visible, clickable text in a link. Use your targeted keyword that you want to rank for.</p>"></i></label>
                                    <div style="display: flex;">
                                        <input type="text" class="form-control" name="anchor_text" maxlength="60" onkeyup="charcounter(this,'anchor_text_counter','60');$('#link_content').highlightWithinTextarea({highlight: this.value});check_anchor();" id="anchor_text">
                                        <span id="anchor_text_counter" class="counter_text">60</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Link Content
                                        <i style="color: grey;padding-left: 3px;position: absolute;color:grey;font-size: 13px" class="zmdi zmdi-help-outline" data-toggle="tooltip" data-html="true" title="" data-original-title="<strong>Link Content</strong><br><p>This is how the link will appear on the blog post page. Ensure that the content includes your Anchor, so that we can identify where to insert your URL.</p>"></i></label>
                                    <small class="ml-3">(Content must include your anchor text)</small>
                                    <div style="display: flex;">

                                        <textarea rows="4" type="text" class="" name="link_content" maxlength="350" onkeyup="charcounter(this,'link_content_counter','350');check_anchor();" id="link_content"></textarea>
                                    </div>
                                    <span id="anchor_check_span_success" class="mt-1"><i id="anchor_check_icon" class="zmdi zmdi-check text-green"></i> Anchor Found!</span>
                                    <span id="anchor_check_span_failed" class="mt-1"><i id="anchor_check_icon" class="zmdi zmdi-alert-triangle animated infinite wobble text-red"></i> Anchor Missing!</span>
                                    <span id="link_content_counter" class="counter_text mt-1">350</span>
                                </div>

                                <div class="form-group">
                                    <input type="checkbox" class="" name="no_follow" value="1" id="no_follow">
                                    <label>Make link No-Follow
                                        <i style="color: grey;padding-left: 3px;position: absolute;color:grey;font-size: 13px" class="zmdi zmdi-help-outline" data-toggle="tooltip" data-html="true" title="" data-original-title="<strong>No-Follow Attribut</strong><br><p>Check the box if you want to add the rel='nofollow' attribute to your link. If you are unsure of what it is, leave it unchecked.</p>"></i></label>

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
        <div class="modal fade" id="addToCartPageModal" tabindex="-1" role="dialog" aria-labelledby="addToCartPageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form class="card auth_form mb-0" id="addToCart" name="addToCart" method="POST">
                        @csrf

                        <div class="modal-header">
                            <h5 class="modal-title" id="addToCartPageModalLabel">Buy Selected Links</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="cart_items">

                                <div class="form-group">
                                    <label>Your URL
                                        <i style="color: grey;padding-left: 3px;position: absolute;color:grey;font-size: 13px" class="zmdi zmdi-help-outline" data-toggle="tooltip" data-html="true" title="" data-original-title="<strong>Your URL</strong><br><p>Please enter the full URL including 'https://'</p>"></i></label>
                                    <div id="url_div" style="display: flex;">
                                        <input type="hidden" name="product_id" id="product_id" value="">
                                        <input type="hidden" name="price" id="price" value="">
                                        <input type="url" class="form-control" name="promoted_url" required id="promoted_url">
                                        <div style="width: auto;">
                                        </div>
                                        <span style="width: 60px;margin-left: 15px"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Anchor
                                        <i style="color: grey;padding-left: 3px;position: absolute;color:grey;font-size: 13px" class="zmdi zmdi-help-outline" data-toggle="tooltip" data-html="true" title="" data-original-title="<strong>Anchor</strong><br><p>This is the visible, clickable text in a link. Use your targeted keyword that you want to rank for.</p>"></i></label>
                                    <div id="anchor_div" style="display: flex;">
                                        <input type="text" class="form-control" name="anchor_text" maxlength="60" onkeyup="charcounter(this,'addToCartPageModal #anchor_text_counter','60');$('#link_content').highlightWithinTextarea({highlight: this.value});check_anchor('#addToCartPageModal');" id="anchor_text">
                                        <span id="anchor_text_counter" class="counter_text">60</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Link Content
                                        <i style="color: grey;padding-left: 3px;position: absolute;color:grey;font-size: 13px" class="zmdi zmdi-help-outline" data-toggle="tooltip" data-html="true" title="" data-original-title="<strong>Link Content</strong><br><p>This is how the link will appear on the blog post page. Ensure that the content includes your Anchor, so that we can identify where to insert your URL.</p>"></i></label>
                                    <small class="ml-3">(Content must include your anchor text)</small>
                                    <div style="display: flex;">

                                        <textarea rows="4" type="text" class="" name="link_content" maxlength="350" onkeyup="charcounter(this,'addToCartPageModal #link_content_counter','350');check_anchor('#addToCartPageModal');" id="link_content"></textarea>
                                    </div>
                                    <span id="anchor_check_span_success" class="mt-1"><i id="anchor_check_icon" class="zmdi zmdi-check text-green"></i> Anchor Found!</span>
                                    <span id="anchor_check_span_failed" class="mt-1"><i id="anchor_check_icon" class="zmdi zmdi-alert-triangle animated infinite wobble text-red"></i> Anchor Missing!</span>
                                    <span id="link_content_counter" class="counter_text mt-1">350</span>
                                </div>

                                <div class="form-group">
                                    <input type="checkbox" class="" name="no_follow" value="Yes">&nbsp;&nbsp;
                                    <label>Make link No-Follow
                                        <i style="color: grey;padding-left: 3px;position: absolute;color:grey;font-size: 13px" class="zmdi zmdi-help-outline" data-toggle="tooltip" data-html="true" title="" data-original-title="<strong>No-Follow Attribut</strong><br><p>Check the box if you want to add the rel='nofollow' attribute to your link. If you are unsure of what it is, leave it unchecked.</p>"></i></label>

                                </div>
                                <div class="form-group">
                                    <h3>1 Link for <?php echo Config::get('app.currency_symbol'); ?>
                                        <span id="cart_price"></span></h3>
                                </div>
                                <div class="form-group btn_div">
                                    <button type="button" class="btn btn-success frmAddtoCartSubmitBtn">
                                        <i class="zmdi zmdi-shopping-cart"></i> Add to Cart
                                    </button>
                                </div>
                                <div>
                                </div>
                            </div>
                            <div class="cart_success" style="display:none;">
                                <i class="zmdi zmdi-check"></i>

                                <h2>Item added to your Cart.</h2>
                                <a href="{{ url('/cart') }}" class="btn btn btn-secondary">
                                    Show cart
                                </a>
                                <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-success">
                                    Continue shopping
                                </a>
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
            <script src="{{asset('assets/plugins/jquery-highlight-within-textarea/jquery.highlight-within-textarea.js')}}"></script>
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
                        autoWidth: false,
                    });
                    $('#inactiveDatatable').DataTable({
                        order: [[0, 'desc']],
                        stateSave: true,
                        responsive: true,
                        autoWidth: false,
                    });


                });
            </script>

@stop
