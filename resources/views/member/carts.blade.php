@extends('layout.master')
@section('title', 'Shopping Cart')
@section('parentPageTitle', 'Tables')
@section('page-style')
    <link rel="stylesheet" href="{{asset('assets/plugins/jquery-datatable/dataTables.bootstrap4.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/plugins/jquery-datatable/responsive.dataTables.min.css')}}"/>
    <!-- noUISlider Css -->
    <link rel="stylesheet" href="{{asset('assets/plugins/nouislider/nouislider.min.css')}}"/>
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('assets/plugins/select2/select2.css')}}"/>
     <link rel="stylesheet" href="{{asset('assets/plugins/jquery-highlight-within-textarea/jquery.highlight-within-textarea.css')}}"/>
    <style>
			.hwt-container {
				background-color: inherit;
			}

			.hwt-content {
				width: 680px;
				height: 100px;
				padding: 5px;
				border: 1px solid #adb5bd;
				color: inherit;
			}

			.hwt-input:focus {
				outline-color: #495057;
			}

			.hwt-content mark {
				border-radius: 3px;
				background-color: #c1ffbf;
			}

		</style>
@stop
@section('content')
    <div class="row clearfix">
        <div class="col-lg-12">
            <form class="card auth_form mb-0" id="placeorder" method="POST">
                @csrf
                <div class="card">
                    <?php if (count($carts) > 0){ ?>
                    <div class="table-responsive">
                        <table class="table display responsive nowrap table-hover product_item_list c_table theme-color mb-0" id="usersDatatable">
                            <thead>
                            <tr>
                                <th>Title</th>
                                <th>Price (each month)</th>
                                <th>Price (this month) **</th>
                                <th class="text-center">Action</th>

                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                $toal_this_month = 0;
                                $m = 1; foreach ($carts as $row) :
                                $pr_current_price = ($row->price / $total_days) * $left_days;
                                $pr_current_price = round($pr_current_price, 2,PHP_ROUND_HALF_UP);
                                $toal_this_month += $pr_current_price;
                                ?>

                            <tr>
                                <td>
                                    <p><strong><?php echo $row->name; ?></strong></p>
                                    <button onclick="editcartitem('<?php echo $row->id;?>');" class="btn btn-success btn-sm mt-0 float-right" type="button">
                                        <i class="zmdi zmdi-edit"></i></button>
                                    <p style="color: grey;">
                                    <strong>Promoted URL:</strong> <?php echo $row->options->promoted_url; ?><br/>
                                    <strong>Anchor:</strong> <?php echo $row->options->anchor_text; ?><br/>
                                        <?php if (is_null($row->options->link_content) === false) echo "<strong>Link Content:</strong> " . $row->options->link_content . "<br/>"; ?>

                                        <?php if ($row->options->no_follow == 1) echo "NoFollow"; ?>
                                    </p>

                                    <input type="hidden" name="cart_id[]" value="<?php echo $row->rowId; ?>" id="cart_id_<?php echo $row->id; ?>"/>
                                    <input type="hidden" name="price[]" value="<?php echo $row->price; ?>" id="price_<?php echo $row->id; ?>"/>
                                    <input type="hidden" name="current_price[]" value="<?php echo $pr_current_price; ?>" id="current_price_<?php echo $row->id; ?>"/>
                                    <input type="hidden" name="product_id[]" value="<?php echo $row->id; ?>" id="product_id_<?php echo $row->id; ?>"/>
                                    <input type="hidden" name="promoted_url[]" value="<?php echo $row->options->promoted_url; ?>" id="promoted_url_<?php echo $row->id; ?>"/>
                                    <input type="hidden" name="link_content[]" value="<?php echo $row->options->link_content; ?>" id="link_content_<?php echo $row->id; ?>"/>
                                    <input type="hidden" name="anchor_text[]" value="<?php echo $row->options->anchor_text; ?>" id="anchor_text_<?php echo $row->id; ?>"/>
                                    <input type="hidden" name="no_follow[]" value="<?php echo $row->options->no_follow; ?>" id="no_follow_<?php echo $row->id; ?>"/>
                                </td>
                                <td><?php echo Config::get('app.currency_symbol'); ?><?php echo number_format($row->price,2); ?></td>
                                <td><?php echo Config::get('app.currency_symbol'); ?><?php echo $pr_current_price; ?></td>
                                <td>
                                    <button onclick="deletecartitem('<?php echo $row->rowId;?>');" class="btn btn-danger btn-sm" type="button">
                                        <i class="zmdi zmdi-delete"></i></button>

                                </td>

                            </tr>

                                <?php $m++; endforeach; ?>

                            </tbody>
                            <tfoot>

                            <tr class="font-weight-bold font-16">
                                <td></td>

                                <td colspan="2" class="text-right">Total (each month)</td>
                                <td class="text-left"><?php echo Config::get('app.currency_symbol'); ?><?php echo \Cart::total(); ?></td>
                            </tr>

                            <tr>
                                <td>&nbsp;</td>

                                <td colspan="2" class="text-right"><strong>Subtotal (this month)*</strong></td>
                                <td class="text-right">
                                    <strong><?php echo Config::get('app.currency_symbol'); ?><?= $toal_this_month ?></strong>
                                </td>
                            </tr>
                            </tfoot>
                        </table>

                        <div class="text-right">
                            <button type="submit" class="btn btn-success frmPlaceOrder">
                                <i class="zmdi zmdi-settings zmdi-hc-spin" style="display: none;"></i> Pay
                                Now <?php echo Config::get('app.currency_symbol'); ?><?= $toal_this_month ?></button>
                        </div>


                    </div>
                    <div class="float-right mt-xl-4" style="margin-bottom: -20px;">
                        <div class="row">
                            <div class="text-right pr-0">
                                <span class="font-weight-bold " style="padding-left: 6px;">*</span></div>
                            <div class="text-left pl-1">
                                <span class="font-italic"> Taxes may apply at checkout.</span></div>
                        </div>
                        <div class="row">
                            <div class="text-right pr-0"><span class="font-weight-bold">**</span></div>
                            <div class="text-left pl-1"><span class="font-italic">  Price for the remaining days in this month.<br>You will be charged the monthly price on the first day of each month.</span>
                            </div>
                        </div>
                    </div>
                    <?php }
                    else {
                        ?>
                    <center><h3>Your cart is empty.</h3></center>
                        <?php
                    }
                    ?>
                </div>
            </form>
        </div>
    </div>
