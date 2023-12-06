@extends('layout.master')
@section('title', 'Marketplace')
@section('parentPageTitle', 'Marketplace')
@section('page-style')
    <link rel="stylesheet" href="{{asset('assets/plugins/jquery-datatable/dataTables.bootstrap4.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/plugins/jquery-datatable/responsive.dataTables.min.css')}}"/>
    <!-- noUISlider Css -->
    <link rel="stylesheet" href="{{asset('assets/plugins/ion-rangeslider/css/ion.rangeSlider.css')}}"/>
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('assets/plugins/select2/select2.css')}}"/>
    <!-- Sweetalert -->
    <link rel="stylesheet" href="{{asset('assets/plugins/sweetalert/sweetalert.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/plugins/searchable-option-list/sol.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/autocomplete.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/plugins/jquery-ui-1.13.1.custom/jquery-ui.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/plugins/jquery-highlight-within-textarea/jquery.highlight-within-textarea.css')}}"/>


    <link rel="stylesheet" href="https://cdn.datatables.net/colreorder/1.5.6/css/colReorder.dataTables.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.dataTables.min.css"/>

@stop

@section('content')
  <style>

  </style>
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <h3 class="mb-0">Marketplace</h3>
                <div class="header pt-0">
                    <div class="row">
                        <div class="col-6 pt-4">
                            <h2><strong>Filters</strong></h2>
                        </div>
                    </div>
                </div>
                <div class="body">
                    <form method="get" action="" autocomplete="off">
                        <div class="row clearfix mb-4">

                            <div class="col-md-3 col-sm-6">
                            <div class="irs-demo">
                                <p><b>Maj. TF</b><i
                                        style="color: grey;padding-left: 3px;position: absolute;color:grey;font-size: 13px"
                                        data-toggle="tooltip" data-html="true" title=""
                                        data-original-title="<strong>Majestic TF</strong><br><p><strong>TF</strong> stands for <strong>T</strong>rust <strong>F</strong>low.<br>This number reflects the quality of the websites linking to the site, measuring the trustworthiness of a page. Trust Flow scores range from 0-100. Where 100 is best. </p>"
                                        class="zmdi zmdi-info-outline"></i></p>
                                <input type="text" id="nouislider_tf_range" value=""/>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="irs-demo">
                                <p><b>Maj. CF</b><i
                                        style="color: grey;padding-left: 3px;position: absolute;color:grey;font-size: 13px"
                                        class="zmdi zmdi-info-outline"
                                        data-toggle="tooltip" data-html="true" title=""
                                        data-original-title="<strong>Majestic CF</strong><br><p><strong>CF</strong> stands for <strong>C</strong>itation <strong>F</strong>low.<br>It reflects the amount of “link juice” that a site has. CF is largely a reflection of the volume of links. CF scores range from 0-100. Where 100 is best. </p>"
                                    ></i></p>
                                <input type="text" id="nouislider_cf_range" value=""/>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="irs-demo">
                                <p><b>Maj. Backlinks</b><i
                                        style="color: grey;padding-left: 3px;position: absolute;color:grey;font-size: 13px"
                                        data-toggle="tooltip" data-html="true" title=""
                                        data-original-title="<strong>Majestic Backlinks</strong><br><p>Number of backlinks linking to a page measured by Majestic. A backlink is an incoming link from a different website. </p>"
                                        class="zmdi zmdi-info-outline"></i></p>
                                <input type="text" id="nouislider_bl_range" value=""/>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="irs-demo">
                                <p><b>Maj. RD</b><i
                                        style="color: grey;padding-left: 3px;position: absolute;color:grey;font-size: 13px"
                                        class="zmdi zmdi-info-outline"
                                        data-toggle="tooltip" data-html="true" title=""
                                        data-original-title="<strong>Majestic RD</strong><br><p><strong>RD</strong> stands for <strong>R</strong>eferring <strong>D</strong>omains.<br>Number of domains linking to a page measured by Majestic.</p>">
                                    </i></p>
                                <input type="text" id="nouislider_rd_range" value=""/>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix mb-4">
                        <div class="col-md-3 col-sm-6 mb-sm-4">
                            <div class="irs-demo">
                                <p><b>Moz DA</b><i
                                        style="color: grey;padding-left: 3px;position: absolute;color:grey;font-size: 13px"
                                        class="zmdi zmdi-info-outline"
                                        data-toggle="tooltip" data-html="true" title=""
                                        data-original-title="<strong>Moz DA</strong><br><p><strong>DA</strong> stands for <strong>D</strong>omain <strong>A</strong>uthority.<br>Domain Authority (DA) is a score developed by Moz.com that predicts how well a certain <strong>domain</strong> will rank on search engines. Domain Authority scores range from 0-100. Where 100 is best.</p>">
                                    </i></p>
                                <input type="text" id="nouislider_da_range" value=""/>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-sm-4">
                            <div class="irs-demo">
                                <p><b>Moz PA</b><i
                                        style="color: grey;padding-left: 3px;position: absolute;color:grey;font-size: 13px"
                                        class="zmdi zmdi-info-outline"
                                        data-toggle="tooltip" data-html="true" title=""
                                        data-original-title="<strong>Moz PA</strong><br><p><strong>PA</strong> stands for <strong>P</strong>age <strong>A</strong>uthority.<br>Page Authority (PA) is a score developed by Moz.com that predicts how well a specific <strong>page</strong> will rank on search engines. Page Authority scores range from 0-100. Where 100 is best.</p>">
                                    </i></p>
                                <input type="text" id="nouislider_pa_range" value=""/>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-4">
                            <div class="irs-demo">
                                <p><b>Price</b></p>
                                <input type="text" id="nouislider_price_range" value=""/>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="irs-demo">
                                <p><b>OBL</b><i
                                        style="color: grey;padding-left: 3px;position: absolute;color:grey;font-size: 13px"
                                        class="zmdi zmdi-info-outline"
                                        data-toggle="tooltip" data-html="true" title=""
                                        data-original-title="<strong>OBL</strong><br><p><strong>OBL</strong> stands for <strong>O</strong>ut<strong>B</strong>ound <strong>L</strong>inks.<br>OBL represents the number of all links pointing away from a page.The less the better.</p>">
                                    </i></p>
                                <input type="text" id="nouislider_obl_range" value=""/>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-6 col-sm-12 mb-sm-4">
                          <div class="row">
                            <div class="col-md-4 col-sm-4 mb-sm-4 search-keyword-box">
                                <p> <b>Search for Keyword</b> </p>
                                <input type="text" name="search_keyword" id="search-keyword" class="form-control " />
                                <select class="form-control show-tick search-keyword-option" id="search-keyword-option" >
                                    <option value="title" selected>Title</option>
                                    <option value="post">Post</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-4 mb-sm-4">
                                <p> <b>Country</b> </p>
                                <select class="form-control show-tick ms select2 countries" multiple data-placeholder="Select Country">
                                    <option value="all">All</option>
                                    @foreach($countries as $country)
                                    <option>{{$country->country}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-4 mb-sm-4">
                                <p> <b>Language</b> </p>
                                <select class="form-control show-tick ms select2 languages" multiple data-placeholder="Select Language">
                                    <option value="all">All</option>
                                    @foreach($languages as $language)
                                        <option>{{$language->language}}</option>
                                    @endforeach
                                </select>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-3 col-sm-6 category-selection">
                            <p><b>Category</b></p>
                            <select id="filter-categories" name="filter-categories" class="categories" multiple="multiple" data-placeholder="Select Category">
                                <option value="all">All</option>
                                @foreach($categories as $category)

                                    <optgroup label="{{ $category->title }}">
                                        @foreach($category->children as $child_category)
                                            @if(count($child_category->children)>0)
                                                @foreach($child_category->children as $child_category1)
                                                    @if(count($child_category1->children)>0)
                                                        @foreach($child_category1->children as $child_category2)
                                                            <option value="{{ $child_category2->level }}">{{ $child_category2->title }}
                                                                ({{ isset($category_counts_array[$child_category2->level])?$category_counts_array[$child_category2->level]:0 }}
                                                                )
                                                            </option>
                                                        @endforeach
                                                    @else
                                                        <option value="{{ $child_category1->level }}">{{ $child_category1->title }}
                                                            ({{ isset($category_counts_array[$child_category1->level])?$category_counts_array[$child_category1->level]:0 }}
                                                            )
                                                        </option>
                                                    @endif
                                          @endforeach
                                        @else
                                          <option value="{{ $child_category->level }}">{{ $child_category->title }} ({{ isset($category_counts_array[$child_category->level])?$category_counts_array[$child_category->level]:0 }})</option>
                                        @endif
                                      @endforeach
                                  </optgroup>
                              @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <p> <b>TLD</b> </p>
                            <select class="form-control show-tick ms select2 tlds" multiple data-placeholder="Select TLD">
                                <option value="all">All</option>
                                @foreach($tlds as $tld)
                                <option>{{$tld->tld}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                  </form>
                  <div class="row clearfix">
                      <div class="col-md-12 col-sm-12">
                          <button class="btn btn-primary float-right" id="reset-filter">Reset Filter</button>
                      </div>
                  </div>
                </div>


                <div class="row">
                    <div class="col-lg-12 table_main">
                        <div class="card">
                            <div class="table-responsive">
                                <table
                                    class="table display responsive nowrap table-hover product_item_list c_table theme-color mb-0"
                                id="blogDatatable">
                                <thead>
                                <tr>
                                    <th>URL</th>
                                    <th>DA (MOZ)</th>
                                    <th>PA (MOZ)</th>
                                    <th>TF (Maj.)</th>
                                    <th>CF (Maj.)</th>
                                    <th>Links (Maj.)</th>
                                    <th>RD (Maj.)</th>
                                    <th>Obl</th>
                                    <th>Country</th>
                                    <th>Language</th>
                                    <th>tld</th>
                                    <th>Category</th>
                                    {{-- <th>Is Active?</th>--}}
                                    <th>Price</th>
                                    <th>Actions</th>
                                    <th>Title</th>
                                    <th>Content</th>
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


            <div class="modal fade" id="addToCartPageModal" tabindex="-1" role="dialog"
                 aria-labelledby="addToCartPageModalLabel" aria-hidden="true">
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
                                        <label>Your URL <i style="color: grey;padding-left: 3px;position: absolute;color:grey;font-size: 13px" class="zmdi zmdi-help-outline" data-toggle="tooltip" data-html="true" title="" data-original-title="<strong>Your URL</strong><br><p>Please enter the full URL including 'https://'</p>"></i></label>
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
                                        <label>Anchor <i style="color: grey;padding-left: 3px;position: absolute;color:grey;font-size: 13px" class="zmdi zmdi-help-outline" data-toggle="tooltip" data-html="true" title="" data-original-title="<strong>Anchor</strong><br><p>This is the visible, clickable text in a link. Use your targeted keyword that you want to rank for.</p>"></i></label>
                                        <div id="anchor_div" style="display: flex;">
                                            <input type="text" class="form-control" name="anchor_text" maxlength="60" onkeyup="charcounter(this,'anchor_text_counter','60');$('#link_content').highlightWithinTextarea({highlight: this.value});check_anchor();"   id="anchor_text">
                                            <span id="anchor_text_counter" class="counter_text">60</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Content Keyword <i style="color: grey;padding-left: 3px;position: absolute;color:grey;font-size: 13px" class="zmdi zmdi-help-outline" data-toggle="tooltip" data-html="true" title="" data-original-title="<strong>Content Keyword</strong><br><p>We utilize this to create related content for your link. Enter 1-3 keywords from your niche. Click the 'Create Content' button once you have filled it out.</p>"></i></label>
                                         <div id="content_keyword_div" style="display: flex;">

                                         <input placeholder="" type="text" class="form-control" name="content_keyword" maxlength="50" onkeyup="charcounter(this,'content_keyword_counter','50');" id="content_keyword">
                                         <span id="content_keyword_counter" class="counter_text">50</span>
                                         </div>
                                         <div id="content_keyword_btn_div" style="display: flex;margin-top: -10px;margin-bottom: 10px;margin-left: -1px;">
                                             <button type="button" class="btn btn-success frmGenerateContentBtn"> <i class="zmdi zmdi-edit mr-2"></i>Create Content</button>
                                         </div>
                                         <label>Link Content <i style="color: grey;padding-left: 3px;position: absolute;color:grey;font-size: 13px" class="zmdi zmdi-help-outline" data-toggle="tooltip" data-html="true" title="" data-original-title="<strong>Link Content</strong><br><p>This is how the link will appear on the blog post page. Ensure that the content includes your Anchor, so that we can identify where to insert your URL.</p>"></i></label>
                                        <small class="ml-3">(Content must include your anchor text)</small>
                                        <div style="display: flex;">

                                            <textarea rows="4" type="text" class="" name="link_content" maxlength="350" onkeyup="charcounter(this,'link_content_counter','350');check_anchor();" id="link_content"></textarea>
                                         </div>
                                          <span id="anchor_check_span_success" class="mt-1"><i id="anchor_check_icon" class="zmdi zmdi-check text-green"></i> Anchor Found!</span>
                                           <span id="anchor_check_span_failed" class="mt-1"><i id="anchor_check_icon" class="zmdi zmdi-alert-triangle animated infinite wobble text-red"></i> Anchor Missing!</span>
                                          <span id="link_content_counter" class="counter_text mt-1">350</span>
                                </div>

                                <div class="form-group">
                                    <input type="checkbox" class="" name="no_follow" value="Yes">&nbsp;&nbsp;
                                    <label>Make link No-Follow <i style="color: grey;padding-left: 3px;position: absolute;color:grey;font-size: 13px" class="zmdi zmdi-help-outline" data-toggle="tooltip" data-html="true" title="" data-original-title="<strong>No-Follow Attribut</strong><br><p>Check the box if you want to add the rel='nofollow' attribute to your link. If you are unsure of what it is, leave it unchecked.</p>"></i></label>

                                </div>
                                <div class="form-group">
                                    <h3>1 Link for <?php echo Config::get('app.currency_symbol');?>
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
            <script>
                $(function () {
                    $('[data-toggle="tooltip"]').tooltip()
                })
                var da_min = {{$da_min}};
                var da_max = {{$da_max}};
                var pa_min = {{$pa_min}};
                var pa_max = {{$pa_max}};
                var tf_min = {{$tf_min}};
                var tf_max = {{$tf_max}};
                var cf_min = {{$cf_min}};
                var cf_max = {{$cf_max}};
                var bl_min = {{$bl_min}};
                var bl_max = {{$bl_max}};
                var price_min = {{$price_min}};
                var price_max = {{$price_max}};
                var obl_min = {{$obl_min}};
                var obl_max = {{$obl_max}};
                var rd_min = {{$rd_min}};
                var rd_max = {{$rd_max}};
                var base_url = '{{url("/")}}';
                var hideOptions = {{(Auth::user()->user_status != 3) ? 1 : 0}};
            </script>
            <script src="{{asset('assets/bundles/datatablescripts.bundle.js')}}"></script>
            <script src="{{asset('assets/plugins/jquery-datatable/dataTables.responsive.min.js')}}"></script>
            <script src="{{asset('assets/plugins/jquery-datatable/buttons/dataTables.buttons.min.js')}}"></script>
            <script src="{{asset('assets/plugins/jquery-datatable/buttons/buttons.bootstrap4.min.js')}}"></script>
            <script src="{{asset('assets/plugins/jquery-datatable/buttons/buttons.colVis.min.js')}}"></script>
            <script src="{{asset('assets/plugins/jquery-datatable/buttons/buttons.flash.min.js')}}"></script>
            <script src="{{asset('assets/plugins/jquery-datatable/buttons/buttons.html5.min.js')}}"></script>
            <script src="{{asset('assets/plugins/jquery-highlight-within-textarea/jquery.highlight-within-textarea.js')}}"></script>
                <script src="{{asset('assets/plugins/jquery-datatable/buttons/buttons.print.min.js')}}"></script>


                <script src="{{asset('assets/plugins/select2/select2.min.js')}}"></script> <!-- Select2 Js -->
                <script src="{{asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.js')}}"></script>
                <!-- noUISlider Plugin Js -->
                <script src="{{asset('assets/plugins/sweetalert/sweetalert.min.js')}}"></script> <!-- Sweetalert Js -->
                <script src="{{asset('assets/js/pages/tables/jquery-datatable.js')}}"></script>
                <script src="{{asset('assets/js/pages/marketplace.js')}}"></script>
                <script src="{{asset('assets/plugins/searchable-option-list/sol.js')}}"></script>
                <script src="{{asset('assets/plugins/jquery-ui-1.13.1.custom/jquery-ui.min.js')}}"></script>
                <script src="https://cdn.datatables.net/colreorder/1.5.6/js/dataTables.colReorder.min.js"></script>
                <script src="https://cdn.datatables.net/fixedheader/3.2.3/js/dataTables.fixedHeader.min.js"></script>
                <script type="text/javascript">
                    $(function () {
                        // initialize sol
                        //$('#filter-categories').val('1.1.10');
                        //console.log($('#blogDatatable').DataTable().state());
                        /*$('#filter-categories').searchableOptionList({
                            "showSelectionBelowList": true,
                            events: {
                                onInitialized: function () {

                                    $('.sol-optiongroup-label').each(function () {

                                        $(this).siblings().toggleClass("auto-hide");
                              })
                            },
                          }
                        });*/

                        $("#filter-categories").change(function () {
                            drawDatatable();
                        });
                        $("#search-keyword").change(function () {
                            drawDatatable();
                        });
                        $("#search-keyword-option").change(function () {
                          if($("#search-keyword").val()!="")
                            drawDatatable();
                        });

                    });
                </script>
                <style>
                    .category-selection .dropdown-toggle {
                        display: none;
                    }

                    .category-selection .categories {
                        width: 100%;
                    }
                </style>
                <script type="text/javascript">

                    $(function () {

                        var availableTags1 =  <?php echo json_encode($my_anchors); ?>;
                        var inputanchors = $('#anchor_text').autocomplete({
                            maxShowItems: 20,
                            source: availableTags1,
                            appendTo: "#anchor_div",
                            minLength: 0
                        }).focus(function () {
                            if ($('#anchor_text').val().length > 0) {
                                $('#anchor_text').autocomplete("search", $('#anchor_text').val());
                            } else {
                                $('#anchor_text').autocomplete("search", '');
                            }

                        });
                        var availableTags2 =  <?php echo json_encode($my_urls); ?>;
                        var inputanchors = $('#promoted_url').autocomplete({
                            maxShowItems: 20,
                            source: availableTags2,
                            appendTo: "#url_div",
                            minLength: 0
                        }).focus(function () {
                            if ($('#promoted_url').val().length > 0) {
                                $('#promoted_url').autocomplete("search", $('#promoted_url').val());
                            } else {
                                $('#promoted_url').autocomplete("search", '');
                            }

                        });

                    });


                </script>
                <script type="text/javascript">
                    $("body").on('click', '.sol-optiongroup-label', function () {
                        $(this).siblings().toggle();
                    })
                    $(function () {
                      /*$('.sol-optiongroup-label').each(function () {
                          $(this).siblings().toggle();
                      })*/
                      $('body').on('keyup','.sol-input-container input[type=text]',function(){
                        if(this.value!=''){
                          $('.sol-option').removeClass("auto-hide");
                        }
                        else{
                          $('.sol-option').addClass("auto-hide");
                        }
                      });
                    });

                </script>
@stop
