@extends('layout.master')
@section('title', 'Admin Management - Seller Blogs')
@section('parentPageTitle', 'Seller Blogs')
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
        <div class="card">
            <h3 class="mb-0">Admin - Seller Sites</h3>
            <div class="header">
                <h2><strong>Filters</strong></h2>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-md-3 col-sm-6">
                        <p><b>User ID</b></p>
                        <input id="filterUserID" class="form-control" data-placeholder="Enter User ID"/>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <p> <b>Domain</b> </p>
                        <input id="filterDomain" class="form-control"  data-placeholder="Enter Domain" />
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <p> <b>Status</b> </p>
                        <select id="filterStatus" class="form-control show-tick ms select2 is_active"  data-placeholder="Select Status">
                            <option value="all">All</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <p> <b>Is Ban?</b> </p>
                        <select id="filterBanStatus" class="form-control show-tick ms select2 is_active"  data-placeholder="Select">
                            <option value="all">All</option>
                            <option value="1">Ban</option>
                            <option value="0">Not Ban</option>
                        </select>
                    </div>

                </div>
            </div>
        </div>
        <div class="card">
            <div class="table-responsive">
                <table class="table display responsive nowrap table-hover product_item_list c_table theme-color mb-0" id="adminSitesDatatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Seller ID</th>
                            <th>Site URL</th>
                            <th>Status</th>
                            <th>Created On</th>
                            <th>Action</th>
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
                <h5 class="modal-title" id="exampleModalLabel">Site Pages</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
<script>
    $(document).on("click", ".btn-admin-expand-site-pages", function() {
        event.preventDefault();
        var id = $(this).attr('id');
        $.ajax({
            type: 'GET',
            url: '{{url("admin-get-sitepages")}}/' + id,
            success: function(data) {
              html="<ul>";
              $.each( data.urls, function( key, value ) {
                html+="<li><a href='"+value+"' target='_blank'>"+value+"</a></li>";
              });
              html+="</ul>";
              $('.modal-body').html(html);
              $('#exampleModal').modal('show');
            }
        });
    });
    $(document).on("change", "#filterUserID", function() {
        $('#adminSitesDatatable').DataTable().draw()
    });
    $(document).on("change", "#filterDomain", function() {
        $('#adminSitesDatatable').DataTable().draw()
    });
    $(document).on("change", "#filterStatus", function() {
        $('#adminSitesDatatable').DataTable().draw()
    });
    $(document).on("change", "#filterBanStatus", function() {
        $('#adminSitesDatatable').DataTable().draw()
    });

    function editUser(params) {
        $.ajax({
            type: 'GET',
            url: '{{url("getuser")}}/' + params,
            success: function(data) {
                $('#UserDetail').attr('data-id', data.id)
                $('#firstname').val(data.firstname)
                $('#lastname').val(data.lastname)
                $('#email').val(data.email)
                $('#balance').val(data.balance)
                $('#status').val(data.user_status);
                $('#status').trigger('change');
                $('#business_name').val(data.business_name)
                $('#street').val(data.street)
                $('#street_number').val(data.street_number)
                $('#zip').val(data.zip)
                $('#city').val(data.city)
                $('#country').val(data.country)
                $('#s2id_country').select2('val', data.country);
                $('#vat').val(data.vat)
                $('#notes').val(data.notes)



                $('#exampleModal').modal('show');
            }
        });
    }

    function banBlog(params,is_ban) {
      if(is_ban==0){
        $.ajax({
            type: 'GET',
            url: '{{url("ban-blog")}}/' + params,
            success: function(data) {
                location.reload();
            }
        });
      }
      else{
        $.ajax({
            type: 'GET',
            url: '{{url("relist-blog")}}/' + params,
            success: function(data) {
                location.reload();
            }
        });
      }
    }

    function deleteBlog(params) {
      $.ajax({
        type: 'GET',
        url: '{{url("delete-blog")}}/' + params,
        success: function(data) {
            location.reload();
        }
      });
    }


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
<script src="{{asset('assets/plugins/nouislider/nouislider.js')}}"></script> <!-- noUISlider Plugin Js -->
<script src="{{asset('assets/js/pages/tables/jquery-datatable.js')}}"></script>
@stop
