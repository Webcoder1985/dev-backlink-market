<!-- Left Sidebar -->
<aside id="leftsidebar" class="sidebar">


    <div class="menu">
        <ul class="list">

            @if(Auth::user()->user_status === 3)
                {{-- 3=Admin --}}
                <li class="{{ Request::segment(1) === 'dashboard' ? 'active' : null }}">
                    <a href="{{route('dashboard')}}"><i class="zmdi zmdi-view-dashboard"></i><span>Admin Dashboard</span></a>
                </li>
            @else
                {{-- User --}}
                <li class="{{ Request::segment(1) === 'dashboard' ? 'active' : null }}">
                    <a href="{{route('dashboard')}}"><i class="zmdi zmdi-view-dashboard"></i><span>Dashboard</span></a>
                </li>
            @endif
            <li class="{{ Request::segment(1) === 'marketplace' ? 'active' : null }}">
                <a href="{{route('marketplace')}}"><i class="zmdi zmdi-home"></i><span>Marketplace</span></a>
            </li>
            <li class="{{ (Request::segment(1) === 'buyer-links' || Request::segment(1) === 'buyer-orders') ? 'active open open_top' : null }}">

                <a class="menu-toggle waves-effect waves-block"><i class="zmdi zmdi-playlist-plus"></i><span>Buyer</span></a>
                <ul class="ml-menu" style="display: block;">
                    <li class="{{ Request::segment(1) === 'buyer-links' ? 'active' : null }}">
                        <a href="{{route('buyerlinks')}}"><i class="zmdi zmdi-link"></i><span style="display: inline !important;">Links</span></a>
                    </li>
                    <li class="{{ Request::segment(1) === 'buyer-orders' ? 'active' : null }}">
                        <a href="{{route('buyerorders')}}"><i class="zmdi zmdi-assignment"></i><span>Orders</span></a>
                    </li>
                </ul>
            </li>
            <li class="{{ ( Request::segment(1) === 'site_pages' || Request::segment(1) === 'seller_sites' || Request::segment(1) === 'seller_sites?add_site=true' || Request::segment(1) === 'seller-links'|| Request::segment(1) === 'seller-orders'  ) ? 'active open open_top' : null }}">

                <a class="menu-toggle waves-effect waves-block"><i class="zmdi zmdi-money-box"></i><span>Seller</span></a>
                <ul class="ml-menu" style="display: block;">
                    <li class="{{ Request::segment(1) === 'seller_sites?add_site=true' ? 'active' : null }}">
                        <a href="{{route('seller_sites')}}?add_site=true"><i class="zmdi zmdi-plus-square"></i><span>Add Site</span></a>
                    </li>
                    <li class="{{ (Request::segment(1) === 'seller_sites' || Request::segment(1) === 'site_pages') ? 'active' : null }}">
                        <a href="{{route('seller_sites')}}"><i class="zmdi zmdi-wrench"></i><span>Manage Sites</span></a>
                    </li>
                    <li class="{{ Request::segment(1) === 'seller-links' ? 'active' : null }}">
                        <a href="{{route('sellerlinks')}}"><i class="zmdi zmdi-link"></i><span>Links</span></a>
                    </li>
                    <li class="{{ Request::segment(1) === 'seller-orders' ? 'active' : null }}">
                        <a href="{{route('sellerorders')}}"><i class="zmdi zmdi-assignment"></i><span>Sales</span></a>
                    </li>


                </ul>
            </li>

            <li class="{{ (Request::segment(1) === 'editprofile'|| Request::segment(1) === 'earnings' ||  Request::segment(1) === 'my-withdraw-list'  || Request::segment(1) === 'invoice' || Request::segment(1) === 'tickets') ? 'active open open_top' : null }}">

                <a class="menu-toggle waves-effect waves-block"><i class="zmdi zmdi-account zmdi-hc-fw"></i><span>Account</span></a>
                <ul class="ml-menu" style="display: block;">
                    <li class="{{ Request::segment(1) === 'editprofile' ? 'active' : null }}">
                        <a href="{{route('editprofile')}}"><i class="zmdi zmdi-settings"></i><span>Profile</span></a>
                    </li>

                    <li class="{{ Request::segment(1) === 'earnings' ? 'active' : null }}">
                        <a href="{{route('earnings')}}"><i class="zmdi zmdi-money"></i><span>Earnings</span></a>
                    </li>
                    <li class="{{ Request::segment(1) === 'my-withdraw-list' ? 'active' : null }}">
                        <a href="{{route('mywithdrawlist')}}"><i class="zmdi zmdi-balance"></i><span>Withdrawals</span></a>
                    </li>
                    <li class="{{ Request::segment(1) === 'invoice' ? 'active' : null }}">
                        <a href="{{route('invoice')}}"><i class="zmdi zmdi-file"></i><span>Invoices</span></a>
                    </li>
                    <li class="{{ Request::segment(1) === 'tickets' ? 'active' : null }}">
                        <a href="{{route('laravel-tickets.tickets.index')}}"><i class="zmdi zmdi-help-outline"></i><span>Support Ticket</span></a>
                    </li>
                    <li class="">
                        <a href="{{route('logout')}}"><i class="zmdi zmdi-power"></i><span>Log Out</span></a>
                    </li>
                </ul>
            </li>


            @if(Auth::user()->user_status === 3)
                {{-- 3=Admin --}}
                <li class="{{ (Request::segment(1) === 'adminorder' || Request::segment(1) === 'admin-purchase-transaction-history' || Request::segment(1) === 'adminorderlinks' || Request::segment(1) === 'user' || Request::segment(1) === 'admin-seller-blogs' || Request::segment(1) === 'tickets' || Request::segment(1) === 'withdraw-list' || Request::segment(1) === 'admin-review-list') ? 'active open open_top' : null }}">

                    <a class="menu-toggle waves-effect waves-block "><i class="zmdi zmdi-shield-security"></i><span>Admin Menu</span></a>
                    <ul class="ml-menu" style="display: block;">
                        <li class="{{ Request::segment(1) === 'admin-seller-blogs' ? 'active' : null }}">
                            <a href="{{route('adminsellerblogs')}}">Seller Sites</a>
                        </li>
                        <li class="{{ Request::segment(1) === 'adminorder' ? 'active' : null }}">
                            <a href="{{route('adminorder')}}">Orders</a>
                        </li>
                        <li class="{{ Request::segment(1) === 'adminorderlinks' ? 'active' : null }}">
                            <a href="{{route('adminorderlinks')}}">Order Links</a>
                        </li>
                        <li class="{{ Request::segment(1) === 'admin-purchase-transaction-history' ? 'active' : null }}">
                            <a href="{{route('admin-transactions')}}">Transactions</a>
                        </li>
                        <li class="{{ Request::segment(1) === 'user' ? 'active' : null }}">
                            <a href="{{route('user')}}">Users</a>
                        </li>
                        <li class="{{ Request::segment(1) === 'tickets' ? 'active' : null }}">
                            <a href="{{route('laravel-tickets.tickets.index')}}">Tickets</a>
                        </li>
                        <li class="{{ Request::segment(1) === 'withdraw-list' ? 'active' : null }}">
                            <a href="{{route('withdrawlist')}}">Withdrawals</a>
                        </li>
                        <li class="{{ Request::segment(1) === 'admin-review-list' ? 'active' : null }}">
                            <a href="{{route('adminreviewlist')}}">Reviews</a>
                        </li>
                        <li class="{{ Request::segment(1) === 'adminnotifications' ? 'active' : null }}">
                            <a href="{{route('adminnotifications')}}">Notifications</a>
                        </li>
                        </li>
                        <li class="{{ Request::segment(1) === 'income' ? 'active' : null }}">
                            <a href="{{route('adminincome')}}">Income</a>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
    </div>


</aside>
