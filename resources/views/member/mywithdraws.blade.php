@extends('layout.master')
@section('title', 'Admin Management - Withdrawals')
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
                <h3>Seller - Withdrawals</h3>

                <p>Earnings available for withdrawal: {{\Auth::user()->balance_seller }}{{ Config::get('app.currency_symbol') }}
                    <a href="{{ route('withdrawrequest') }}" class="btn btn-success float-right mb-2" style="color: white" onclick="resetform()"><i class="zmdi zmdi-plus" style="font-size: 24px;"></i><span style="font-weight: bold;font-size: 14px;padding-left: 8px;position: relative;top: -3px;">Request Withdrawal</span>
                    </a>
                </p>
                <?php if($withdraw->count() > 0){ ?>
                <div class="table-responsive">
                    <table class="table display responsive nowrap table-hover product_item_list c_table theme-color mb-0" id="usersDatatable">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Requested on</th>
                            <th>Amount</th>
                            <th>Recipient PayPal Account</th>
                            <th>Status</th>
                            <th>Payout Date</th>
                            <th class="text-right">Receipt</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $m = 1; foreach($withdraw as $row) :
                        if ($row->status == 0) {
                            $status = 'Pending';
                        } else if ($row->status == 1) {
                            $status = 'Approved';
                        } else {
                            $status = 'Declined';
                        }
                        ?>

                        <tr>
                            <td>
                                <strong><?php echo $row->id; ?></strong>
                            </td>
                            <td><?php echo date_format($row->created_at, "d.m.Y"); ?></td>
                            <td><?php echo $row->amount; ?><?php echo Config::get('app.currency_symbol');?></td>

                            <td><?php echo $row->paypal_email; ?></td>
                            <td><?php echo $status; ?></td>
                            <td><?php if ($row->payout_at) echo date_format($row->payout_at, "d.m.Y"); ?></td>
                            <td class="text-right">
                                <?php if ($row->status == 1) {
                                    echo '<a href="' . route('receipt', $row->id) . '" target="_blank">Download</a>';
                                }
                                ?>

                            </td>


                        </tr>

                        <?php $m++; endforeach; ?>

                        </tbody>

                    </table>

                </div>
                <?php }
                else {
                ?>
                <center><h3>There is no request for widthdraw.</h3></center>
                <?php
                }
                ?>
            </div>

        </div>
    </div>
@stop
@section('page-script')
    <script>
        var base_url = '{{url("/")}}';

    </script>
    <script src="{{asset('assets/plugins/select2/select2.min.js')}}"></script> <!-- Select2 Js -->
    <script src="{{asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.js')}}"></script> <!-- noUISlider Plugin Js -->
    <script src="{{asset('assets/plugins/sweetalert/sweetalert.min.js')}}"></script> <!-- Sweetalert Js -->
    <script src="{{asset('assets/js/pages/withdraw.js')}}"></script>

@stop
