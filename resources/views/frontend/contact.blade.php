@extends('frontend.include.template')
@section('title','Contact - Backlink Market')


@section('meta_description','Contact us. Any questions? We love to answer all your questions!')
@section('meta_keywords','backlink market, contact')


@push("navtype")
<x-frontend.layout.navbar :navType="4" /> {{-- Component //--}}
@endpush

@section("pagecontent")
<section class="contact-section">
    <div class="container">
        <div class="row justify-content-center justify-content-lg-between">
            <div class="col-lg-7">
                <div class="contact-wrapper">
                    <div>
                        <h3 class="subtitle get touch">Get in Touch</h3>
                    </div>
                    <!-- Success message -->
                    @if(Session::has('success'))
                        <div class="alert alert-success">
                            {{Session::get('success')}}
                        </div>
                    @endif
                    <form class="contact-form" method="post" id="contact_form_submit" action="{{ route('ContactUsForm') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-3 d-flex align-items-center">
                                <div class="form-group mb-0">
                                    <label for="name">Your Name</label>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <input type="text" class="form-control {{ $errors->has('name') ? 'error' : '' }}" name="name" id="name">
                                    <!-- Error -->
                                    @if ($errors->has('name'))
                                    <div class="error">
                                        {{ $errors->first('name') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 d-flex align-items-center">
                                <div class="form-group mb-0">
                                    <label for="name">Your Email</label>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                   <input type="email" class="form-control {{ $errors->has('email') ? 'error' : '' }}" name="email" id="email">
                                    @if ($errors->has('email'))
                                    <div class="error">
                                        {{ $errors->first('email') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 d-flex align-items-center">
                                <div class="form-group mb-0">
                                    <label for="name">Your Message</label>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <textarea class="form-control {{ $errors->has('message') ? 'error' : '' }}" name="message" id="message"
            rows="4"></textarea>
        @if ($errors->has('message'))
        <div class="error">
            {{ $errors->first('message') }}
        </div>
        @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mt-5 submit">
                                    <button type="submit" class="nav-link button-1 text-white btn button button-1 sub" href="#">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="contact-content">
                    <div class="section-header">
                        <h3 class="subtitle get">Any questions?</h3>
                        <p class="text">
                            We love to answer all your questions! <br>
Before you contact us, we recommend checking our FAQs page seeing if your question is already answered there.
                        </p>
                        <a href="{{route('faq')}}" class="faqtxt">Read F.A.Q <i class="fas fa-angle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
