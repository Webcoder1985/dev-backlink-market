@extends('layout.master')
@section('title', 'Admin Management - Withdrawals')
@section('parentPageTitle', 'Tables')
@section('page-style')
<link rel="stylesheet" href="{{asset('assets/plugins/jquery-datatable/dataTables.bootstrap4.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/plugins/jquery-datatable/responsive.dataTables.min.css')}}" />
<!-- noUISlider Css -->
<link rel="stylesheet" href="{{asset('assets/plugins/nouislider/nouislider.min.css')}}" />
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('assets/plugins/select2/select2.css')}}" />
@stop
@section('content')
<div class="row clearfix">
    <div class="col-lg-12">

          @csrf
        <div class="card">
            <h2>Admin - Withdrawals</h2>
            <?php if($withdraw->count() > 0){ ?>
            <div class="table-responsive">
                <table class="table display responsive nowrap table-hover product_item_list c_table theme-color mb-0" id="usersDatatable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                      <?php
                      $m=1; foreach($withdraw as $row) :
                        if($row->status == 0){
                            $status = 'Pending';
                          }
                          else if($row->status == 1){
                            $status = 'Approved';
                          }
                          else{
                            $status = 'Decline';
                          }
                      ?>

                     		<tr>
                            <td>
                           		<strong><?php echo $row->user->firstname; ?> <?php echo $row->user->lastname; ?></strong>
                       		</td>
                       		<td><?php echo Config::get('app.currency_symbol');?><?php echo $row->amount; ?></td>
                            <td><?php echo $status; ?></td>
                            <td><?php echo $row->created_at; ?></td>

                            <td>
                                <?php if($row->status == 0 ){ ?>
                                    <button onclick="approverequest('<?php echo $row->id;?>');" class="btn btn-success btn-sm"><i class="zmdi zmdi-check"></i></button>
                                    <button onclick="declinerequest('<?php echo $row->id;?>');" class="btn btn-danger btn-sm"><i class="zmdi zmdi-delete"></i></button>
                                    <?php }
                                    else {
                                      echo $row->reference_id;
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
