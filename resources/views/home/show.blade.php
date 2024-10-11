

@extends('layouts.app')
{{-- @push('head')
<link rel="preload" href="{{ mix('css/splide.css') }}" as="style" />
<link rel="stylesheet" href="{{ mix('css/splide.css') }}">
<script src="{{ mix('js/splide.js') }}"></script>
@endpush --}}
@push('head')
    <link rel="preload" href="{{ mix('css/home.css') }}" as="style" />
    <link rel="stylesheet" href="{{ mix('css/home.css') }}">
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <style>
        .about-us-img{
               width: 50%;
            }
            .footer-container{
                width: 50%;
            }
        @media (max-width : 600px){
            .about-us-img{
               width: 85%;
            }
            .footer-container{
                width: 85%;
            }
        }

    </style>
    {{--
    <script src="https://unpkg.com/html5-qrcode"></script> --}}
@endpush

@section('content')

@if (!$parentCategories->isEmpty())
    <section id="home__category" class="m-0 mt-5 w-100 row flex-row-reverse overflow-hidden justify-content-between py-2">
        @foreach ($parentCategories as $parentCategory)
            <div class="home__category-box col-6 col-sm-6 col-lg-2 d-flex flex-column align-items-center">
                <img class="w-100" src="{{ $parentCategory->image_url }}" />
                <a href="{{ $parentCategory->url }}" class="text-decoration-none text-body">
                    <h3 class="home__category-title text-nowrap">{{ $parentCategory->name }}</h3>
                </a>
            </div>
        @endforeach
    </section>
@endif



<section class="m-0 mt-5 overflow-hidden w-100 d-flex align-items-center gap-5 ff-montserrat flex-column flex-md-row"
    style="color:#547286; position:relative;">
    <img class="about-us-img" src="/upload-images/about-us.png" />
    <div style="position:relative; width:100%;">
        <h4 class="d-flex justify-content-left;">About
            <hr style="
    width: 50px;
    height: 5px;
    background: #5989ae;
    opacity: 1;
    margin-left: 10px;
">
        </h4>
        <h1 class="fw-bold">Products and Labels to help you get Organised</h1>
        <p class="fw-semibold">
            The idea of MeemHome might include offering a range of products and services such as furniture, home décor,
            and organizational solutions that emphasize modern aesthetics, sustainability, and personalization.
        </p>
        <img src="/home-page_info"
            style="position:absolute; right:-40%;top:50%; transform:translateY(-50%) scale(0.75); z-index:-1;" />
    </div>
</section>




<div class="row d-flex align-items-center justify-content-center
        @if ($banners->isEmpty()) h-100 @else mt-5 @endif">
    {{-- <div class="col-lg-6 col-md-8 col-sm-12 my-5">
        <div id="qr-reader" style="width: 600px"></div>
        <warranty-check-form-component></warranty-check-form-component>
    </div> --}}
    <div class="col-md-12">
        @if (!$latestProducts->isEmpty())
                    <section class="mb-5" style="color:#547286;">
                        <header class="h1 text-center mb-4 fw-bold" style="
                text-decoration: underline;
            ">Latest Products</header>
                        <div class="row">
                            @foreach ($latestProducts as $latestProduct)
                                <div class="col-lg-3 col-md-6 col-6 mb-4 d-flex align-items-stretch">
                                    <x-product :product="$latestProduct"></x-product>
                                </div>
                            @endforeach
                        </div>
                    </section>
        @endif
    </div>
</div>


{{-- @if (!$categories->isEmpty())
<section class="mb-5">
    <header class="h3 text-center mb-0 text-uppercase">
        <span class="fw-bold">Shop</span> by Category
    </header>
    <div class="c-underline">&nbsp;</div>
    <div class="row">
        @foreach ($categories as $category)
        <div class="col-md-2 col-6 d-flex align-items-stretch mb-3">
            <a href="{{ $category->url }}"
                class=" text-decoration-none text-body border w-100 category-navigation-classifier rounded-4">
                <picture>
                    <source type="image/jpg" data-srcset="{{ $category->splide_slide_image }}" />
                    <img alt="{{ $category->name }}" data-src="{{ $category->splide_slide_image }}" aria-hidden="true"
                        class="lazy w-100  loading mb-2 rounded-top-4" />
                </picture>
                <div class="text-center text-dark fw-bold px-3 pt-2 pb-1">
                    {{ $category->name }}
                </div>
            </a>
        </div>
        @endforeach
    </div>
</section>
@endif --}}
{{-- <section class="mb-5">
    <header class="h3 text-center mb-0 text-uppercase">
        Our Advantages
    </header>
    <div class="c-underline">&nbsp;</div>
    @include('home.advantages')
</section> --}}

