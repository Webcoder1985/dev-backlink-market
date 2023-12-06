@extends('layout.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
          @if(\Auth::user()->user_status != 3)
           <div class="row clearfix">
              <div class="col-lg-3 col-md-6 col-sm-6">
                  <div class="card w_data_1">
                      <div class="body">
                          <div class="w_icon pink"><i class="zmdi zmdi-shopping-basket"></i></div>
                          <h4 class="mt-3 mb-0">{{ $linksCountBuyer}}</h4>
                          @if ($linksCountBuyer>0)
                              <a href="{{ route('buyerlinks') }}">  <span class="text-muted">Active Links</span></a>
                          @else
                              <span class="text-muted">Active Links</span>
                          @endif
                      </div>
                  </div>
              </div>

              <div class="col-lg-3 col-md-6 col-sm-6">
                  <div class="card w_data_1">
                      <div class="body">
                          <div class="w_icon pink"><i class="zmdi zmdi-money"></i></div>
                          <h4 class="mt-3 mb-0">&euro;{{ $linksAmountBuyer }}</h4>
                          @if ($linksCountBuyer>0)
                              <a href="{{ route('buyerlinks') }}">  <span class="text-muted">Monthly Costs</span></a>
                          @else
                              <span class="text-muted">Monthly Costs</span>
                          @endif
                      </div>
                  </div>
              </div>

            </div>
           <div class="row clearfix">
              <div class="col-lg-3 col-md-6 col-sm-6">
                  <div class="card w_data_1">
                      <div class="body">
                          <div class="w_icon pink"><i class="zmdi zmdi-shopping-basket"></i></div>
                          <h4 class="mt-3 mb-0">{{ $linksCountSeller}}</h4>
                          @if ($linksCountSeller>0)
                              <a href="{{ route('sellerlinks') }}">  <span class="text-muted">Active Sales</span></a>
                          @else
                              <span class="text-muted">Active Sales</span>
                          @endif
                      </div>
                  </div>
              </div>

              <div class="col-lg-3 col-md-6 col-sm-6">
                  <div class="card w_data_1">
                      <div class="body">
                          <div class="w_icon pink"><i class="zmdi zmdi-money"></i></div>
                          <h4 class="mt-3 mb-0">&euro;{{ $linksAmountSeller }}</h4>
                            @if ($linksAmountSeller>0)
                              <a href="{{ route('sellerlinks') }}">  <span class="text-muted">Monthly Earnings</span></a>
                          @else
                              <span class="text-muted">Monthly Earnings</span>
                          @endif
                      </div>
                  </div>
              </div>

            </div>
           <div class="row clearfix">
              <div class="col-lg-3 col-md-6 col-sm-6">
                  <div class="card w_data_1">
                      <div class="body">
                          <div class="w_icon pink"><i class="zmdi zmdi-pin-help"></i></div>
                           <h4 class="mt-3 mb-0">{{ $openTickets}}</h4>
                          @if ($openTickets>0)
                              <a href="{{ route('laravel-tickets.tickets.index') }}"><span class="text-muted">Open Support Tickets</span></a>
                          @else
                          <span class="text-muted">Open Support Tickets</span>
                          @endif

                      </div>
                  </div>
              </div>

              <div class="col-lg-3 col-md-6 col-sm-6">
                  <div class="card w_data_1">
                      <div class="body">
                          <div class="w_icon pink"><i class="zmdi zmdi-money"></i></div>
                         <h4 class="mt-3 mb-0">&euro;{{ $withdrawAmount }}</h4>
                          @if ($withdrawAmount>=50)
                          <a href="{{ route('withdrawrequest') }}"><span class="text-muted">Ready for Withdrawal</span></a>
                          @else
                          <span class="text-muted">Ready for Withdrawal</span>
                          @endif
                      </div>
                  </div>
              </div>

            </div>
          @else
            <div class="row clearfix">
              <div class="col-lg-3 col-md-6 col-sm-6">
                  <div class="card w_data_1">
                      <div class="body">
                          <div class="w_icon pink"><i class="zmdi zmdi-shopping-basket"></i></div>
                          <h4 class="mt-3 mb-0">{{ $orderCount }}</h4>
                          <span class="text-muted">Daily Orders</span>
                      </div>
                  </div>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-6">
                  <div class="card w_data_1">
                      <div class="body">
                          <div class="w_icon pink"><i class="zmdi zmdi-money"></i></div>
                          <h4 class="mt-3 mb-0">${{ $orderAmount }}</h4>
                          <span class="text-muted">Daily Order Amount</span>
                      </div>
                  </div>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-6">
                  <div class="card w_data_1">
                      <div class="body">
                          <div class="w_icon pink"><i class="zmdi zmdi-account-add"></i></div>
                          <h4 class="mt-3 mb-0">{{ $userCount }}</h4>
                          <span class="text-muted">Daily User Registered</span>
                      </div>
                  </div>
              </div>
            </div>
          @endif
        </div>
    </div>
</div>

@endsection

