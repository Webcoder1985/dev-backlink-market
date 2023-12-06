@extends('frontend.include.template')
@section('title','Frequently Asked Questions FAQs - Backlink Market')


@section('meta_description','Find answers to your questions! Collection of frequently asked questions.')
@section('meta_keywords','faqs, backlink market,Frequently Asked Questions')


@push("navtype")
    <x-frontend.layout.navbar :navType="5"/> {{-- Component //--}}
@endpush

@section("pagecontent")

    <div class="faq pt-xl-3" id="faq" >
        <div class="container">

            <div class="row">
                <div class="col-lg-12 pb-xl-5">
                    <div class="section-head">
                        <h3 class="text-center">Topics</h3>
                    </div>
                    <ul class="list-group list-group-flush" style="max-width: 200px;margin-left: auto;margin-right: auto;">
                        <li class="list-group-item text-center">
                            <a class="faqtxt list-item" href="#general">General</a>
                        </li>
                        <li class="list-group-item text-center">
                            <a class="faqtxt list-item" href="#pricing">Pricing / Payments</a>
                        </li>
                        <li class="list-group-item text-center">
                            <a class="faqtxt list-item" href="#buying">Buying</a>
                        </li>
                        <li class="list-group-item text-center">
                            <a class="faqtxt list-item" href="#selling">Selling</a>
                        </li>
                    </ul>
                </div>


            </div>

            <div class="row align-items-center justify-content-between">
                <div class="col-xl-12">
                    <div class="faq-box">

                        <div class="accordion" id="accordionExample">
                            <div class="row" style="text-align: center;"><h4 id="general" class="center">General</h4>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseTwo" aria-expanded="false"
                                            aria-controls="collapseTwo">
                                        <img src="{{asset('images/faq-icon.png')}}" alt="" class="icon">
                                        How safe is it to buy backlinks from the marketplace?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        Getting in-content backlinks from Backlink-Market.com is the most safest way of
                                        getting backlinks. You can check all link giving pages before you purchase a
                                        link. You can filter the Marketplace by SEO metrics, Categories, Language,
                                        Country and many more to find the perfect match for your website.
                                        Risk free backlinks with full control at fair prices. Google absolutely loves
                                        in-content links from relevant websites. Link building has never been so safe
                                        and easy.
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="text-align: center;padding-top: 60px;">
                                <h4 id="pricing" class="center"> Pricing and Payment</h4></div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseTwo" aria-expanded="false"
                                            aria-controls="collapseTwo">
                                        <img src="{{asset('images/faq-icon.png')}}" alt="" class="icon">
                                        What payment option do you provide?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        We offer Credit Card and PayPal payment options. Payments are handled by PayPro
                                        Global.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne" aria-expanded="false"
                                            aria-controls="collapseOne">
                                        <img src="{{asset('images/faq-icon.png')}}" alt="" class="icon">
                                        What are the prices for backlinks on your marketplace?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        The prices starts from 1{{Config::get('app.currency_symbol')}} per link per
                                        month and depends on the URLs
                                        metric. The higher and more powerful the URL the higher the price.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseThree" aria-expanded="false"
                                            aria-controls="collapseThree">
                                        <img src="{{asset('images/faq-icon.png')}}" alt="" class="icon">
                                        Are the prices monthly or one time?
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse"
                                     aria-labelledby="headingThree"
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        All prices are monthly. You pay a price for a whole month from 1st to 1st of
                                        next month.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseFour" aria-expanded="false"
                                            aria-controls="collapseFour">
                                        <img src="{{asset('images/faq-icon.png')}}" alt="" class="icon">
                                        How do you handle the payment process? Because I noticed partly payments.
                                    </button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        When you place your first order you automatically allow us to bill you for your
                                        active links on each month.
                                        We bill you for all currently active links on the 1st day of a month. You pre
                                        pay for the whole month.
                                        This is very comfortable for you. You can stop the backlinks at any time and so
                                        we do not bill you next month for the stopped backlinks.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFive">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseFive" aria-expanded="false"
                                            aria-controls="collapseFive">
                                        <img src="{{asset('images/faq-icon.png')}}" alt="" class="icon">
                                        What happens when I place my order in the middle of a month?
                                    </button>
                                </h2>
                                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        We count the remaining days for this month and calculate the price for the
                                        backlink based on the monthly price.
                                        Means, you just pay a part of the monthly price, exactly the price for the
                                        remaining days.
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="text-align: center;padding-top: 60px;"><h4 id="buying" class="center"> Buying</h4></div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingSix">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseSix" aria-expanded="false"
                                            aria-controls="collapseSix">
                                        <img src="{{asset('images/faq-icon.png')}}" alt="" class="icon">
                                        What happens when my link is offline?
                                    </button>
                                </h2>
                                <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix"
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        All purchased backlinks getting automatically checked and verified once per day.
                                        When your link pops up as offline we ask the blog owner to immediately fix the
                                        link.
                                        If the link is not fixed within 5 days we refund the costs for this link to your
                                        balance and stop the order.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingSeven">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseSeven" aria-expanded="false"
                                            aria-controls="collapseSeven">
                                        <img src="{{asset('images/faq-icon.png')}}" alt="" class="icon">
                                        Do I get the blog post URL so I can verify the link?
                                    </button>
                                </h2>
                                <div id="collapseSeven" class="accordion-collapse collapse"
                                     aria-labelledby="headingSeven"
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        Yes, you can inspect the URL before you purchase a link from the blog post.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingEight">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseEight" aria-expanded="false"
                                            aria-controls="collapseEight">
                                        <img src="{{asset('images/faq-icon.png')}}" alt="" class="icon">
                                        What marketplace filter option can be selected to find the best matching link
                                        for me?
                                    </button>
                                </h2>
                                <div id="collapseEight" class="accordion-collapse collapse"
                                     aria-labelledby="headingEight"
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        You can filter the marketplace by country, language, Maj. TF, Maj. CF, MOZ DA,
                                        MOZ PA, RD (Referring Domains), OBL (OutBound Links), price, category and TLD
                                        (TopLevel Domain).
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingNine">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseNine" aria-expanded="false"
                                            aria-controls="collapseNine">
                                        <img src="{{asset('images/faq-icon.png')}}" alt="" class="icon">
                                        Where does the link gets placed (Sidebar, Blog Posts content, footer etc) ?
                                    </button>
                                </h2>
                                <div id="collapseNine" class="accordion-collapse collapse" aria-labelledby="headingNine"
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        We strictly offer only the best quality and so all links are be placed within
                                        the content of each Blog Post. You can also create a surrounding content of max.
                                        200 characters for each link (optional).
                                        We do not offer Footer or Sidebar links. These links will do more harm than
                                        good.
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="text-align: center;padding-top: 60px;"><h4 id="selling" class="center">Selling</h4></div>


                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTen">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseTen" aria-expanded="false"
                                            aria-controls="collapseTen">
                                        <img src="{{asset('images/faq-icon.png')}}" alt="" class="icon">
                                        How can I earn money with my Blog?
                                    </button>
                                </h2>
                                <div id="collapseTen" class="accordion-collapse collapse" aria-labelledby="headingTen"
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        Just register an account, add our Wordpress Plugin to your Blog and add the
                                        verification key. We automatically evaluate your blog posts with SEO metrics and
                                        suggest a matching price with our smart pricing algorithm. You also have the
                                        option to skip some blog posts from getting
                                        added to our marketplace.
                                        As soon as a buyer purchases a link from your blog, the plugin automatically
                                        adds the link to the blog post and you get credited for it on the 2nd day of
                                        each month for the previous month. We sum up all your earnings from your active
                                        links from last month to be ready for withdrawal.
                                        The whole process is fully automated. Lean back and make money on autopilot.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTen">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseTen" aria-expanded="false"
                                            aria-controls="collapseTen">
                                        <img src="{{asset('images/faq-icon.png')}}" alt="" class="icon">
                                        How do I get paid?
                                    </button>
                                </h2>
                                <div id="collapseTen" class="accordion-collapse collapse" aria-labelledby="headingTen"
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        You need a paypal account to receive your monthly earnings. You can request a
                                        withdrawal when your earnings balance is above
                                        50{{Config::get('app.currency_symbol')}}. You can request a
                                        withdrawal once per month.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTen">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseTen" aria-expanded="false"
                                            aria-controls="collapseTen">
                                        <img src="{{asset('images/faq-icon.png')}}" alt="" class="icon">
                                        Is there a min. metric to get a blog post listed on the marketplace?
                                    </button>
                                </h2>
                                <div id="collapseTen" class="accordion-collapse collapse" aria-labelledby="headingTen"
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        Yes. Your blog post needs to have a min. Maj. TF of 10 and it needs to be indexed in google.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTen">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseTen" aria-expanded="false"
                                            aria-controls="collapseTen">
                                        <img src="{{asset('images/faq-icon.png')}}" alt="" class="icon">
                                        How many Blogs can I add?
                                    </button>
                                </h2>
                                <div id="collapseTen" class="accordion-collapse collapse" aria-labelledby="headingTen"
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        Unlimited. The more blog posts you add the more you can earn.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTen">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseTen" aria-expanded="false"
                                            aria-controls="collapseTen">
                                        <img src="{{asset('images/faq-icon.png')}}" alt="" class="icon">
                                        Can I exclude blog posts from getting listed on the marketplace?
                                    </button>
                                </h2>
                                <div id="collapseTen" class="accordion-collapse collapse" aria-labelledby="headingTen"
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        Yes. You can exclude any blog post from getting listed on the marketplace. The
                                        members area offers a Site Management where you can easily remove, edit your
                                        pages.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row text-center pt-xl-5">
                <p class="text">
                            If You haven’t found an answer to your question,
                            <a href="{{route('contact')}}" class="faqtxt"><strong>Contact us</strong></a>
            </p>
             </div>
        </div>
    </div>

@endsection
