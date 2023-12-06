@extends('layout.master')
@section('title', 'Add Blog Posts')
@section('parentPageTitle', 'Tables')
@section('page-style')
<link rel="stylesheet" href="{{asset('assets/plugins/jquery-datatable/dataTables.bootstrap4.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/plugins/jquery-datatable/responsive.dataTables.min.css')}}" />
<!-- noUISlider Css -->
<link rel="stylesheet" href="{{asset('assets/plugins/nouislider/nouislider.min.css')}}" />
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('assets/plugins/select2/select2.css')}}" />
<!-- Sweetalert -->
<link rel="stylesheet" href="{{asset('assets/plugins/sweetalert/sweetalert.css')}}" />
@stop
@section('content')
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
           <div class="row">
               <div class="col-lg-12">
                   <h3>Add Blog Posts/Pages</h3>
                   <p>Uncheck the Blog Posts you do not want to add to the Marketplace and click the Submit button.</p>

               </div>
           </div>
            @if(!is_array($all_pages))
                         <div class="alert alert-warning mt-2" role="alert">
                        No blog pages found
                        </div>
                  <a href="{{route('seller_sites')}}" class="btn btn-success" ><span
                    style="font-weight: bold;font-size: 14px;position: relative;">Go Back</span>
            </a>
           @else

            <div class="table-responsive">
                <form action="" method="post">
                    @csrf
                    @if(is_array($all_pages) && count($all_pages)>0)
                        <input class="btn btn-success btn-md" style="font-size: 12px;font-weight: bold;" type="submit"
                               value="Submit"/>
                        <input type="hidden" name="deactivate_pages" value="Deactivate"/>
                    @endif
                    <input type="hidden" name="site_id" value="{{ $site_id }}"/>

                    <table
                        class="table display responsive nowrap table-hover product_item_list c_table theme-color mb-0">
                        <thead>
                        <tr>

                            <th style="width: 80px"><input name="select-all" id="select-all" type="checkbox"> Add</th>
                            <th style="width: 125px;">Publish Date</th>
                            <th>URL</th>


                            <th>Post/Page Title</th>
                            <th style="width: 65px;">Type</th>

                        </tr>
                        </thead>
                        @if(is_string($all_pages))
                    </table>
                    <div class="alert alert-warning mt-2" role="alert">
                        {{ $all_pages }}
                    </div>
                    @else
                        <tbody>

                            @foreach($all_pages as $page)
                                @php $ids[]=$page["ID"] @endphp
                                <tr>
                                    <td><input type="checkbox" checked name="selected_pages[]" value="{{ $page['ID'] }}"/>
                                    </td>
                                    <td style="">{{ date("d.m.Y", strtotime($page['publish_date']))}}</td>
                                    <td style="text-align: left !important;">{{ $page['url']}}</td>
                                    <td style="text-align: left !important;">{{ $page['title']}}</td>
                                    <td style="">{{ ucfirst($page['type'])}}</td>

                                </tr>
                            @endforeach

                            <tr>
                                <td colspan="5"><input class="btn btn-success btn-md"
                                                       style="font-size: 12px;font-weight: bold;" type="submit"
                                                       value="Submit"/></td>
                            </tr>
                            </tbody>
                            </table>
                            <input type="hidden" name="all_pages" value="{{ implode(',',$ids) }}"/>

                    @endif
                </form>
            </div>
          @endif
        </div>
    </div>
</div>
<!-- Modal -->

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
    <script type="application/javascript">
        $('#select-all').click(function(event) {
    if(this.checked) {
        // Iterate each checkbox
        $(':checkbox').each(function() {
            this.checked = true;
        });
    } else {
        $(':checkbox').each(function() {
            this.checked = false;
        });
    }
});
    </script>
@stop
