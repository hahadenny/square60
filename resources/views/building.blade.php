@extends('layouts.app')
@section('header')
    <header>
        @include('layouts.header')
    </header>
@endsection
@section('content')
    <div id="app">
        <div>
            <section id="filters" class=>
                <div class="container">
                    <div class="columns">
                        <div class="column is-2">
                            <ul class="level is-mobile" id="topline">
                                <li><a class="" id="email-topline" ><span>Email</span></a>
                                    <span class="icon"><i class="fa fa-envelope-o"></i></span>
                                </li>

                                <li>

                                        <a class="" id="search-toline" >
                                            <span>Save</span></a><span class="icon">
                                                            <i class="fa fa-folder-open-o"></i></span>


                                </li>
                            </ul>
                        </div>

                        <div class="column is-4 is-offset-6">
                            @include('partial.search')
                        </div>
                    </div>
                </div>
            </section>
            <section class="hero">
                <div class="hero-body">
                    <div class="container">
                        <div class="columns">
                            <div class="column is-5 is-offset-1">

                                {{--@if (count($images))
                                    <div class='carousel'>
                                        <div class='carousel-container'>
                                            <div class='carousel-content carousel-animate carousel-animate-slide'>
                                                @foreach ( $images as $image)
                                                    <div class='carousel-item'>
                                                        <figure class="image is-16by9">
                                                            <img class="is-background" src="{{$image}}" alt=""  />
                                                        </figure>
                                                    </div>
                                                @endforeach

                                            </div>
                                            <div class="carousel-nav-left">
                                                <i class="fa fa-chevron-left" aria-hidden="true"></i>
                                            </div>
                                            <div class="carousel-nav-right">
                                                <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                @else--}}
                                    @if (count($images) && is_array($images))
                                        <div class="image-gallery">
                                            <a class="prev" onclick="plusSlides(-1)">❮</a>
                                            <a class="next" onclick="plusSlides(1)">❯</a>
                                            <div id="buildingImages">
                                                @foreach ( $images as $image)
                                                    <div class="mainSlides">
                                                        <div class="item" data-src="{{$image}}">
                                                            <img src="{{$image}}" />
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="row">
                                                @if (count($images) > 1)
                                                    @foreach($images as $k=>$image)

                                                        <div class="thumb-image">
                                                            <img class="thumb-img cursor" src="{{$image}}" style="width:100%" onclick="currentSlide({{$k+1}})" alt="">
                                                        </div>

                                                    @endforeach
                                                @endif
                                            </div>

                                        </div>
                                    @else
                                    <img class="is-background" src="{{$images}}" alt="" width="640" height="310" />
                                @endif
                            </div>
                            <div class="column is-5">

                            <span class="tags has-addons">

                            </span>
                                <br>
                                <p>
                                    <span class="is-size-5">Building name: {{$result->building_name}}</span><br>
                                    <span class="is-size-5">{{$result->building_address}}, {{$result->building_city}} {{$result->building_state}} {{$result->building_zip}}</span>

                                </p>
                                    <br>
                                <div class="columns">
                                    <div class="column">
                                        <ul>
                                            <li>Condo</li>
                                            <li>Units: {{$result->building_units}}</li>
                                            @if ($result->building_build_year < 1945)
                                            <li>Prewar</li>
                                            @endif
                                        </ul>
                                    </div>
                                    <div class="column">
                                        <ul>
                                            <li>{{$result->neighborhood}}</li>
                                            <li>Stories: {{$result->building_stories}}</li>
                                            <li>{{$result->building_build_year}}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section">

                <div class="container">
                    <div class="columns">

                        <div class="column is-5 is-offset-1">
                            <h4 class="is-size-4">Building information</h4><br />

                            <p>{{$result->building_description}}</p>
                            <br>
                            <h3 class="is-size-5">Features</h3>
                            <ul class="filters_list">
                                @foreach ( $result->building_amenities  as $k=>$item)
                                    <li class=" filters ">{{$item}}</li>
                                @endforeach
                            </ul>
                            <br>
                            <div id="div_map"><div id="map_canvas"></div></div>
                        </div>
                    </div>
                    <br><br>
                    <div class="columns">
                        <div class="column is-12-tablet is-12-desktop is-12-widescreen is-12-fullhd has-text-centered">
                            <h2 class="is-size-3">Currently available: </h2>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column is-6-tablet is-4-desktop is-4-widescreen is-3-fullhd is-offset-2-desktop is-offset-2-widescreen is-offset-3-fullhd">

                            <h3 class="is-size-5">Sales</h3>
                            <hr />

                            @if(!$currentAvailableSales->isEmpty())

                                @foreach($currentAvailableSales as $item)
                                    <div class="card">
                                        <div class="card-image">
                                            <figure class="image is-4by3">

                                                @if ($item->img)
                                                <img src="{{$item->img}}" alt="Placeholder image">
                                                @else

                                                @endif
                                            </figure>
                                        </div>
                                        <div class="card-content">
                                            <div class="content">
                                                <p>
                                                    <span class="is-size-3">{{$item->full_address}}</span>

                                                </p>

                                                <p class="is-size-5">Information</p>
                                                <ul>
                                                    <li><span>{{$item->full_address}}</span></li>
                                                    <li>Size: {{$item->sq_feet}}ft^<sup>2</sup></li>
                                                </ul>
                                                <a href="{{$item->name_link}}" class="button is-primary">Learn more</a>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                @endforeach

                            @endif

                        </div>

                        <div class="column is-6-tablet is-4-desktop is-4-widescreen is-3-fullhd">

                            <h3 class="is-size-5">Rental</h3>
                            <hr />

                            @if(!$currentAvailableRentals->isEmpty())

                                @foreach($currentAvailableRentals as $item)
                                    <div class="card">
                                        <div class="card-image">
                                            <figure class="image is-4by3">
                                                @if ($item->img)
                                                    <img src="{{$item->img}}" alt="Placeholder image">
                                                @else

                                                @endif
                                            </figure>
                                        </div>
                                        <div class="card-content">
                                            <div class="content">
                                                <p>
                                                    <span class="is-size-3">{{$item->full_address}}</span>

                                                </p>

                                                <p class="is-size-5">Information</p>
                                                <ul>
                                                    <li><span>{{$item->full_address}}</span></li>
                                                    <li>Size: {{$item->sq_feet}}ft^<sup>2</sup></li>
                                                </ul>
                                                <a href="{{$item->name_link}}" class="button is-primary">Learn more</a>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                @endforeach

                            @endif

                        </div>
                    </div>

                </div>
            </section>
        </div>
    </div>

