<div class="footer">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-3 text-center text-lg-start wow fadeInUp" data-wow-duration="0.3s" data-wow-delay="0.3s"
                style="visibility: visible; animation-duration: 0.3s; animation-delay: 0.3s; animation-name: fadeInUp;">
                <div class="footer-box p-0">
                    <a href="{{ route('homepage')}}"><img src="{{asset('/images/logo.webp')}}" class="logo" alt="logo"></a>
                </div>
            </div>
            <div class="col-lg-6 text-center wow fadeInUp" data-wow-duration="0.4s" data-wow-delay="0.4s"
                style="visibility: visible; animation-duration: 0.4s; animation-delay: 0.4s; animation-name: fadeInUp;">
                <div class="footer-box p-0">
                    <ul class="footer-link">
                        <li><a href="{{ route('buy')}}">Buy Backlinks</a></li>
                        <li><a href="{{ route('sell')}}">Sell Backlinks</a></li>
                        <li><a href="{{ route('review')}}">Reviews</a></li>
                        <li><a href="{{ route('faq')}}">Faq</a></li>
                        <li><a href="{{ route('contact')}}">Contact</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 text-lg-end text-center wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.5s"
                style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.5s; animation-name: fadeInUp;">
                <div class="footer-box">
                    <div class="social-style">
                        <img src="{{asset('/images/pay.webp')}}" alt="payment method">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 wow fadeInUp" data-wow-duration="0.3s" data-wow-delay="0.3s"
                style="visibility: visible; animation-duration: 0.3s; animation-delay: 0.3s; animation-name: fadeInUp;">
                <div class="footer-bottom text-center">
                    <div class="d-flex justify-content-center mb-3 font-size-xs">
                        <a href="{{ route('terms')}}" >TOS</a><span style="font-size: 12px"> | </span>
                        <a href="{{ route('privacy')}}" >Privacy Policy</a><span style="font-size: 12px"> | </span>
                        <a href="{{ route('cookie-privacy')}}"> Cookie Policy</a>
                    </div>
                    <div class="content text-center">
                        <p class="text">© Copyright {{ date('Y') }}.
                            <a id="footer-hp-link" href="{{ route('homepage')}}">Backlink Market </a> - All Rights Reserved
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="scrollUp" title="Scroll To Top">
    <i class="fas fa-arrow-up"></i>
</div>
