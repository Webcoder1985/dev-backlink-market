@extends('layout.master')
@section('title', 'Admin Management - Reviews')
@section('parentPageTitle', 'Tables')
@section('page-style')
<link rel="stylesheet" href="{{asset('assets/plugins/jquery-datatable/dataTables.bootstrap4.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/plugins/jquery-datatable/responsive.dataTables.min.css')}}" />
<!-- noUISlider Css -->
<link rel="stylesheet" href="{{asset('assets/plugins/nouislider/nouislider.min.css')}}" />

@stop
@section('content')
<div class="row clearfix">
    <div class="col-lg-12">

          @csrf
        <div class="card">
            <h2>Admin - Reviews</h2>

            <?php if(count($reviews) > 0){ ?>
            <div class="table-responsive">
                <table class="table display responsive nowrap table-hover product_item_list c_table theme-color mb-0" id="usersDatatable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Rating</th>
                            <th>Review</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                      <?php
                      $m=1; foreach($reviews as $row) :
                        if($row->active == 0){
                            $status = 'Pending';
                          }
                          else if($row->active == 1){
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
                       		<td><?php echo $row->rating; ?></td>
                               <td><?php echo $row->review; ?></td>
                            <td><?php echo $status; ?></td>
                            <td><?php echo $row->created_at; ?></td>

                            <td>
                                <?php if($row->active == 0){ ?>
                                    <button onclick="approvereview('<?php echo $row->id;?>');" class="btn btn-success btn-sm"><i class="zmdi zmdi-check" title="Review Approve"></i></button>&nbsp;
                                    <button onclick="deletereview('<?php echo $row->id;?>');" class="btn btn-danger btn-sm"><i class="zmdi zmdi-delete" title="Review Delete"></i></button>
                                    <?php }
                                    else {
                                      ?>
                                      <button onclick="declinereview('<?php echo $row->id;?>');" class="btn btn-danger btn-sm"><i class="zmdi zmdi-block" title="Review Decline"></i></button>
                                      <button onclick="deletereview('<?php echo $row->id;?>');" class="btn btn-danger btn-sm"><i class="zmdi zmdi-delete" title="Review Delete"></i></button>
                                      <?php
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
                  <center><h3>There is no review.</h3></center>
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
<script src="{{asset('assets/plugins/sweetalert/sweetalert.min.js')}}"></script> <!-- Sweetalert Js -->
<script src="{{asset('assets/js/pages/review.js')}}"></script>


@stop
