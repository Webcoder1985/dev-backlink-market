@extends('frontend.include.template')
@section('title','Reviews - Backlink Market')


@section('meta_description','Take a look at out stunning reviews and see what other customers says about our service. Convince yourself.')
@section('meta_keywords','reviews, feedback, backlink market')
@section('custom_css')
<link href="{{ asset('css/plugin/flag-icons.min.css')}}" rel="preload stylesheet" as="style">
@endsection
@push("navtype")
<x-frontend.layout.navbar :navType="6" /> {{-- Component //--}}
@endpush


@section("pagecontent")
<div class="effortless pt-3">
    <div class="container">
    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif
        <div class="row align-items-center justify-content-between">

            <div class="row">
                <div class="offset-md-3 col-md-6">
                    <div>

                        <span class="heading"><?php echo number_format($option->rating,1);?></span>
                        <div class="rating">
                        <?php $i=1;
                        $firsttime = true;
                        while($i <=5)
                        {
                            if($option->rating  >= $i)
                            {

                                echo '<i class="fa fa-star checked"></i>';
                            }
                            else
                            {
                                if($firsttime)
                                {
                                    $custom = $option->rating - intval($option->rating);
                                    $firsttime=false;
                                    echo '<i class="fa fa-star custom"></i>';
                                }
                                else{
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
                        </style>
                        </div>
                        <p><?php echo number_format($option->rating,1);?> out of 5 stars (based in <?php echo count($reviews);?> reviews)</p>
                        <div class="row">
                            <div class="side">
                                <div>Excellent</div>
                            </div>
                            <div class="middle">
                                <div class="bar-container">
                                    <div class="bar-5" style="width:<?php echo $option->filteredPercentage(5);?>%"></div>
                                </div>
                            </div>
                            <div class="side right">
                                <div><?php echo $option->filteredPercentage(5);?>%</div>
                            </div>
                            <div class="side">
                                <div>Very good</div>
                            </div>
                            <div class="middle">
                                <div class="bar-container">
                                    <div class="bar-4" style="width:<?php echo $option->filteredPercentage(4);?>%"></div>
                                </div>
                            </div>
                            <div class="side right">
                                <div><?php echo $option->filteredPercentage(4);?>%</div>
                            </div>
                            <div class="side">
                                <div>Average</div>
                            </div>
                            <div class="middle">
                                <div class="bar-container">
                                    <div class="bar-3" style="width:<?php echo $option->filteredPercentage(3);?>%"></div>
                                </div>
                            </div>
                            <div class="side right">
                                <div><?php echo $option->filteredPercentage(3);?>%</div>
                            </div>
                            <div class="side">
                                <div>Poor</div>
                            </div>
                            <div class="middle">
                                <div class="bar-container">
                                    <div class="bar-2" style="width:<?php echo $option->filteredPercentage(2);?>%"></div>
                                </div>
                            </div>
                            <div class="side right">
                                <div><?php echo $option->filteredPercentage(2);?>%</div>
                            </div>
                            <div class="side">
                                <div>Terrible</div>
                            </div>
                            <div class="middle">
                                <div class="bar-container">
                                    <div class="bar-1" style="width:<?php echo $option->filteredPercentage(1);?>%"></div>
                                </div>
                            </div>
                            <div class="side right">
                                <div><?php echo $option->filteredPercentage(1);?>%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="steps mb-3">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-xl-8 col-lg-10 text-center">
                <div class="section-head">
                    <h2 class="title">Recent Reviews</h2>
                    <p class="text">
                        <strong class="cusrv"> Some of our recent reviews what client has to say about us ! </strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Review list starts here --}}
<div class="container">
    {{-- review list--}}

    <?php foreach($reviews as $row){ ?>
    <div class="row" style="margin-bottom:30px">
        <div class="offset-md-5 col-md-6">
        <div class="rating">
        <?php $i=1;
        while($i <=5)
        {
            if($row->rating  >= $i)
            {
               echo '<i class="fa fa-star checked"></i>';
            }
            else
            {
                echo '<i class="fa fa-star"></i>';
            }

        $i++;
        }
        ?>
        </div> <?php echo date('F d,Y',strtotime($row->created_at));?>
            <br>
            <p><?php echo $row->review;?></p>
            <p><span class="flag-icon flag-icon-<?php echo strtolower($row->user->country);?>"></span><strong> -<?php echo $row->user->firstname." ".$row->user->lastname;?></strong></p>
        </div>
        <div class="col-2"> </div>
    </div>
    {{-- review list--}}
    <?php }?>
    {!! $reviews->render() !!}
    {{--<nav aria-label="Page navigation example">
        <ul class="pagination">
            <li class="page-item"><a class="page-link" href="#">Previous</a></li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#">Next</a></li>
        </ul>
    </nav>--}}
</div>
{{-- Review list end here--}}
<div class="pricing  mt-5 mb-5">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-xl-12 text-center">
                <?php if (Auth::guest()){ ?>
                <div class="section-head">
                    <p class="text loggedtxt">
                        <span><img src="{{asset('images/logged.png')}}" alt=""></span> &nbsp;
                        You must be
                        <strong>
                            <a href="{{ route('login') }}"><router-link to="login" style="font-size:29px;color:#6F767D ;font-weight:bold"> logged in
                            </router-link></a>
                        </strong>
                        to submit a review.
                    </p>
                </div>
                <?php } else {
                        if($user_review == 0){
                    ?>
                    <div class="contact-section">
                        <div class="contact-wrapper">
                            <form class="contact-form" id="contact_form_submit" action="{{ route('review.save.post') }}" method="post">
                                @csrf
                                <div class="row">
                                <div class="col-md-3 d-flex align-items-center">
                                        <div class="form-group mb-0">
                                            <label for="name">Your Rating</label>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                        <div class="pStar" data-pid="1"><?php
                                                $rate =  0 ;
                                                for ($i=1; $i<=5; $i++) {
                                                $css = $i<=$rate ? "star" : "star blank" ;
                                                echo "<div class='$css' data-i='$i'></div>";
                                                }
                                            ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 d-flex align-items-center">
                                        <div class="form-group mb-0">
                                            <label for="name">Your Message</label>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <textarea id="review" placeholder="" required="" name="review"></textarea>
                                            <input id="ninStar" type="hidden" name="rating"/>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mt-5 submit">
                                            <button  type="submit" class="nav-link button-1 text-white btn button button-1 sub" href="#">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php }
                else{
                    ?>
                    <div class="section-head">
                    <p class="text loggedtxt">
                        <span><img src="{{asset('images/logged.png')}}" alt=""></span> &nbsp;
                        You have already submitted a review.
                    </p>
                </div>
                    <?php
                }

                }?>
            </div>
        </div>
    </div>
</div>

<script>
    var stars = {
  // (A) INIT - ATTACH HOVER & CLICK EVENTS
  init : () => {
    for (let d of document.getElementsByClassName("pStar")) {
      let all = d.getElementsByClassName("star");
      for (let s of all) {
        s.onmouseover = () => { stars.hover(all, s.dataset.i); };
        s.onclick = () => { stars.click(s.dataset.i); };
      }
    }
  },

  // (B) HOVER - UPDATE NUMBER OF YELLOW STARS
  hover : (all, rating) => {
    let now = 1;
    for (let s of all) {
      if (now<=rating) { s.classList.remove("blank"); }
      else { s.classList.add("blank"); }
      now++;
    }
    document.getElementById("ninStar").value = rating;
  },

  // (C) CLICK - SUBMIT FORM
  click : (rating) => {

    document.getElementById("ninStar").value = rating;
    //document.getElementById("ninForm").submit();
  }
};
window.addEventListener("DOMContentLoaded", stars.init);
</script>
@endsection
