<div class="navbar-top">
    <ul class="navbar-nav" style="flex-direction: row">
        <li>
            <div class="navbar-brand">
                <button class="btn-menu ls-toggle-btn d-none d-md-inline" type="button">
                    <i style="font-size: 21px;" class="zmdi zmdi-menu"></i></button>
                <button class="btn-menu mobile_menu d-inline d-md-none" type="button">
                    <i style="font-size: 21px;" class="zmdi zmdi-menu"></i></button>

            </div>
        </li>
        <li>
            <div>
                <a href="{{route('homepage')}}">
                    <img src="{{asset('assets/images/logo.png')}}" width="35" alt="Backlink">
                    <span class="m-l-10 d-none d-md-inline" style="font-size: 19px;">Backlink Market</span>
                </a>
            </div>
        </li>
        <li class="margin-auto d-none d-sm-inline">
            <div class="user-info">

                <div class="image">
                    <div class="dropdown" style="float: right;margin-left: 10px;">
                        <a href="javascript:void(0);" id="notification-bell" class="dropdown-toggle" title="Notifications"
                           data-toggle="dropdown" role="button"><i class="zmdi zmdi-notifications"></i>
                             @if($notifications->count()>0)
                            <span class="badge badge-warning" id="lblNotificationCount">{{$notifications->count()}}</span>

                                <div class="notify" style="position: relative;top: -6px;right: 23px;"><span
                                        class="heartbit"></span><span class="point"></span></div>

                            @endif
                        </a>
                        <ul class="dropdown-menu slideUp2">
                            <li class="header">Notifications</li>
                            <li class="body">
                                @if($notifications->count()>0)
                                <ul class="menu list-unstyled">
                                    @foreach($notifications as $notification)
                                        <li>
                                            <a href="javascript:void(0);">
                                                <div class="icon-circle bg-blue"><i class="zmdi zmdi-account"></i></div>
                                                <div class="menu-info">
                                                    <h4>{{$notification->type}}</h4>
                                                    <p>
                                                        <i class="zmdi zmdi-time"></i> {{ time_elapsed_string($notification->created_at) }}

                                                    </p>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                                @else <div class="text-center font-12 mt-lg-2">There are no new notifications.</div>
                                @endif
                            </li>
                            <li class="footer"><a href="{{route('notification')}}">View All Notifications</a></li>
                        </ul>
                    </div>

                    <div class="dropdown" style="float: left;">
                        <a style="color: #46b6fe;" class="dropdown-toggle" title="Click for Account Details" data-toggle="dropdown"
                           role="button" href="javascript:void(0);">{{\Auth::user()->email}}
                            <i class="zmdi zmdi-caret-down" style="margin-left: 3px;"></i></a>
                        <ul class="dropdown-menu" style="min-width: 180px;width: auto;">
                            <li class="body">
                                <ul class="menu list-unstyled" style="font-size: 14px;">
                                    <li>
                                        <a href="{{route('editprofile')}}"><i class="zmdi zmdi-settings mr-2"></i><span>Profile</span></a>
                                    </li>
                                    <li>
                                        <a href="{{route('invoice')}}"><i
                                                class="zmdi zmdi-file mr-2"></i><span>Invoices</span></a>
                                    </li>
                                    <li>
                                        <a href="{{route('laravel-tickets.tickets.index')}}"><i
                                                class="zmdi zmdi-help-outline mr-2"></i><span>Support Ticket</span></a>
                                    </li>
                                    <div class="dropdown-divider"></div>
                                    <li style="justify-content: center;display: flex;">
                                        <a style="color: #46b6fe;text-align: center;margin-top: 0px;padding-top: 6px;"
                                           href="{{route('logout')}}"><i style="color: #46b6fe;"
                                                                         class="zmdi zmdi-power mr-2"></i><span>Log Out</span></a>
                                    </li>

                                </ul>
                            </li>

                        </ul>
                    </div>
                </div>

                <div class="detail">
                    Balance: {{\Auth::user()->balance}}{{Config::get('app.currency_symbol')}} |
                    @if(Auth::user()->balance_seller >= 50)
                        <a title="Click to withdraw your earnings."
                           href="{{route('withdrawrequest')}}">Earnings: {{\Auth::user()->balance_seller}}</a>
                    @else
                        Earnings:  {{\Auth::user()->balance_seller}}
                    @endif
                    {{Config::get('app.currency_symbol')}}

                </div>


            </div>
        </li>


        <li style="position: absolute;    right: 70px;">
            <div><a style="font-size: 18px" href="{{route('cart')}}" class="" title="Shopping Cart">
                    <i style="padding-right: 5px;color: black;font-size: 18px" class="zmdi zmdi-shopping-cart"></i>
                    <span class="badge badge-warning" id="lblCartCount">@php if(\Cart::count()>0) echo \Cart::count() @endphp</span>
                    </i>

                </a>
            </div>
        </li>
        <li style="margin-right: 2px;margin-top: 4px;position: absolute;right: 0;">
            <a style="margin-right: 5px;" href="{{route('laravel-tickets.tickets.index')}}" class="" title="Support">
                <i style="" class="zmdi zmdi-help-outline"></i></a>
            <a style="" title="Log Out" href="{{route('logout')}}"><i style="" class="zmdi zmdi-power mr-3"></i></a>
        </li>
        <?php /* <li style="margin-right: 20px;margin-top: 4px;"><a href="javascript:void(0);" class="js-right-sidebar" title="Theme Settings"><i class="zmdi zmdi-settings"></i></a></li> */ ?>
    </ul>
</div>