@stop
<div class="modal fade" id="addToCartPageModal" tabindex="-1" role="dialog" aria-labelledby="addToCartPageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form class="card auth_form mb-0" id="addToCart" method="POST">
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
                            <label>Your URL<i style="color: grey;padding-left: 3px;position: absolute;color:grey;font-size: 13px" class="zmdi zmdi-help-outline" data-toggle="tooltip" data-html="true" title="" data-original-title="<strong>Your URL</strong><br><p>Please enter the full URL including 'https://'</p>"></i></label>

                            <div style="display: flex;">
                                <input type="hidden" name="product_id" id="product_id" value="">
                                <input type="hidden" name="rowId" id="cart_id" value="">
                                <input type="hidden" name="price" id="price" value="">
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

                                <textarea rows="4" type="text" class="" style="padding:5px;" name="link_content" maxlength="350" onkeyup="charcounter(this,'link_content_counter','350');check_anchor();" id="link_content"></textarea>
                            </div>
                            <span id="anchor_check_span_success" class="mt-1"><i id="anchor_check_icon" class="zmdi zmdi-check text-green"></i> Anchor Found!</span>
                            <span id="anchor_check_span_failed" class="mt-1"><i id="anchor_check_icon" class="zmdi zmdi-alert-triangle animated infinite wobble text-red"></i> Anchor Missing!</span>
                            <span id="link_content_counter" class="counter_text mt-1">350</span>
                        </div>
                        <div class="form-group">
                            <input type="checkbox" class="" name="no_follow" value="Yes" id="no_follow">
                             <label>Make link No-Follow <i style="color: grey;padding-left: 3px;position: absolute;color:grey;font-size: 13px" class="zmdi zmdi-help-outline" data-toggle="tooltip" data-html="true" title="" data-original-title="<strong>No-Follow Attribut</strong><br><p>Check the box if you want to add the rel='nofollow' attribute to your link. If you are unsure of what it is, leave it unchecked.</p>"></i></label>

                        </div>
                        <div class="form-group">
                            <h3>1 links for <?php echo Config::get('app.currency_symbol'); ?>
                                <span id="cart_price"></span></h3>
                        </div>
                        <div class="form-group btn_div">
                            <button type="button" class="btn btn-success frmAddtoCartSubmitBtn">
                                <i class="zmdi zmdi-shopping-cart"></i> Update Cart
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
                        <a href="javascript:void(0)" onclick="location.reload();" data-dismiss="modal" class="btn btn-success">
                            Continue shopping
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@section('page-script')
    <script>
        var base_url = '{{url("/")}}';
    </script>
    <script src="{{asset('assets/plugins/jquery-highlight-within-textarea/jquery.highlight-within-textarea.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/select2.min.js')}}"></script> <!-- Select2 Js -->
    <script src="{{asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.js')}}"></script> <!-- noUISlider Plugin Js -->
    <script src="{{asset('assets/plugins/sweetalert/sweetalert.min.js')}}"></script> <!-- Sweetalert Js -->
    <script src="{{asset('assets/js/pages/cart.js')}}"></script>

@stop
