@extends('frontend.include.template')
@section('title','Buy and Sell Backlinks - Backlink Market')

{{-- SEO --}}
@section('meta_description','Backlink Market - Buy and Sell Backlinks - Monetizing your website and climbing the SEO rankings has never been easier.')
@section('meta_keywords','Buy Backlinks, Sell Backlinks, Backlink Market, Link Market, Marketplace, Backlinks, Links')
{{-- SEO--}}

@push("navtype")
<x-frontend.layout.navbar :navType="1"/> {{-- Component //--}}
@endpush
@section("pagecontent")

<div class="global-rates" id="ourtool">
    <div class="container">

       <div class="row align-items-center">
          <div class="col-xl-6  d-xl-block">
             <div class="section-thumb" >
                <img  src="{{asset('images/buylinks.webp')}}" alt="" class="global-img">
             </div>
          </div>
           <div class="col-xl-6" >
               <div class="section-head">
                   <h2 class="title" >Buy Backlinks</h2>
                   <h3 class="subtitle buylinks">We offer 100% SEO friendly, natural backlinks from
                       trustworthy  websites.
                   </h3>
               </div>
               <ul class="list">
                   <li class="list-item">
                       <div class="thumb"><img src="{{asset('images/grp1.png')}}" alt=""></div>
                       <div class="content">
                           <p class="textbulletform">Register to access our link inventory</p>
                       </div>
                   </li>
                   <li class="list-item" >
                       <div class="thumb"><img src="{{asset('images/grp2.png')}}" alt=""></div>
                       <div class="content">
                           <p class="textbulletform">Browse our link inventory, hand pick your links</p>
                       </div>
                   </li>
                   <li class="list-item" >
                       <div class="thumb"><img src="{{asset('images/grp3.png')}}" alt=""></div>
                       <div class="content">
                           <p class="textbulletform">Link goes live right after payment</p>
                       </div>
                   </li>
               </ul>
               <div class="mt-5">
                <a href="{{route('register')}}" class="button-2 one cusbuylink-btn"><i class="fas fa-cart-plus m-1 "></i> Buy Backlinks</a>
               </div>
           </div>
       </div>
    </div>
 </div>
 <div class="global-rates" id="ourtool">
    <div class="container">
       <div class="row align-items-center">
             <div class="col-xl-6 " >
             <div class="section-head">
                <h2 class="title" >Sell Backlinks</h2>
                <h3 class="subtitle buylinks" >With our “Set it & Forget It” system,
                   earning a passive income is easier than ever!
                </h3>
             </div>
             <ul class="list">
                <li class="list-item" >
                   <div class="thumb"><img src="{{asset('images/grp4.png')}}" alt=""></div>
                   <div class="content">
                      <p class="textbulletform">Register & validate your website</p>
                   </div>
                </li>
                <li class="list-item" >
                   <div class="thumb"><img src="{{asset('images/grp5.png')}}" alt=""></div>
                   <div class="content">
                      <p class="textbulletform">
                         Install our easy to use WordPress plugin
                      </p>
                   </div>
                </li>
                <li class="list-item" >
                   <div class="thumb"><img src="{{asset('images/grp6.png')}}" alt=""></div>
                   <div class="content">
                      <p class="textbulletform">Collect your earnings</p>
                   </div>
                </li>
             </ul>
             <div class="mt-5">
                <a href="{{ route('register')}}" class="button-3"><i class="fas fa-hand-holding-usd m-1 "></i> Sell Backlinks</a>
             </div>
          </div>
          <div class="col-xl-6  d-xl-block">
             <div class="section-thumb " >
                <img src="{{asset('images/selllinks.webp')}}" alt="" class="global-img">
             </div>
          </div>

       </div>
    </div>
 </div>
 <div class="global-rates mt-5" id="ourtool">
    <div class="container">
       <div class="row align-items-center">
          <div class="col-xl-6 d-xl-block">
             <div class="section-thumb ">
                <img src="{{asset('images/why-shuuold-i.webp')}}" alt=""   class="global-img">
             </div>
          </div>
           <div class="col-xl-6 " >
             <div class="section-head">
                <div class="icon d-flex align-items-center justify-content-center" >
                   <img src="{{asset('images/f-icon-1.png')}}" alt="">
                </div>
                <h2 class="title" >Why should I buy?</h2>
                <p class="text" >
                   Monetizing your website and climbing the SEO rankings has never been
                   easier. Hand pick links from laser targeted niche blogs.
                </p>
                <p class="text mt-3">
                   Our platform is the #1 link hub today. We offer 100% Whitehat SEO
                   backlink opportunities from trusted sources that will help you beat
                   your competitors in record time.
                </p>
                <p class="text mt-3" >
                   We also help you sell backlink opportunities from your website. Our platform represents an ecosystem that’ll help you find customers looking for link possibilities on blogs like yours.
                </p>
             </div>
          </div>
       </div>
    </div>
 </div>
 {{-- Steps Start --}}
 <div class="steps" id="about">
    <div class="container">
       <div class="row align-items-center justify-content-center">
          <div class="col-xl-8 col-lg-10 text-center">
             <div class="section-head">
                <h3 class="subtitle" >Stellar Reviews</h3>
                <h2 class="title" >Don’t just take <br/>
                   our word for it!
                </h2>
                <p class="text" >
                   <strong class="cusrv">See what our customers had to say about us! </strong>
                </p>
             </div>
          </div>
       </div>
    </div>
 </div>
 {{-- testomonial Start --}}

 <div class="testomonial" >
    <div class="container">
       <div class="row">
          <div class="col-12">
            <div class="testo-box owl-carousel owl-theme">
                <div class="single item">
                    <div class="person">
                        <div class="mt-3 mb-3">
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star checked"></span>
                        <span class="fa fa-star"></span>
                        <span class="fa fa-star"></span>
                    </div>


                    </div>
                    <div class="content">
                        <h2 class="lasthead">
                            “Great service, fast, helpful and efficient!”
                        </h2>
                        <h5 class="name">Martha Vargas</h5>
                    </div>
                </div>
                <div class="single item">
                    <div class="person">
                        <div class="mt-3 mb-3">
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star"></span>
                            <span class="fa fa-star"></span>
                        </div>


                    </div>
                    <div class="content">
                        <h2 class="lasthead">
                            “Very helpful excellent service always answering”
                        </h2>
                        <h5 class="name">Chris Bates</h5>
                    </div>
                </div>
                <div class="single item">
                    <div class="person">
                        <div class="mt-3 mb-3">
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star"></span>
                            <span class="fa fa-star"></span>
                        </div>


                    </div>
                    <div class="content">
                        <h2 class="lasthead">
                            “ Answering all my questions And the most”
                        </h2>
                        <h5 class="name">Alfredo Harrison</h5>
                    </div>
                </div>
                <div class="single item">
                    <div class="person">
                        <div class="mt-3 mb-3">
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star"></span>
                            <span class="fa fa-star"></span>
                        </div>


                    </div>
                    <div class="content">
                        <h2 class="lasthead">
                            “All the help keep up the good work”
                        </h2>
                        <h5 class="name">Ellis Phillips</h5>
                    </div>
                </div>
                <div class="single item">
                    <div class="person">
                        <div class="mt-3 mb-3">
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star"></span>
                            <span class="fa fa-star"></span>
                        </div>


                    </div>
                    <div class="content">
                        <h2 class="lasthead">
                            “Good work”
                        </h2>
                        <h5 class="name">Andrew Owens</h5>
                    </div>
                </div>
                <div class="single item">
                    <div class="person">
                        <div class="mt-3 mb-3">
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star"></span>
                            <span class="fa fa-star"></span>
                        </div>


                    </div>
                    <div class="content">
                        <h2 class="lasthead">
                            “Very helpful excellent service always ”
                        </h2>
                        <h5 class="name">Myron Castillo</h5>
                    </div>
                </div>
            </div>
          </div>
       </div>
    </div>
 </div>
 <div class="feature pt-5">
    <div class="container">
       <div class="row align-items-center justify-content-between">
          <div class="col-xl-12 ">
             <div class="text-center">
                 <a href="{{ route('review')}}" class="button-1 one Seereviews">See Reviews</a>
             </div>
          </div>
       </div>
    </div>
 </div>
 <div class="global-rates" id="ourtool">
    <div class="container">
       <div class="row align-items-center">
          <div class="col-xl-6 d-xl-block">
             <div class="section-thumb" >
                <img src="{{asset('images/platform.webp')}}" alt="" class="global-img">
             </div>
          </div>
          <div class="col-xl-6 " >
             <div class="section-head">
                <div class="icon d-flex align-items-center justify-content-center ">
                   <img src="{{asset('images/f-icon-1.png')}}" alt="">
                </div>
                <h2 class="title">
                   Our Tools and Platform
                </h2>
                <p class="text ourtools" >
                   Backlink Market offers an easy-to-use online catalog that allows you to
                   hand-pick backlinks you’d like to purchase for your website.
                </p>
                <p class="text mt-3 ourtools" >
                   Our inventory offers are all verified by our staff and tools to ensure you
                   get the best backlinks for your money.
                </p>
             </div>
          </div>
       </div>
    </div>
 </div>
 {{-- Our Mission Start --}}
 <div class="mission" id="mission" style="background-image: url({{asset('images/mission-bg.png')}});">
    <div class="container">
       <div class="row align-items-center justify-content-between">
          <div class="col-xl-7">
             <div class="section-head">
                <h2 class="title" >Premium links, Minus the Hastle</h2>
                <p class="text" >
                   Instinctive programming and cards structured together for the manner
                   in which organizations really work.
                </p>
                <ul class="list">
                   <li class="list-item" >
                      <span class="check"><img src="{{asset('images/check.png')}}" alt=""></span>
                      <p class="text">Most SEO experts agree: acquiring high-quality, organic backlinks is difficult.</p>
                   </li>
                   <li class="list-item" >
                      <span class="check"><img src="{{asset('images/check.png')}}" alt=""></span>
                      <p class="text">We make it easy.</p>
                   </li>
                   <li class="list-item" >
                      <span class="check"><img src="{{asset('images/check.png')}}" alt=""></span>
                      <p class="text">We offer 100% SEO friendly, natural backlinks from trustworthy websites. These websites will link you straight from their blog content, to ensure maximum SEO value.</p>
                   </li>
                </ul>
                <a href="{{ route('register')}}" class="button-1 one Seereviews mt-4"><i class="fas fa-cart-plus m-1 "></i>Buy Backlinks</a>
             </div>
          </div>
          <div class="col-xl-5 d-xl-block">
             <div class="pic" >
                <img src="{{asset('images/laptop-man.webp')}}" alt="" class="laptop-man">
             </div>
          </div>
       </div>
    </div>
 </div>
 {{-- Recurring Start --}}
 <div class="global-rates pb-5" id="about">
    <div class="container">
       <div class="row align-items-center">
          <div class="col-xl-5 d-xl-block">
             <div class="section-thumb " >
                <img src="{{asset('images/feature-img-2.webp')}}" alt="" class="global-img">
             </div>
          </div>
          <div class="col-xl-7 ">
             <div class="section-head">
                <h2 class="title" >Earn Monthly Recurring Income</h2>
                <p class="text" >
                   Instinctive programming and cards structured together for the manner
                   in which organizations really work.
                </p>
                <ul class="list">
                   <li class="list-item" >
                      <span class="check"><img src="{{asset('images/check.png')}}" alt=""></span>
                      <p class="text">With our “Set it & Forget It” system, earning passive income is easier than ever!</p>
                   </li>
                   <li class="list-item" >
                      <span class="check"><img src="{{asset('images/check.png')}}" alt=""></span>
                      <p class="text">Our secure platform validates your site and automatically adds, edits or removes purchased links.</p>
                   </li>
                   <li class="list-item" >
                      <span class="check"><img src="{{asset('images/check.png')}}" alt=""></span>
                      <p class="text">All you need to do is withdraw your earnings using your preferred payment method.</p>
                   </li>
                </ul>
                <a href="{{ route('register')}}" class="button-1 one Seereviews mt-4"><i class="fas fa-hand-holding-usd m-1 "></i> Sell Backlinks</a>
             </div>
          </div>
       </div>
    </div>
 </div>
 {{-- tril Start --}}
 <div class="tril pt-0 pb-0" id="contact">
    <div class="container">
       <div class="row justify-content-center">
          <div class="col-md-12">
             <div class="content">
                <div class="row justify-content-center">
                   <div class="col-lg-8 text-center">
                      <div class="section-head">
                         <h2 class="title text-dark"> Now
                            it's time to switch old ways to track expenses smarter
                         </h2>
                         <p class="text text-dark" >What
                            are you waiting for? It's time to get started.
                         </p>
                         <a href="{{route('register')}}" class="button-1 one Seereviews mt-4">Sign up! It's free</a>
                      </div>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </div>
 </div>
@endsection

@push('customjs')


@endpush