@endsection

@section('footer')
    @include('layouts.footer')
@endsection

@section('additional_scripts')
    <script>

        /*==for map==*/
        var map = new google.maps.Map(document.getElementById('map_canvas'), {
            zoom: 16,
            center: {lat: 40.692464, lng: -74.2008182},
        });
        var geocoder = new google.maps.Geocoder();

        geocodeAddress(geocoder, map);

        function geocodeAddress(geocoder, resultsMap) {
            var address = '{{$result->building_address}} {{$result->building_city}} {{$result->building_zip}}';
            geocoder.geocode({'address': address}, function(results, status) {
                if (status === 'OK') {
                    resultsMap.setCenter(results[0].geometry.location);
                    var marker = new google.maps.Marker({
                        map: resultsMap,
                        position: results[0].geometry.location
                    });
                } else {
                    alert('Geocode was not successful for the following reason: ' + status);
                }
            });
        }

        /*var georssLayer = new google.maps.KmlLayer({
            url: ' https://s3.us-east-2.amazonaws.com/new-developer/maps-layout/BRONX.kml',
            map: map
        });*/

        /*======for slider=======*/
        var slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        function showSlides(n) {
            var i;
            var slides = document.getElementsByClassName("mainSlides");
            var dots = document.getElementsByClassName("thumb-img");
//            var captionText = document.getElementById("caption");
            if (n > slides.length) {slideIndex = 1}
            if (n < 1) {slideIndex = slides.length}
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex-1].style.display = "block";
            dots[slideIndex-1].className += " active";
//            captionText.innerHTML = dots[slideIndex-1].alt;
        }

        /*for lightBox*/
        $('#buildingImages').lightBox({
            selector: '.item',
            thumbnail:true
        });

    </script>
@endsection