{{-- @if (!$categories->isEmpty())
<section id="card-carousel" class="splide mb-3">
    <div class="splide__track">
        <ul class="splide__list border">
            @foreach ($categories as $category)
            <x-category :category="$category"></x-category>
            @endforeach
        </ul>
    </div>
</section>
@endif --}}
{{-- <section class="mb-5">
    <video class="Intro-module--video--miFVw rounded-4" autoplay="" loop="" muted="">
        <source src="{{ asset('videos/fb-video-1.webm') }}" type="video/webm">
        <source src="{{ asset('videos/fb-video-1.mp4') }}" type="video/mp4">
    </video>
</section>

@if (!$discountProducts->isEmpty())
<section class="mb-5">
    <header class="h3 text-center mb-0 text-uppercase">Discounts %</header>
    <div class="c-underline">&nbsp;</div>
    <div class="row">
        @foreach ($discountProducts as $discountProduct)
        <div class="col-lg-3 col-md-6 col-6 mb-4 d-flex align-items-stretch">
            <x-product :product="$discountProduct"></x-product>
        </div>
        @endforeach
    </div>
</section>
@endif
@if (!$latestProducts->isEmpty())
<section class="mb-5">
    <header class="h3 text-center mb-0 text-uppercase">New</header>
    <div class="c-underline">&nbsp;</div>
    <div class="row">
        @foreach ($latestProducts as $latestProduct)
        <div class="col-lg-3 col-md-6 col-6 mb-4 d-flex align-items-stretch">
            <x-product :product="$latestProduct"></x-product>
        </div>
        @endforeach
    </div>
</section>
@endif
<section class="mb-5">
    <video class="Intro-module--video--miFVw rounded-4" autoplay="" loop="" muted="">
        <source src="{{ asset('videos/fb-video-2.webm') }}" type="video/webm">
        <source src="{{ asset('videos/fb-video-2.mp4') }}" type="video/mp4">
    </video>
</section>
@if (!$randomProducts->isEmpty())
<section class=mb-5">
    <header class="h3 text-center mb-0 text-uppercase">Products You Might Like</header>
    <div class="c-underline">&nbsp;</div>
    <div class="row">
        @foreach ($randomProducts as $randomProduct)
        <div class="col-lg-3 col-md-6 col-6 mb-4 d-flex align-items-stretch">
            <x-product :product="$randomProduct"></x-product>
        </div>
        @endforeach
    </div>
</section>
@endif
<section class="mb-5">
    <video class="Intro-module--video--miFVw rounded-4" autoplay="" loop="" muted="">
        <source src="{{ asset('videos/fb-video-3.webm') }}" type="video/webm">
        <source src="{{ asset('videos/fb-video-3.mp4') }}" type="video/mp4">
    </video>
</section> --}}
@endsection
{{-- @push('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Splide('#card-carousel', {
            perPage: 8,
            breakpoints: {
                640: {
                    perPage: 1,
                },
            },
        }).mount();
    });
</script>
@endpush --}}
@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelector('#tech-wrapper').classList.add("body-home");

            document.body.classList.remove("tech-body");

            // var navbarHeight = document.querySelector("#mainNavbar").clientHeight;
            // var infoNavbarHeight = document.querySelector("#infoNavbar").clientHeight;
            // var heightM = navbarHeight + infoNavbarHeight;

            // document.querySelector('#tech-wrapper').style.height = `calc(100vh - ${heightM}px)`;
            // document.querySelector('#app-container').style.height = `calc(100vh - ${heightM}px)`;


            //document.querySelector(".navbar").classList.add("fixed-top");
        });

        // function onScanSuccess(decodedText, decodedResult) {
        //     console.log(`Code scanned = ${decodedText}`, decodedResult);
        // }
        // var html5QrcodeScanner = new Html5QrcodeScanner(
        //     "qr-reader", {
        //         fps: 10,
        //         qrbox: 250
        //     });
        // html5QrcodeScanner.render(onScanSuccess);
    </script>
@endpush
