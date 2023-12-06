@extends('layout.master')
@section('title', 'Manage My Sites')
@section('parentPageTitle', 'Tables')
@section('page-style')
    <link rel="stylesheet" href="{{asset('assets/plugins/jquery-datatable/dataTables.bootstrap4.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/plugins/jquery-datatable/responsive.dataTables.min.css')}}"/>
    <!-- noUISlider Css -->
    <link rel="stylesheet" href="{{asset('assets/plugins/nouislider/nouislider.min.css')}}"/>
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('assets/plugins/select2/select2.css')}}"/>
    <!-- Sweetalert -->
    <link rel="stylesheet" href="{{asset('assets/plugins/sweetalert/sweetalert.css')}}"/>
    <style>

        .tooltip {
            position: relative;
            display: inline-block;
            opacity: 1;
            margin-top: -3px;
            margin-left: 5px;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 140px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 150%;
            left: 50%;
            margin-left: -75px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltip .tooltiptext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #555 transparent transparent transparent;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        #auth_key {
            max-width: 180px;
            text-align: center;
            margin-left: 80px;
        }

        #sitesDatatable tbody tr td:nth-last-child(2) {
            padding-left: 1.8rem;
        }
    </style>
@stop
@section('content')
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <h3 class="mb-0">Seller - Manage Sites</h3>
                <a href="{{route('download-plugin')}}" class="btn btn-success float-right mb-2"><i class="zmdi zmdi-download" style="font-size: 24px;"></i><span
                        style="font-weight: bold;font-size: 14px;padding-left: 8px;position: relative;top: -3px;">Download WP Plugin</span>
                </a>
                <button class="btn btn-success float-right mb-2" onclick="resetform()" data-toggle="modal"
                        data-target="#exampleModal"><i class="zmdi zmdi-plus" style="font-size: 24px;"></i><span
                        style="font-weight: bold;font-size: 14px;padding-left: 8px;position: relative;top: -3px;">Add New Site</span>
                </button>
                <div class="table-responsive">
                    <table class="table display responsive nowrap table-hover product_item_list c_table theme-color mb-0" id="sitesDatatable">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Site Url</th>
                            <th>Site AuthKey</th>
                            <th>Plugin Version</th>
                            <th>Active</th>
                            <th>Created</th>
                            <th style="padding-bottom: 0;padding-top: 0;">
                                <span style="position: absolute;top: 17.5px;margin-left: 11px;">Pages</span><br><small>(active/total)</small>
                            </th>
                            <th style="text-align: right">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Site</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="card auth_form" id="siteDetail" method="POST">
                        @csrf
                        <div class="body">

                            <label for="siteurl">Your Wordpress Blog Url</label>
                            <div class="input-group mb-3" require>
                                <div style="width: 100%">
                                    <input id="siteurl" type="text" class="form-control" name="site_url" value="https://" autocomplete="on" required autofocus>
                                </div>
                                <div style="font-size: 12px;color: #484848;margin-left: 2px;">(e.x.
                                    https://www.your-blog.com)
                                </div>
                            </div>
                            <div id="siteurl_error"></div>
                            <?php /*  <label for="site_category">Category</label>
                        <div class="input-group mb-3" require>
                            <select id="site_category" value="Web Development" class="form-control show-tick ms select2" name="site_category" required>
                              <option value="" selected>Select Category</option>
                              <option>Web Development</option>
                              <option>Medical</option>
                            </select>
                        </div>
                        <input type="hidden" name="site_category" id="site_category" value="Web Development">*/ ?>
                            <input type="hidden" name="id" id="id">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="saveButton" class="btn btn-primary">Next</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="verificationModal" tabindex="-1" role="dialog" aria-labelledby="verificationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verificationModalLabel">Verification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="card auth_form" id="verifyDetail" method="POST">
                        @csrf
                        <div class="body">
                            <p>Confirm your Blog</p>
                            <ul>
                                <li>Install our
                                    <a style="color: #46b6fe" href="{{route('download-plugin')}}">Backlink
                                        Market WP Plugin</a> to your Blog
                                </li>
                                <li>In your WordPress Dashboard click <br>"Backlink-Market" from the main menu on the
                                    left.
                                </li>
                                <li>Enter the following code to AuthKey field:</li>
                            </ul>
                            <div class="input-group mb-3" require>
                                <input type="hidden" id="site_id" name="site_id" value=""/>
                                <input id="auth_key" type="text" class="form-control" name="auth_key" autofocus readonly>
                                <div class="tooltip">
                                    <button class="btn btn-secondary buttons-print" onclick="event.preventDefault();myFunction()" onmouseout="outFunc()">
                                        <span class="tooltiptext" id="myTooltip">Copy to clipboard</span> Copy text
                                    </button>
                                </div>
                            </div>
                            <div id="verify_status" class="alert alert-danger" style="display:none;"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="confirmButton" class="btn btn-primary">Confirm</button>
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-script')
    <script>
        var base_url = '{{url("/")}}';
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
    <script src="{{asset('assets/plugins/sweetalert/sweetalert.min.js')}}"></script> <!-- Sweetalert Js -->
    <script src="{{asset('assets/plugins/nouislider/nouislider.js')}}"></script> <!-- noUISlider Plugin Js -->
    <script src="{{asset('assets/js/pages/tables/jquery-datatable.js')}}"></script>
    <script src="{{asset('assets/js/pages/seller_site.js')}}"></script>
    @if(isset($_REQUEST["add_site"]) && $_REQUEST["add_site"]=="true")
        <script type="text/javascript">
            jQuery('#exampleModal').modal();
        </script>
    @endif
    @if(isset($_REQUEST["confirm"]))
        <script type="text/javascript">
            //window.setTimeout(function(){jQuery(".page-loader-wrapper").show();},1000);
            $(window).on('load', function () {
                $counter = 0;

                function set_button() {
                    $counter++;
                    $button = $('button[data-site="<?php echo urldecode($_REQUEST["confirm"]); ?>"]').get(0);
                    if ($button == undefined) {
                        if ($counter < 10) {
                            window.setTimeout(set_button, 1000);
                        }
                    } else {
                        jQuery(".page-loader-wrapper").hide();
                        confirmSite($('button[data-site="<?php echo urldecode($_REQUEST["confirm"]); ?>"]').get(0));
                    }
                }

                set_button();
            });
        </script>
    @endif
}
    <script type="text/javascript">
        function myFunction() {
            event.preventDefault();
            // copy auth button to  key to clipboard on modal
            var copyText = document.getElementById("auth_key");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);

            var tooltip = document.getElementById("myTooltip");
            tooltip.innerHTML = "Copied: " + copyText.value;
        }

        function outFunc() {
            var tooltip = document.getElementById("myTooltip");
            tooltip.innerHTML = "Copy to clipboard";
        }

        function reload_table() {
            $("#sitesDatatable").DataTable().draw()
        }

    </script>

@stop
