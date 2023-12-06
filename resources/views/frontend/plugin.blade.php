@extends('frontend.include.template')
@section('title','Wordpress Plugin - Backlink Market')


@section('meta_description','Wordpress Plugin to monetize your wordpress blog on autopilot. Making money has never been so easy! Sell backlinks on your Wordpress Blog. ')
@section('meta_keywords','wordpress plugin backlink market, WP Plugin, Blog Backlinks, make money,sell backlinks, sell links, monetize blog, make money wordpress')


@push("navtype")
<x-frontend.layout.navbar :navType="8" /> {{-- Component //--}}
@endpush

@section("pagecontent")
<div class="manage pb-5 mt-5">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-xl-6 col-lg-10 text-center">
                <div class="section-head">
                    <h3 class="subtitle ">How it works?</h3>
                    <h2 class="title ">Easy and Fast </h2>
                    <p class="text ">
                        It takes just 3 Steps to monetize your website!
                    </p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-5">
            <div class="col-xl-4 col-md-6 ">
                <div class="manag-box text-center">
                    <div class="thumb">
                        <img src="{{asset('images/order-1.png')}}" alt="">
                    </div>
                    <div class="content">
                        <h3 class="subtitle-2">1) Register</h3>
                        <p class="text-3"> Sign up for an account.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 ">
                <div class="manag-box text-center">
                    <div class="thumb">
                        <img src="{{asset('images/submit.png')}}" alt="">
                    </div>
                    <div class="content">
                        <h3 class="subtitle-2">2) Install Plugin</h3>
                        <p class="text-3">Install our secure <a style="color: #3AB0E2" href="{{route('download-plugin')}}">WP Plugin</a> to your blog.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 ">
                <div class="manag-box text-center">
                    <div class="thumb">
                        <img src="{{asset('images/earn-money.png')}}" alt="">
                    </div>
                    <div class="content">
                        <h3 class="subtitle-2">3) Earn Money</h3>
                        <p class="text-3">Simply wait for orders to roll in, and collect
                            your earnings every month!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="global-rates mb-5" id="ourtool">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-6  d-xl-block">
                <div class="">
                    <img src="{{asset('images/wordpress_plugin_screenshot.png')}}" alt="" class="global-img">
                </div>
            </div>
            <div class="col-xl-6 ">
                <div class="section-head">
                    <h2 class="title ">Why should I use this Plugin?
                    </h2>
                    <p class="text  ourtools" data-wow-duration="0.5s" data-wow-delay="0.4s"
                       style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.4s;">
                       When installed, the Plugin automatically handles all the hassle for you.
                    </p>
                    <p class="text mb-0 mt-3 ourtools" data-wow-duration="0.5s" data-wow-delay="0.4s"
                       style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.4s;">
                        <b></b><ol class="mb-0">
                        <li>Automatically pulls your Blog Posts URLs</li>
                        <li>Automatically evaluates the URLs by SEO Metrics</li>
                        <li>Automatically sets a price and lists the URLs in our Marketplace</li>
                        <li>Automatically Adds and Removes Backlinks from your blog posts</li>
                        </ol>

                    </p>
                    <p class="text text-center mt-0 ourtools" data-wow-duration="0.5s" data-wow-delay="0.4s"
                       style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.4s;">
                        <b>The best of all? It’s free and super easy to use!</b>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
 <div class="tril pt-0 pb-0" style="background-color: #f4f8fd" id="contact">
    <div class="container">
       <div class="row justify-content-center">
          <div class="col-md-12">
             <div class="content pt-5 pb-5">
                <div class="row justify-content-center">
                   <div class="col-lg-8 text-center">
                      <div class="section-head">
                         <h2 class="title text-dark">Backlink Market Wordpress Plugin
                         </h2>
                         <p class="text text-dark" >
                         </p>
                         <a href="{{route('download-plugin')}}" class="button-1 one Seereviews mt-4">Download</a>
                      </div>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </div>
 </div>

