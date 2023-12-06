@extends('layout.master')
@section('title', 'Pages')
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
    <link rel="stylesheet" href="{{asset('assets/plugins/searchable-option-list/sol.css')}}"/>
@stop
@section('content')
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    @if ($showSuccess==true)
                    <div class="row">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6">
                            <div class="alert alert-success" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="alert-heading mt-0">Pages successfully imported!</h4>
                                <p>It may take a few minutes to query all metrics and evaluate everything.</p>
                                <hr>
                                <p class="mb-0">
                                    Once we have finished, a price will appear and the page will be automatically listed on the marketplace.</p>

                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="row">

                        <div class="col-6 pt-4">
                            <h2><a class="collapsed" href="javascript:void(0)" data-toggle="collapse"
                                   data-target="#filterCollapse" aria-expanded="false"
                                   aria-controls="filterCollapse"><strong>Filters <span class='show-filter'><i
                                                    class="zmdi zmdi-plus"></i> Show Filter</span><span class='hide-filter'><i
                                                    class="zmdi zmdi-minus"></i> Hide Filter</span></strong></a></h2>
                        </div>
                        <div class="col-6">

                            <button class="btn btn-success float-right" onclick="openCreatePageModal({{$id}})"><i
                                        class="zmdi zmdi-plus" style="font-size: 24px;"></i><span
                                        style="font-weight: bold;font-size: 14px;padding-left: 8px;position: relative;top: -3px;">Add Post/Page</span>
                            </button>
                            <?php echo '<a class="btn btn-success float-right" href=' . URL('site_pages/' . $id . '/recheck') . ' style="margin-top:5px" class="btn btn-primary btn-sm"><i
                                    class="zmdi zmdi-replay" style="font-size: 24px;"></i><span
                                    style="font-weight: bold;font-size: 14px;padding-left: 8px;position: relative;top: -3px;">Check For New Pages</span></a>'; ?>

                        </div>
                    </div>
                </div>

                <div class="body collapse" id="filterCollapse">
                    <div>
                        <div class="row clearfix mb-4">
                            <div class="col-md-3 col-sm-6 mb-sm-4">
                                <div class="irs-demo">
                                    <p><b>DA</b></p>
                                    <input type="text" id="nouislider_da_range" value=""/>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-sm-4">
                                <div class="irs-demo">
                                    <p><b>PA</b></p>
                                    <input type="text" id="nouislider_pa_range" value=""/>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="irs-demo">
                                    <p><b>TF</b></p>
                                    <input type="text" id="nouislider_tf_range" value=""/>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="irs-demo">
                                    <p><b>CF</b></p>
                                    <input type="text" id="nouislider_cf_range" value=""/>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix mb-4">
                            <div class="col-md-3 col-sm-6">
                                <div class="irs-demo">
                                    <p><b>Backlinks</b></p>
                                    <input type="text" id="nouislider_bl_range" value=""/>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="irs-demo">
                                    <p><b>RD</b></p>
                                    <input type="text" id="nouislider_rd_range" value=""/>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="irs-demo">
                                    <p><b>Price</b></p>
                                    <input type="text" id="nouislider_price_range" value=""/>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="irs-demo">
                                    <p><b>OBL</b></p>
                                    <input type="text" id="nouislider_obl_range" value=""/>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-3 col-sm-6 mb-sm-4">
                                <p><b>Language</b></p>
                                <select class="form-control show-tick ms select2 languages" multiple data-placeholder="Select Language">
                                    <option value="all">All</option>
                                    @foreach($languages as $language)
                                        <option>{{$language->language}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <p><b>Category</b></p>
                                <select class="form-control show-tick ms select2 categories" multiple data-placeholder="Select Category">
                                    <option value="all">All</option>
                                    @foreach($categories as $category)
                                        <option>{{$category->category}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <p><b>Active</b></p>
                                <select class="form-control show-tick ms select2 is_active" data-placeholder="Select Status">
                                    <option value="all">All</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <form action="" method="post">
                    @csrf
                    <div class="table-responsive">
                        <table class="table display responsive nowrap table-hover product_item_list c_table theme-color mb-0" id="blogPagesDatatable">
                            <thead>
                            <tr>
                                <th>Added</th>
                                <th>URL</th>
                                <th>DA (MOZ)</th>
                                <th>PA (MOZ)</th>
                                <th>TF (Maj.)</th>
                                <th>CF (Maj.)</th>
                                <th>Links (Maj.)</th>
                                <th>RD (Maj.)</th>
                                <th>Obl</th>
                                <th>Language</th>
                                <th>Category</th>
                                <th>Indexed</th>
                                <th>Is Active?</th>
                                <th>Price</th>
                                <th>Actions</th>
                                <th><input type="checkbox"></th>

                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="align-right">

                        <input type="submit" class="btn btn-success" name="activate_pages" value="Activate"/>
                        <input type="submit" class="btn btn-warning" name="deactivate_pages" value="Deactivate"/>
                        <input type="submit" class="btn btn-danger" name="delete_pages" value="Delete"/>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addNewSellerPageModal" tabindex="-1" role="dialog" aria-labelledby="addNewSellerPageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form class="card auth_form mb-0" id="sellerPageAdd" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewSellerPageModalLabel">Create page(s)</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="modalLoading"><i class="zmdi zmdi-settings zmdi-hc-spin"></i> Please wait...</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success frmSubmitBtn">
                            <i class="zmdi zmdi-settings zmdi-hc-spin" style="display: none;"></i> Add
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editSellerPageModal" tabindex="-1" role="dialog" aria-labelledby="editSellerPageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form class="card auth_form mb-0" id="sellerPageEdit" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSellerPageModalLabel">Edit page</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="modalLoading"><i class="zmdi zmdi-settings zmdi-hc-spin"></i> Please wait...</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success frmSubmitBtn">
                            <i class="zmdi zmdi-settings zmdi-hc-spin" style="display: none;"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
@section('page-script')
    <script>
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
    <script src="{{asset('assets/plugins/jquery-datatable/buttons/buttons.print.min.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/select2.min.js')}}"></script> <!-- Select2 Js -->
    <script src="{{asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.js')}}"></script> <!-- noUISlider Plugin Js -->
    <script src="{{asset('assets/plugins/sweetalert/sweetalert.min.js')}}"></script> <!-- Sweetalert Js -->
    <script src="{{asset('assets/js/pages/tables/jquery-datatable.js')}}"></script>

    <script src="{{asset('assets/plugins/jquery-datatable/dataTables.select.min.js')}}"></script>
    <script src="{{asset('assets/js/pages/marketplace.js')}}"></script>
    @if (session()->has('message'))
        <script type="application/javascript">
            @if (session()->get('message')=="Oops, Something went wrong.")
            swal({
                title: "Operation Failed!",
                icon: "warning",
                text: "{{session()->get('message')}}",
                confirm: {
                    text: "OK",
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: true
                }
            });
            @else
            swal({
                title: "Success!",
                icon: "success",
                text: "{{session()->get('message')}}",
                confirm: {
                    text: "OK",
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: true

                }
            });
            @endif

            jQuery("body").find(".show-tooltip").tooltip({
                container: "body",
                trigger: "hover",
                placement: "bottom"
            })
        </script>
    @endif
@stop
