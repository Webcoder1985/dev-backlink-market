<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex">


    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('site.webmanifest')}}">
    <link rel="mask-icon" href="{{asset('safari-pinned-tab.svg')}}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <link rel="icon" href="{{asset('favicon.ico')}}" type="image/x-icon"> <!-- Favicon-->
    <title>{{ config('app.name') }} - @yield('title')</title>
    <meta name="description" content="@yield('meta_description', config('app.name'))">
    <meta name="author" content="@yield('meta_author', config('app.name'))">
    <script type="text/javascript">var settings = {"baseurl": "{{ URL::to('/') }}"}</script>
    @yield('meta')
    {{-- See https://laravel.com/docs/5.5/blade#stacks for usage --}}
    @stack('before-styles')
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}">
    @if (trim($__env->yieldContent('page-style')))
        @yield('page-style')
    @endif
<!-- Custom Css -->
    <link rel="stylesheet" href="{{asset('assets/css/style.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/customcss/custom_style.css')}}">
        @stack('after-styles')

    </head>
    <?php

    $theme = "theme-blue";
    $menu = "";



    ?>
    <body class="<?= $theme ?>">
        <!-- Page Loader -->
        <div class="page-loader-wrapper">
            <div class="loader">
                <div class="m-t-30"><img class="zmdi-hc-spin" src="{{asset('assets/images/logo.png')}}" width="48" height="48" alt="Backlink"></div>
                <p>Please wait...</p>
            </div>
        </div>
        <!-- Overlay For Sidebars -->
        <div class="overlay"></div>
        @include('layout.navbartop')
      {{--     @include('layout.navbarright')--}}
        @include('layout.sidebar')
      @include('layout.rightsidebar')
        <section class="content">
          <?php /*  <div class="block-header">
                <div class="row">
                    <div class="col-lg-7 col-md-6 col-sm-12">
                        <button class="btn btn-primary btn-icon mobile_menu" type="button"><i class="zmdi zmdi-sort-amount-desc"></i></button>
                    </div>
                    <div class="col-lg-5 col-md-6 col-sm-12">
                        <button class="btn btn-primary btn-icon float-right right_icon_toggle_btn" type="button"><i class="zmdi zmdi-arrow-right"></i></button>
                    </div>
                </div>
            </div> */?>
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
        @yield('modal')
        <!-- Scripts -->
        @stack('before-scripts')
        <script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
        <script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
        <script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
        @stack('after-scripts')
        @if (trim($__env->yieldContent('page-script')))
            @yield('page-script')
		@endif
        @if (Session::has('verified'))
            <div class="modal fade" id="account_success_message" tabindex="-1" role="dialog" aria-labelledby="account_success_messageLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">&nbsp;</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center"><h2>Account successfully activated</h2></div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                jQuery('#account_success_message').modal();
            </script>
            @endif
        <script type="text/javascript">
            $(document).ready(function () {
                jQuery("#notification-bell").click(function () {
                    $.ajax({
                        async: true,
                        url: "{{route('notification_checked')}}",
                  contentType: "application/json; charset=utf-8",
                  dataType: "json",
                  success: function (response) {
                    $('.notify').hide();
                    $("#lblNotificationCount").html('');
                  }
              });
            });

          });
        </script>
    </body>
</html>
