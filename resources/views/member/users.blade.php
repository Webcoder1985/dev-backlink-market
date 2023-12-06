@extends('layout.master')
@section('title', 'Admin Management - Users')
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
        <div class="card">
            <h2>Admin - Users</h2>
            <div class="header">
                <h2><strong>Filters</strong></h2>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-md-3 col-sm-6">
                        <p><b>User ID</b></p>
                        <input id="filterID" class="form-control" data-placeholder="Enter ID"/>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <p> <b>Email</b> </p>
                        <input id="filterEmail" class="form-control"  data-placeholder="Enter Email" />
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <p> <b>FirstName</b> </p>
                        <input id="filterFirstName" class="form-control"  data-placeholder="Enter First Name" />
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <p> <b>LastName</b> </p>
                        <input id="filterLastName" class="form-control"  data-placeholder="Enter Last Name" />
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <p> <b>Country</b> </p>
                        <select id="filterCountry" class="form-control show-tick ms select2 categories" multiple data-placeholder="Select Country">
                            @foreach($countryLists as $countryList)
                            <option value="{{$countryList['code']}}">{{$countryList['country']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <p> <b>Status</b> </p>
                        <select id="filterStatus"  class="form-control show-tick ms select2 tlds" multiple data-placeholder="Select Status">
                            <option value="">Status</option>
                            <option value="0">Registered</option>
                            <option value="1">Email Verified</option>
                            <option value="2">Banned</option>
                            <option value="3">Admin</option>
                        </select>
                    </div>

                </div>
            </div>
        </div>
        <div class="card">
            <div class="table-responsive">
                <table class="table display responsive nowrap table-hover product_item_list c_table theme-color mb-0" id="usersDatatable">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th>Registration</th>
                        <th>Register IP</th>
                        <th>Last Login IP</th>
                        <th>Country</th>
                        <th>Notes</th>
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
                <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="card auth_form" data-id="" id="UserDetail" method="POST">
                    @csrf
                    <div class="body">

                        <label for="firstname">firstname</label>
                        <div class="input-group mb-3">
                            <input id="firstname" type="text" class="form-control" name="firstname" autofocus>
                        </div>
                        <label for="lastname">lastname</label>
                        <div class="input-group mb-3">
                            <input id="lastname" type="text" class="form-control" name="lastname">
                        </div>
                        <label for="email">Email</label>
                        <div class="input-group mb-3">
                            <input id="email" type="email" class="form-control" name="email" autocomplete="email">
                        </div>
                        <label for="balance">Balance</label>
                        <div class="input-group mb-3">
                            <input id="balance" type="text" class="form-control" name="balance">
                        </div>
                        <label for="balance">Balance Seller</label>
                        <div class="input-group mb-3">
                            <input id="balance_seller" type="text" class="form-control" name="balance_seller">
                        </div>
                        <label for="status">Status</label>
                        <select id="status" name="user_status" class="form-control show-tick ms select2" tabindex="-1">
                            <option value="">Status</option>
                            <option value="0">Registered</option>
                            <option value="1">Email Verified</option>
                            <option value="2">Banned</option>
                            <option value="3">Admin</option>

                        </select>

                        <label for="business_name">Business Name</label>
                        <div class="input-group mb-3">
                            <input id="business_name" type="text" class="form-control" name="business_name">
                        </div>
                        <label for="street">Street</label>
                        <div class="input-group mb-3">
                            <input id="street" type="text" class="form-control" name="street">
                        </div>
                        <label for="street_number">Street Number</label>
                        <div class="input-group mb-3">
                            <input id="street_number" type="text" class="form-control" name="street_number">
                        </div>
                        <label for="zip">Zip</label>
                        <div class="input-group mb-3">
                            <input id="zip" type="text" class="form-control" name="zip">
                        </div>
                        <label for="city">City</label>
                        <div class="input-group mb-3">
                            <input id="city" type="text" class="form-control" name="city">
                        </div>
                        <label for="country">Country</label>
                        <div class="input-group mb-3">
                          <select id="country" name="country" class="form-control show-tick ms select2 categories" data-placeholder="Select Country">
                            @foreach($countryLists as $countryList)
                            <option value="{{$countryList['code']}}">{{$countryList['country']}}</option>
                            @endforeach
                          </select>
                        </div>
                        <label for="vat">Vat</label>
                        <div class="input-group mb-3">
                            <input id="vat" type="text" class="form-control" name="vat">
                        </div>
                        <label for="notes">Notes</label>
                        <div class="input-group mb-3">
                            <input id="notes" type="text" class="form-control" name="notes">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="saveButton" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
<script>
    function banUser(params) {
        $.ajax({
            type: 'GET',
            url: '{{url("changestatus")}}/' + params + '?status=2',
            success: function(data) {
                $('#usersDatatable').DataTable().draw()
            }
        });
    }
    $(document).on("click", "#saveButton", function() {
        event.preventDefault();
        var data = $('#UserDetail').serialize()
        $.ajax({
            type: 'POST',
            url: '{{url("saveuser")}}/' + $('#UserDetail').attr('data-id'),
            data: data,
            success: function(data) {
                $('#usersDatatable').DataTable().draw()
                $('#exampleModal').modal('hide');
            }
        });
    });
    $(document).on("change", "#filterID", function() {
        $('#usersDatatable').DataTable().draw()
    });
    $(document).on("change", "#filterEmail", function() {
        $('#usersDatatable').DataTable().draw()
    });
    $(document).on("change", "#filterFirstName", function() {
        $('#usersDatatable').DataTable().draw()
    });
    $(document).on("change", "#filterLastName", function() {
        $('#usersDatatable').DataTable().draw()
    });
    $(document).on("change", "#filterCountry", function() {
        $('#usersDatatable').DataTable().draw()
    });
    $(document).on("change", "#filterStatus", function() {
        $('#usersDatatable').DataTable().draw()
    });

    function editUser(params) {
        $.ajax({
            type: 'GET',
            url: '{{url("getuser")}}/' + params,
            success: function (data) {
                $('#UserDetail').attr('data-id', data.id)
                $('#firstname').val(data.firstname)
                $('#lastname').val(data.lastname)
                $('#email').val(data.email)
                $('#balance').val(data.balance)
                $('#balance_seller').val(data.balance_seller)
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

    function deleteUser(params) {
        var con = confirm('are you sure?')
        if (con) {
            $.ajax({
                type: 'GET',
                url: '{{url("deleteuser")}}/' + params,
                success: function(data) {
                    $('#usersDatatable').DataTable().draw()
                }
            });
        }
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
