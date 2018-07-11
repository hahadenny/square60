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
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/" onclick="window.history.go(-1); return false;" />
Previous page</a></li>
                                <li class="breadcrumb-item active"><span>Listing</span></li>
                            </ol>
                            <div class="columns">
                                <div class="column is-5 is-offset-1">
                                    <p>
                                        <span class="is-size-4">${{number_format($result->price,0,'.',',')}}  @if ($result->estate_type==2)@endif</span>
                                        <br>
                                        <span>{{$result->full_address}} {{$result->unit}} {{$result->city}} {{$result->state}}, {{$result->zip}} </span>
                                    </p>

                                    {{--@if (count($images))
                                        <ul id="image-gallery" class="gallery list-unstyled">
                                            @foreach ( $images as $image)

                                                <li data-thumb="{{$image}}" data-src="{{$image}}">
                                                    <img src="{{$image}}" />
                                                </li>
                                            @endforeach

                                            @foreach ( $result->path_for_floorplans as $fimage)
                                                <li id='test' class="is-pulled-right" data-thumb="{{$fimage}}" data-src="{{$fimage}}">
                                                    <img src="{{$fimage}}" />
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <img class="is-background" src="https://s3.us-east-2.amazonaws.com/new-developer/unit_images/137823/10236523/299542512.jpg" alt="" width="640" height="310" />
                                    @endif--}}

                                    @if (count($images))
                                        <div class="image-gallery">
                                            <a class="prev" onclick="plusSlides(-1)">❮</a>
                                            <a class="next" onclick="plusSlides(1)">❯</a>

                                            <div id="selector1">
                                                @foreach ( $images as $image)
                                                    <div class="mainSlides">
                                                        <div class="item" data-src="{{$image}}">
                                                            <img src="{{$image}}" alt="" />
                                                        </div>
                                                       {{--<img src="{{$image}}" />--}}
                                                    </div>
                                                @endforeach

                                                @foreach ( $result->path_for_floorplans as $fimage)
                                                  <div class="mainSlides">
                                                      <div class="item" data-src="{{$fimage}}">
                                                          <img src="{{$fimage}}" alt="" />
                                                      </div>
                                                  </div>
                                                @endforeach
                                            </div>
                                                    <div class="row">
                                                        @foreach($images as $k=>$image)

                                                                <div class="thumb-image">
                                                                    <img class="thumb-img cursor" src="{{$image}}" style="width:100%" onclick="currentSlide({{$k+1}})" alt="">
                                                                </div>

                                                        @endforeach

                                                            @foreach($result->path_for_floorplans as $k=>$fimage)
                                                                @if($k==0)
                                                                <div class=" floor">
                                                                    <div class="thumb-img cursor floor-button" style="width:100%;" onclick="currentSlide({{$k}})" >floor plan</div>
                                                                </div>
                                                                @endif
                                                            @endforeach

                                                    </div>

                                        </div>
                                    @else
                                        <img class="is-background" src="https://s3.us-east-2.amazonaws.com/new-developer/unit_images/137823/10236523/299542512.jpg" alt="" width="640" height="310" />
                                    @endif



                                </div>

                                <div class="column is-5">
                                     <!--<div class="column is-8 is-offset-3">
                                        <span>Open House</span>
                                        <ul>
                                            <li>Monday: 2-5 pm</li>
                                            <li>Thursday: 1-3 pm</li>
                                        </ul> 
                                    </div>-->
                                    <div class="column is-12">
                                        @if ($result->building && $result->building->id)
                                        <a href="/building/show/{{$result->building->id}}">Current Available in building</a>
                                        @else
                                            Current Available in building
                                        @endif
                                    </div>
                                    <span class="tags has-addons">
                                        <span class="tag is-danger">[{{$result->unit_type}}] {{$result->neighborhood}}</span>
                                    </span>
                                    @if($result->estate_type == 2 && $result->fees == 0)
                                        <span class="tag is-danger">NoFee</span>
                                    @endif

                                    <ul>
                                        <li>Bed: {{number_format($result->beds)}}</li>
                                        <li>Bath: {{number_format($result->baths)}}</li>
                                     <!--   <li>Half Bath: 0</li> -->
                                        <li>Size: {{floatval($result->sq_feet)}} ft<sup>2</sup></li>
                                     @if($result->tax!=0) <li>Tax:  ${{$result->tax}} </li>@endif
                                     @if($result->maint!=0)<li>Maint: ${{$result->maint}}</li>@endif
                                        <li>Condition: Good</li>
                                        <li>Listed: {{$result->listed}}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="section">
                    <div class="container">
                        <div class="columns">
                            <div class="column is-12-mobile is-7-tablet is-6-desktop is-6-widescreen is-5-fullhd text-building">
                                <h4 class="is-size-4">Description: </h4><br />
                                @if ($result->unit_description)
                                    <p class="comment more">{!! $result->unit_description !!}</p><br>
                                @endif<p>Building Information</p><br>
                                <p>Amenities:  {{$result->b_amenities}}
                                </p>
                                <p>Apartment features:  {{ implode(', ',$result->amenities)}} </p>
                                <br>

                                <div class="column is-12-mobile is-6-tablet is-6-desktop is-6-widescreen is-6-fullhd is-offset-1-desktop is-offset-1-widescreen is-offset-2-fullhd">
                                    <h3 class=" has-text-centered label"  onclick="toggleCalc()" ><span class="button"><b>Mortgage Calculator</b></span></h3>
                                    <div id ="calcContainer" class="calcContainer" style="display: none;">
                                    <form id="calcForm" onreset="load(), resetTotal()">

                                        <div class="field">
                                            <label class="label">Enter the Loan Amount:</label>
                                            <div class="control has-icons-left ">
                                                <input type="text" class="input" id="inAmount"/>
                                            </div>
                                        </div>

                                        <div class="field">
                                            <label class="label">Enter the APR (Interest Rate):</label>
                                            <div class="control has-icons-left ">
                                                <input type="text" class="input" size="17.5" id="inAPR"/>
                                                <span class="icon is-small is-left">
                                            <i class="fa fa-percent"></i>
                                        </span>
                                            </div>
                                        </div>

                                        <div class="field">
                                            <label class="label">Enter the Period (in years):</label>
                                            <div class="control has-icons-left ">
                                                <input type="text" class="input" id="inPeriod"/>
                                            </div>
                                        </div>

                                        <div class="field">
                                            <label class="label">Enter the Down Payment (optional):</label>
                                            <div class="control has-icons-left ">
                                                <input type="text" class="input" onchange="doIT()" id="inDown"/>
                                            </div>
                                        </div>

                                    </form>

                                    <br/>

                                    <button class="button is-primary" id="btnCalculate" onclick="doIT()"><span>Calculate Payments</span></button>

                                    <button id="btnClear"  class="button" onclick="wipeIT(),play()">Reset</button>

                                    <hr>
                                    <p class="is-size-6"><b>Total Monthly Payment: <span id="outFinal"></span></b></p>
                                    </div>
                                </div>


                                <div id="div_map"><div id="map_canvas"></div></div>



                            </div>
                            <div class="column is-12-mobile is-5-tablet is-4-desktop is-4-widescreen is-3-fullhd is-offset-1-desktop is-offset-1-widescreen is-offset-2-fullhd">
                                <h4 class="is-size-4">Listed by</h4>
                                <br />
                                @foreach ($agents as $agent)
                                <article class="media">
                                    <figure class="media-left">
                                        <div style="margin: 0px 20px 10px 0px;">
                                            @if (isset($agent->img))
                                                @if ($agent->web_site)
                                            <p class="image is-128x128 agent-image" style="overflow: hidden;border-radius: 50%;">
                                                <a href="{{$agent->web_site}}"><img src="{{$agent->img}}"></a>
                                            </p>
                                                @else
                                                    <p class="image is-128x128 agent-image" style="overflow: hidden;border-radius: 50%;">
                                                        <img src="{{$agent->img}}">
                                                    </p>
                                                @endif
                                            @endif
                                        </div>
                                    </figure>
                                    <div class="media-content">
                                        <div class="content">
                                            <p>
                                                @if(isset($agent->id))
                                                    <a href="/agent/{{$agent->id}}"><strong>{{$agent->name}}</strong></a>
                                                @else
                                                    <strong>{{$agent->name}}</strong>
                                                @endif
                                                <br />

                                                @if(isset($agent->company))
                                                <small>
                                                    @if ($agent->web_site)
                                                        <a href="{{$agent->web_site}}">{{$agent->company}}</a>
                                                    @else
                                                        {{$agent->company}}
                                                    @endif

                                                </small>
                                                    <br />

                                                @endif

                                                @if($result->path_to_logo)
                                                    <img src="{{$result->path_to_logo}}">
                                                @endif()

                                                @if (isset($agent->phone))
                                                    <small>
                                                        <label></label> <span id="show_phone_span_{{$agent->id}}" onclick="showPhone({{$agent->id}})">Click for phone #</span><span style="display: none;" id="show_phone_{{$agent->id}}">{{$agent->phone}}</span>

                                                    </small>
                                                 <br />
                                            @endif

                                                <br />
                                            </p>
                                        </div>
                                    </div>
                                </article>

                                @endforeach

                                {{--@if (Auth::Guest())
                                    @include('layouts.modalSendGuest')
                                @else

                                    @include('layouts.modalSend')
                                @endif--}}
                                <div slot="header">
                                    <div>
                                        <a class="modal-close" type="button" @click="showModal = false"></a>
                                    </div>
                                    <h3 class="has-text-centered">For more info</h3>
                                </div>
                                <div slot="body">
                                    <send inline-template v-bind:agentemail="'@if(isset($agent)){{$agent->email}}@else{{''}}@endif'" v-bind:name="'@if(isset(Auth::user()->name)){{Auth::user()->name}}@else{{'Guest'}}@endif'" v-bind:listingid="{{$result->id}}">
                                        <div>
                                            <div class="field">
                                                <label class="label">Email</label>
                                                <div class="control has-icons-left ">
                                                    <input v-model="useremail" class="input" type="email" placeholder="Email input" name="email" value="" required>
                                                    <span class="icon is-small is-left">
                                            <i class="fa fa-envelope"></i>
                                        </span>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <label class="label">Phone</label>
                                                <div class="control has-icons-left ">
                                                    <input v-model="phone" class="input" type="text" placeholder="Phone input" name="phone" value="" required>
                                                    <span class="icon is-small is-left">
                                        <i class="fa fa-phone"></i>
                                    </span>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <label class="label">Message</label>
                                                <div class="control has-icons-left ">
                                                    <textarea v-model="message" class="textarea" placeholder="Message input" name="message" rows="3" required></textarea>
                                                </div>
                                            </div>
                                            <button id="sendButton" class="button is-primary" type="button" v-on:click="setPost">Send</button>&nbsp;&nbsp;<span id="messageResponse"></span>
                                        </div>
                                    </send>
                                </div>

                                <div slot="footer">

                                </div>
                                <modal v-if="showModal" @close="showModal = false">
                                </modal>

                            </div>
                        </div>
                    </div>
                    <br/><br/>
                    <div class="container">
                        <br/>
                        <div class="columns">
                            <div class="column is-12-tablet is-8-desktop is-8-widescreen is-8-fullhd is-offset-2-desktop is-offset-2-widescreen is-offset-3-fullhd">
                                <h2 class="is-size-3">Similar: </h2>
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

                                                    @if ($item->images)
                                                        <img src="{{$item->path_for_images}}" alt="Placeholder image">
                                                    @else
                                                        <img class="is-background" src="https://s3.us-east-2.amazonaws.com/new-developer/unit_images/137823/10236523/299542512.jpg" alt="" />
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
                                                    <a href="/show/{{$item->name}}/{{$item->id}}" class="button is-primary">Learn more</a>
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

                                                    @if ($item->images)
                                                        <img src="{{$item->path_for_images}}" alt="Placeholder image">
                                                    @else
                                                        <img class="is-background" src="https://s3.us-east-2.amazonaws.com/new-developer/unit_images/137823/10236523/299542512.jpg" alt="" />
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
                                                    <a href="/show/{{$item->name}}/{{$item->id}}" class="button is-primary">Learn more</a>
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
                var address = '{{$result->full_address}} {{$result->city}} {{$result->state}} {{$result->zip}}';
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
               // var captionText = document.getElementById("caption");
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
                //captionText.innerHTML = dots[slideIndex-1].alt;
            }

            function showPhone(id){
                $('#show_phone_span_'+id).toggle();
                $('#show_phone_'+id).toggle();
            }

            /*for lightBox*/
            $('#selector1').lightBox({
                selector: '.item',
                thumbnail:true
            });

        /*for calculator*/
        const invalidColor = "rgb(237, 190, 190)";

        //formula is c= ( (r*p) * Math.pow((1+r), n))  / (Math.pow((1+r),n) - 1)
        //@param p double is Amount
        //@param r the interest as percent.
        //@param n Term in years
        function calculateMortgage(p,r,n)
        {
            //convert to decimal
            r = convertToDecimal(r);
            //convert to months
            n = convertToMonth(n);

            var total = ( (r*p) * Math.pow((1+r), n))  / (Math.pow((1+r),n) - 1);

            return parseFloat(total.toFixed(2));

        }

        function convertToDecimal(percent)
        {
            return ((percent/12)/100);
        }

        function convertToMonth(year)
        {
            return (year*12);
        }

        function postPayment(payment)
        {
            var htmlEL = document.getElementById("outFinal");
            htmlEL.innerHTML = "$" + payment;
        }

        function resetTotal()
        {
            var total = document.getElementById("outFinal");
            total.innerHTML = "";
        }

        //calculate the data, JUST DO IT
        function doIT()
        {
            var cost = document.getElementById("inAmount").value;
            if(cost == "")
            {
                alert("Please Enter an amount")
                return false;
            }
            if(cost <= 0 )
            {
                alert("Invalid Cost")

                return false;
            }

            var downPayment = document.getElementById("inDown").value;

            if(downPayment < 0)
            {
                alert("Invalid Down Payment")
                return false;
            }

            var interest = document.getElementById("inAPR").value;

            if(interest == "")
            {
                alert("Please Enter an interest rate")
                return false;
            }
            if(interest <= 0 || !(interest >=0 && interest <=100))
            {
                alert("Invalid interest rate")
                return false;
            }

            var term = document.getElementById("inPeriod").value;

            if(term == "")
            {
                alert("Please Enter a term")
                return false;
            }
            if(term <= 0)
            {
                alert("Invalid Term")
                return false;
            }

            var loanAmount = cost - downPayment;

            var pmt = calculateMortgage(loanAmount,interest,term);

            postPayment(pmt);

        };

        function load()
        {
            document.getElementById("inAmount").focus();
        }

        function wipeIT()
        {
            document.getElementById("calcForm").reset();

        }

        function play() {
            document.getElementById("snd").currentTime = 0;
            snd.play();
        }


            function toggleCalc(){

                $('#calcContainer').toggle();
            }

            /*======for description========*/
            var showChar = 255;
            var ellipsestext = "...";
            var moretext = "read more";
            var lesstext = "less";
            $('.more').each(function() {
                var content = $(this).html();

                if(content.length > showChar) {

                    var c = content.substr(0, showChar);
                    var h = content.substr(showChar, content.length - showChar);

                    var html = c + '<span class="moreelipses">'+ellipsestext+'</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">'+moretext+'</a></span>';

                    $(this).html(html);
                }

            });

            $(".morelink").click(function(){
                if($(this).hasClass("less")) {
                    $(this).removeClass("less");
                    $(this).html(moretext);
                } else {
                    $(this).addClass("less");
                    $(this).html(lesstext);
                }
                $(this).parent().prev().toggle();
                $(this).prev().toggle();
                return false;
            });

        </script>
@endsection