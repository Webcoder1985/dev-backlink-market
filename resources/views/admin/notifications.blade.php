@extends('layout.master')
@section('title', 'Notifications')
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
            <form class="card auth_form mb-0" id="placeorder" method="POST">
                @csrf
                <div class="card">
                    <?php if (count($all_notifications) > 0){ ?>
                    <div class="table-responsive">
                        <table class="table display responsive nowrap table-hover product_item_list c_table theme-color mb-0" id="notificationDatatable">
                            <thead>
                            <tr>

                                <th style="width: 120px;">Date</th>
                                <th>Message</th>
                                 <th>User</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php $m = 1; foreach ($all_notifications as $notification) : ?>

                            <tr id="tr_row_<?php echo $notification->id;?>">

                                <td>
                                        <?php echo date('d.m.Y', strtotime($notification->created_at)); ?>
                                </td>
                                <td style="text-align: left!important;">
                                        <?php echo $notification->type; ?>
                                </td>
                                 <td style="text-align: left!important;">
                                        <?php echo $notification->user_id; ?>
                                </td>
                            </tr>


                                <?php $m++; endforeach; ?>

                            </tbody>

                        </table>

                    </div>
                    <?php }

                    ?>
                </div>
            </form>
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
    <script src="{{asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.js')}}"></script> <!-- noUISlider Plugin Js -->
    <script src="{{asset('assets/plugins/sweetalert/sweetalert.min.js')}}"></script> <!-- Sweetalert Js -->
    <script src="{{asset('assets/js/pages/order.js')}}"></script>
    <script>
        var base_url = '{{url("/")}}';
        $(document).ready(function () {
         $('#notificationDatatable').DataTable({"order" : [[0, "desc"]]});

        });
    </script>

@stop
