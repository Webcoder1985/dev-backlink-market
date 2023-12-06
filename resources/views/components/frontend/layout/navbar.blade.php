<div class="" style="background-image: url( {{asset('images/banner-bg.webp')}}); background-size:cover; background-repeat:no-repeat">
    {{-- Main-menu Strat --}}
    <?php
    $option = \App\Models\Option::find(1);
    $reviews = \DGvai\Review\Review::active()->get();
    ?>
    <div class="mein-menu">
        {{-- Reviw rating and short menu--}}
        <div class="container">
            <div class="row">
                <div class="col-7" style="text-align:right">
                    <div class="rating">
                        <?php $i = 1;
                        $firsttime = true;
                        while ($i <= 5) {
                            if ($option->rating >= $i) {

                                echo '<i class="fa fa-star checked"></i>';
                            } else {
                                if ($firsttime) {
                                    $custom = $option->rating - intval($option->rating);
                                    $firsttime = false;
                                    echo '<i class="fa fa-star custom custom-header"></i>';
                                } else {
                                    echo '<i class="fa fa-star"></i>';
                                }

                            }

                            $i++;
                        }
                        ?>
                        <style>
                            .fa.fa-star.custom:before {
                                width: <?php echo $custom * 100;?>%;
                            }

                            .fa.fa-star.custom-header:before {
                                left: -10px;
                            }
                        </style>

                        <span style="margin-left:10px"> <?php echo number_format($option->rating, 1); ?>/5</span>
                        <a href="{{route('review')}}"><span style="color:#019BDB">(<?php echo count($reviews); ?> reviews)</span></a>
                    </div>
                </div>
                <div class="col-5" style="text-align:right; float:right">
                    <a href="{{route('plugin')}}" class="topmenu" style="margin-right:20px">WP-Plugin</a>
                    <a href="{{route('contact')}}" class="topmenu" style="margin-right:20px">Contact</a>

                    <?php if (Auth::guest()){ ?>
                    <a href="{{route('login')}}" class="topmenu" to="login">Login</a>
                    <?php } else { ?>
                    <a href="{{route('dashboard')}}" class="topmenu" to="logout" style="margin-right:20px">Dashboard</a>
                    <a href="{{route('logout')}}" class="topmenu" to="logout">Logout</a>
                    <?php } ?>
                </div>
            </div>
        </div>
        {{-- end of review rating and short menu--}}
        <nav class="navbar navbar-expand-lg navbar-dark ">
            <div class="container">
                <a class="navbar-brand" href="{{route('homepage')}}">
                    <img src="{{asset('images/logo.png')}}" class="logo" alt="logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="{{ route('homepage')}}" class="nav-link  @if(request()->routeIs('homepage')) active @endif ">Home</a>
                        </li>
                        <li class="nav-item" style="min-width: 120px;">
                            <a href="{{ route('buy')}}" class="nav-link  @if(request()->routeIs('buy')) active @endif ">Buy
                                Backlinks</a>
                        </li>
                        <li class="nav-item" style="min-width: 120px;">
                            <a href="{{ route('sell')}}" class="nav-link @if(request()->routeIs('sell')) active @endif ">Sell
                                Backlinks</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('review')}}" class="nav-link @if(request()->routeIs('review')) active @endif">Reviews</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('faq')}}" class="nav-link @if(request()->routeIs('faq')) active @endif">FAQs</a>
                        </li>
                        {{--<li class="nav-item">
                            <a to="login" class="nav-link login">Login</a>
                        </li>//--}}
                        <li class="nav-item">
                            <a href="{{ route('register')}}" class="nav-link button-1 cus-btn text-white" style="width: 160px !important; color:#fff !important">Get
                                Started</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    {{-- Main-menu End --}}
    @if($navType==1)
        {{-- Home Header --}}
        <div class="banner wow fadeInUp" data-wow-duration="0.3s" data-wow-delay="0.3s" id="home">
            <div class="hero-area">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-xl-7 col-lg-7 text-center text-xl-start">
                            <div class="banner-content pt-5 pb-5">
                                <h3 class="subtitle wow fadeInDown" data-wow-duration="0.3s" data-wow-delay="0.3s">
                                    Marketplace </h3>
                                <h1 class="head wow fadeInDown" data-wow-duration="0.3s" data-wow-delay="0.3s"> Backlink
                                    Market Has You Covered!</h1>
                                <p class="text wow fadeInDown" data-wow-duration="0.3s" data-wow-delay="0.3s">
                                    Are you looking for genuine, high quality backlinks? Explore our marketplace.<br>
                                    Do you want to make money with your blog? List your Wordpress blog on our
                                    marketplace.<br>


                                </p>
                                <div class="link-box wow fadeInUp" data-wow-duration="0.3s" data-wow-delay="0.3s">
                                    <a href="{{ route('buy')}}" class="button-1 one mb-3"><i class="fas fa-cart-plus m-1"></i>
                                        Buy Backlinks</a>
                                    <a href="{{ route('sell')}}" class="button-1 two"><i style="font-size: larger;" class="fas fa-hand-holding-usd m-1"></i>
                                        Sell Backlinks</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4" data-wow-duration="0.3s" data-wow-delay="0.3s" style="visibility: visible; animation-duration: 0.3s; animation-delay: 0.3s; animation-name: fadeInUp;">
                            <div class="m-portlet__head text-center ">
                                <h3>Sign Up Now</h3>
                            </div>
                            <div class="right-box">
                                <div class="tab-content">
                                    <div>
                                        <div class="exchange">
                                            <div class="exchange-box">

                                                <form method="POST" action="{{ route('register')}}" name="register">
                                                    @if (isset($errors) && $errors->any())
                                                        <div class="alert alert-danger">
                                                            <ul>
                                                                @foreach ($errors->all() as $error)
                                                                    <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                    @csrf
                                                    <input type="hidden" name="action_register" id="action_register" value="on">
                                                    <div class="row">
                                                        <div class="col-md-12 gx-2">
                                                            <div class="form-group">
                                                                <div class="text-center">
                                                                    <label for="">Enter your email address:</label>
                                                                </div>
                                                                <input type="email" placeholder="" value="{{ old('email') }}" name="email" required autocomplete="off" class="form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12 gx-2">
                                                            <div class="form-group">
                                                                <div class="text-center mt-4">
                                                                    <label for="">Enter your password:</label>
                                                                </div>
                                                                <input type="password" placeholder="" name="password" minlength="8" autocomplete="off" required class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="button-box mt-4 text-center">
                                                        <button type="submit" class="nav-link button-1 text-white btn button button-1 sub">
                                                            Submit
                                                        </button>

                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @elseif($navType ==2)
        {{-- Buy Backlinks Header --}}
        <div class="banner" id="home">
            <div class="hero-area">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-xl-7 col-lg-7 text-center text-xl-start">
                            <div class="banner-content pt-5">
                                <h3 class="subtitle ">Buy Backlinks </h3>
                                <h1 class="head">High Quality Backlinks From Real Blogs</h1>
                                <p class="text">
                                    Register now and explore our large link inventory to <br/>
                                    find high authority genuine backlinks to skyrocket your website.
                                </p>
                                <div class="link-box">
                                    <a href="{{route('register')}}" class="button-1 one mb-3 rounded-1"><i class="fas fa-user-circle m-1"></i>
                                        Register</a>
                                </div>
                            </div>
                        </div>
                            <?php /*    <div class="col-xl-5 col-lg-5 wow fadeInUp pt-5">
                        <div class="boss">
                            <div class="video">
                                <div class="video-box">

                               <iframe width="560" height="315" src="https://www.youtube.com/embed/CBVJTplw4cE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

                                </div>
                            </div>
                        </div>
                    </div> */ ?>
                        <div class="col-xl-4 col-lg-4" data-wow-duration="0.3s" data-wow-delay="0.3s" style="visibility: visible; animation-duration: 0.3s; animation-delay: 0.3s; animation-name: fadeInUp;">
                            <div class="m-portlet__head text-center ">
                                <h3>Sign Up Now</h3>
                            </div>
                            <div class="right-box">
                                <div class="tab-content">
                                    <div>
                                        <div class="exchange">
                                            <div class="exchange-box">

                                                <form method="POST" action="{{ route('register')}}" name="register">
                                                    @if (isset($errors) && $errors->any())
                                                        <div class="alert alert-danger">
                                                            <ul>
                                                                @foreach ($errors->all() as $error)
                                                                    <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                    @csrf
                                                    <input type="hidden" name="action_register" id="action_register" value="on">
                                                    <div class="row">
                                                        <div class="col-md-12 gx-2">
                                                            <div class="form-group">
                                                                <div class="text-center">
                                                                    <label for="">Enter your email address:</label>
                                                                </div>
                                                                <input type="email" placeholder="" value="{{ old('email') }}" name="email" required autocomplete="off" class="form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12 gx-2">
                                                            <div class="form-group">
                                                                <div class="text-center mt-4">
                                                                    <label for="">Enter your password:</label>
                                                                </div>
                                                                <input type="password" placeholder="" name="password" minlength="8" autocomplete="off" required class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="button-box mt-4 text-center">
                                                        <button type="submit" class="nav-link button-1 text-white btn button button-1 sub">
                                                            Submit
                                                        </button>

                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif($navType ==3)
        {{-- No Header --}}

    @elseif($navType ==4)
        {{-- Contact --}}
        <div class="banner" id="home">
            <div class="hero-area">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-xl-12 col-lg-12 text-center text-xl-start">
                            <div class="banner-content pt-5">

                                <h1 class="head text-center">Contact us</h1>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @elseif($navType ==5)
        {{-- FAQs --}}
        <div class="banner" id="home">
            <div class="hero-area">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-xl-12 col-lg-12 text-center text-xl-start">
                            <div class="banner-content pt-5">

                                <h1 class="head text-center">Frequently Asked Questions</h1>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @elseif($navType ==6)
        {{-- Reviews --}}
        <div class="banner" id="home">
            <div class="hero-area">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-xl-12 col-lg-12 text-center text-xl-start">
                            <div class="banner-content pt-5">

                                <h1 class="head text-center">Reviews</h1>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @elseif($navType ==7)
        {{-- Sell Backlinks Header --}}
        <div class="banner" id="home">
            <div class="hero-area">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-xl-7 col-lg-7 text-center text-xl-start">
                            <div class="banner-content pt-5">
                                <h3 class="subtitle ">Sell Backlinks </h3>
                                <h1 class="head"> Monetize Your Website<br> Start Earning Real Money.</h1>
                                <p class="text">
                                    Just register an account, install our Plugin to your blog <br/>
                                    and collect your monthly earnings on autopilot.
                                </p>
                                <div class="link-box">
                                    <a href="{{route('register')}}" class="button-1 one mb-3 rounded-1"><i class="fas fa-user-circle m-1"></i>
                                        Register</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4" data-wow-duration="0.3s" data-wow-delay="0.3s" style="visibility: visible; animation-duration: 0.3s; animation-delay: 0.3s; animation-name: fadeInUp;">
                            <div class="m-portlet__head text-center ">
                                <h3>Sign Up Now</h3>
                            </div>
                            <div class="right-box">
                                <div class="tab-content">
                                    <div>
                                        <div class="exchange">
                                            <div class="exchange-box">

                                                <form method="POST" action="{{ route('register')}}" name="register">
                                                    @if (isset($errors) && $errors->any())
                                                        <div class="alert alert-danger">
                                                            <ul>
                                                                @foreach ($errors->all() as $error)
                                                                    <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                    @csrf
                                                    <input type="hidden" name="action_register" id="action_register" value="on">
                                                    <div class="row">
                                                        <div class="col-md-12 gx-2">
                                                            <div class="form-group">
                                                                <div class="text-center">
                                                                    <label for="">Enter your email address:</label>
                                                                </div>
                                                                <input type="email" placeholder="" value="{{ old('email') }}" name="email" required autocomplete="off" class="form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12 gx-2">
                                                            <div class="form-group">
                                                                <div class="text-center mt-4">
                                                                    <label for="">Enter your password:</label>
                                                                </div>
                                                                <input type="password" placeholder="" name="password" minlength="8" autocomplete="off" required class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="button-box mt-4 text-center">
                                                        <button type="submit" class="nav-link button-1 text-white btn button button-1 sub">
                                                            Submit
                                                        </button>

                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                            <?php /*<div class="col-xl-5 col-lg-5 wow fadeInUp pt-5">
                        <div class="boss">
                            <div class="video">
                                <div class="video-box">

                                <iframe width="560" height="315" src="https://www.youtube.com/embed/CBVJTplw4cE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

                                </div>
                            </div>
                        </div>
                    </div>*/ ?>
                    </div>
                </div>
            </div>
        </div>
    @elseif($navType ==8)
        {{-- Plugin Header --}}
        <div class="banner" id="home">
            <div class="hero-area">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-xl-7 col-lg-7 text-center text-xl-start">
                            <div class="banner-content pt-5">
                                <h3 class="subtitle ">Wordpress Plugin </h3>
                                <h1 class="head"> Making Money<br> has never been so easy.</h1>
                                <p class="text">
                                    Install the Plugin, verify the Auth Key <br/>
                                    and start making money with your Blog.
                                </p>
                                <div class="link-box">
                                    <a href="{{route('download-plugin')}}" class="button-1 one mb-3 rounded-1"><i class="fab fa-wordpress-simple m-1"></i>
                                        Download Plugin</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-5 col-lg-5 wow fadeInUp pt-5">
                        <div class="boss">
                            <div class="video">
                                <div class="video-box">

                                <iframe width="560" height="315" src="https://www.youtube.com/embed/7ZrXXG52Y30" title="How to add Backlink-Market.com WP Plugin to my Blow" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

                                </div>
                            </div>
                        </div>
                    </div>

                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