<div class="workflow pt-5 pb-3" id="howworks">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-xl-12 col-lg-12 mb-xl-3 text-center ">
                <div class="section-head mb-4">
                    <h2 class="title">Monetizing your website has never been so easy!
                    </h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-3 col-lg-4 col-md-6 ">
                <div class="workflow-box">
                    <div class="thumb-box one d-flex align-items-center justify-content-center">
                        <div class="icon">
                            <img src="{{asset('images/shield.png')}}" alt="">
                        </div>
                    </div>
                    <div class="content text-center">
                        <h3 class="log">Save to Use</h3>
                        <h3 class="hundrd">100%</h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 ">
                <div class="workflow-box">
                    <div class="thumb-box one d-flex align-items-center justify-content-center">
                        <div class="icon">
                            <img src="{{asset('images/save-for-use.png')}}" alt="">
                        </div>
                    </div>
                    <div class="content text-center">
                        <h3 class="log">Free to Use</h3>
                        <h3 class="hundrd">100%</h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 ">
                <div class="workflow-box three">
                    <div class="thumb-box one d-flex align-items-center justify-content-center">
                        <div class="icon">
                            <img src="{{asset('images/easyto-use.png')}}" alt="">
                        </div>
                    </div>
                    <div class="content text-center">
                        <h3 class="log">Easy to Use</h3>
                        <h3 class="hundrd">100%</h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 ">
                <div class="workflow-box">
                    <div class="thumb-box one d-flex align-items-center justify-content-center">
                        <div class="icon">
                            <img src="{{asset('images/grp6.png')}}" alt="">
                        </div>
                    </div>
                    <div class="content text-center">
                        <h3 class="log">Excellent Pricing</h3>
                        <h3 class="hundrd">100%</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


 <div class="tril pt-0 pb-0" style="background-color: #f4f8fd" id="contact">
    <div class="container">
       <div class="row justify-content-center">
          <div class="col-md-12">
             <div class="content pt-5 pb-5">
                <div class="row justify-content-center">
                   <div class="col-lg-8 text-center">
                      <div class="section-head">
                         <h2 class="title text-dark"> What are you waiting for?
                         </h2>
                         <p class="text text-dark" > It's time to make money!
                         </p>
                         <a href="{{route('register')}}" class="button-1 one Seereviews mt-4">Sign up!</a>
                      </div>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </div>
 </div>
{{-- faq Start --}}
<div class="faq pt-5 pb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 text-center ">
                <div class="section-head">
                    <h2 class="title ">Frequently Asked Questions</h2>
                    <p class="text ">
                        We’ve compiled some of the most frequently asked questions and answered them below. <br>
                        If there’s a topic we haven’t covered, please  <a style="color: #3AB0E2" href="{{ route('contact')}}">contact us</a>!
                    </p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="faq-box">
                    <div class="accordion" id="accordionExample">
                         <div class="accordion-item ">
                            <h2 class="accordion-header" id="headingZero">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseZero" aria-expanded="false" aria-controls="collapseZero">
                                    How exactly does it work?
                                    <span></span>
                                </button>
                            </h2>
                            <div id="collapseZero" class="accordion-collapse collapse" aria-labelledby="headingZero"
                                 data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    The plugin is designed to make it super easy for you to make money with your blog:
                                    <ol>

                                        <li><a style="color: #3AB0E2" href="{{ route('register')}}">Create an account</a> or <a style="color: #3AB0E2" href="{{ route('login')}}">login</a></li>
                                        <li>Install <a style="color: #3AB0E2" href="{{ route('download-plugin')}}">Plugin</a> to your blog and activate it.</li>
                                        <li><a style="color: #3AB0E2" href="{{route('seller_sites')}}?add_site=true">Add your Blog URL to your Sites list</a>.</li>
                                        <li>Add the Auth Key to the Plugin Settings on your blog.</li>
                                        <li>Go to the <a style="color: #3AB0E2" href="{{route('seller_sites')}}">Dashboard</a> and confirm the auth key has been added.</li>
                                        <li>Your blog is now verified and we automatically pull all URLs from your blog. You can deselect the blog posts you don't want to get listed</li>
                                        <li>We calculate a price based on the SEO Metrics. This process starts right away and may take up to 30 minutes. Depending on the amount of blogs posts.</li>
                                        <li>Your blog posts are now listed on our <a style="color: #3AB0E2" href="{{route('marketplace')}}">Marketplace</a> and available for buyers.</li>
                                        <li>Wait for orders to roll in. You can request a <a style="color: #3AB0E2" href="{{route('mywithdrawlist')}}">withdrawal</a> of your earning via PayPal.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item ">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    Is this service free to use?
                                    <span></span>
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                                 data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    Yes! Our platform is free to use. There are no fees or hidden charges for selling
                                    backlinks through our platform.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item ">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    I'm not sure anyone wants a link from my website
                                    <span></span>
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                 data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    Our advertisers and platform users are always looking for legitimate backlink
                                    opportunities across all niches (even gambling and adult niches!).
                                    Registering and listing your website is free, so we encourage you to give it a try!
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item ">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    How do you assign a price to my website's backlink?
                                    <span></span>
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                                 data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    We consider several variables like Majestic and MOZ metrics but many more. We assign
                                    a price for your backlink based on our evaluation of your blog post. Nevertheless,
                                    you can change the price at any time.
                                    Lower prices means a higher possibility of getting a sale but you get less earnings
                                    per sale. Best way is to stick with our automated pricing.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item ">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    Is this platform easy to use?
                                    <span></span>
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                                 data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    Yes. We use a custom-made WordPress plugin to interact with your website. Just
                                    register an account and upload the Wordpress plugin to your Wordpress Blog. Enter
                                    the authentication key we've provide to the installed Wordpress Plugin on your blog.
                                    The blog post URLs gets automatically transferred to our system. You can remove any
                                    blog post of your choice at any time, even at the initial transfer. Just in case you
                                    have some blog posts you don't' want to be listed on the marketplace.
                                    We evaluate your URLs and set a price. Right after that your URLs gets listed on the
                                    marketplace. It just takes you minutes to get your website listed. Now, wait for the
                                    orders to roll in.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item ">
                            <h2 class="accordion-header" id="headingFive">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFour">
                                    Is this service safe to use?
                                    <span></span>
                                </button>
                            </h2>
                            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    Absolutely! Our platform is coded from scratch and comes with rigorous security
                                    measures. Only verified users are able to conduct trades, guaranteeing you get
                                    regular payouts from any processed orders.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item ">
                            <h2 class="accordion-header" id="headingSix">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseFour">
                                    I'd like to start earning money. What are the requirements?
                                    <span></span>
                                </button>
                            </h2>
                            <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="panel-body toggle-content fusion-clearfix">
                                        <p>Our requirements are as follows:</p>
                                        <ul>
                                            <li>Your blog needs to run with Wordpress.</li>
                                            <li>The page you’ll link from must be indexed in Google</li>
                                            <li>Adult and gambling websites are allowed but must be listed as such and placed in the correct category.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item ">
                            <h2 class="accordion-header" id="headingSeven">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseFour">
                                    How to get Paid? What are your withdrawal options?
                                    <span></span>
                                </button>
                            </h2>
                            <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven"
                                 data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    We work with PayPal to withdrawal your earnings. The min. payout amount is 50 Euros.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item ">
                            <h2 class="accordion-header" id="headingEight">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseFour">
                                    Do I have to monitor my sales?
                                    <span></span>
                                </button>
                            </h2>
                            <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight"
                                 data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    Nope! Our "Set it & Forget it" approach allows our system to automatically add/edit
                                    or remove any purchased backlinks. All we require is for you to validate your
                                    website on our platform.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item ">
                            <h2 class="accordion-header" id="headingNine">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseNine" aria-expanded="false" aria-controls="collapseFour">
                                    How do I validate my website?
                                    <span></span>
                                </button>
                            </h2>
                            <div id="collapseNine" class="accordion-collapse collapse" aria-labelledby="headingNine"
                                 data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    First, you need to register on our platform. Install our Wordpress plugin to your
                                    blog and enter the authentication key.
                                    Your URLs gets automatically submitted to our platform, analyzed, priced and listed
                                    on our marketplace. You can edit, remove your blogs at any time.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="feature pt-0  pb-5">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-xl-5 d-xl-block">
                <div class="section-thumb">
                    <img src="{{asset('images/help.png')}}" alt="" class="fimg-2">
                </div>
            </div>
            <div class="col-xl-7 text-center">
                <div class="section-head">
                    <h2 class="title">Any Question?</h2>
                    <p class="text  ourtools">
                        We are here to help you.<br>
                        Don't hesitate to contact us.
                    </p>
                </div>
                 <div class="text-center mt-4 ">
                    <a href="{{ route('contact')}}" class="button-1 one Seereviews rounded-1"><i
                            class="far fa-comment" style="padding-right: 10px"></i>Contact us</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
