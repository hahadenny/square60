
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Square60</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="pevYwEva9k7U192zFuiCM7v182MjqvKaCXeEKJua">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-form-styler@2.0.2/dist/jquery.formstyler.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.0/slick-theme.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/bulma2.css">
    <link rel="shortcut icon" href="/images/favicon.ico" />
</head>

<body class="">

<div class="mobile-menu" id="mobile-menu">
    <ul>
        <li>
        <a href="http://www.square81.com/login">Sign In</a>
    </li>
    <li>
        <a href="http://www.square81.com/register">Sign Up</a>
    </li>
    <li>
        <a href="#" class="showModal modal-button">Submit Listing</a>
    </li>
    </ul>        
</div>
<div class="wrapper">
    <div class="content">
        @include('partial.header')  
        <div class="action-menu">
            <div class="container">
                <ul class="menu-list">
                    <li>
                        <a href="javascript:void(0);" class="print" onclick="window.print()">Print</a>
                    </li>
                    <li id="app2">
                        <a href="javascript:void(0);" class="email" id="email-topline">E-mail</a>
                                                <div class="wrap-modal">
                            <modal v-if="showModal" @close="showModal = false">
                                <div slot="header">
                                    <label class="label">Enter email</label>
                                </div>
                                <div slot="body">
                                    <sendbuilding inline-template v-bind:building_name="'32_East_1st_Street'" v-bind:building_city="'Manhattan'">
                                        <div>
                                            <div class="field">
                                                <div class="control has-icons-left ">
                                                    <input v-model="guestEmail" class="input" id="email" type="email" name="email" value="" required autofocus>
                                                    <span class="icon is-small is-left"><i class="fa fa-envelope"></i></span>
                                                </div>
                                            </div>
                                            <button class="sendButton button is-primary" type="button" v-on:click="setPost">Send</button>
                                        </div>
                                    </sendbuilding>
                                </div>
                                <div slot="footer"></div>
                            </modal>
                        </div>
                                            </li>
                    <li>
                                            <a href="javascript:void(0);" class="save" id="search-toline">Save</a>
                                        </li>
                </ul>
            </div>
        </div>
        <div class="breadcrumbs">
            <div class="container">
                                <ul>
                    <li>
                        <a href="javascript:void(0);" onclick="window.history.go(-1); return false;">Previous Page</a>
                    </li>
                    <li>
                        <span>Building Page</span>
                    </li>
                </ul>
            </div>
        </div>
        <div style="width:100%;text-align:center;margin-top:10px; margin-bottom:40px;color:red;"><h1>This is just an example!</h1></div>
        <div class="listing">
            <div class="container">
                <div class="building-desc">
                    <div class="slider" style="max-height:380px;overflow:hidden;">
                        <div class="slider-for">
                                                                                <div style="overflow:hidden;background-color: rgba(0, 0, 0, 0.8);">
                                <img class="slider-img" src="http://s3.amazonaws.com/sales-listing-images/b_large/5273/1318952/286329989.jpg" alt="">
                            </div>
                                                        <div style="overflow:hidden;background-color: rgba(0, 0, 0, 0.8);">
                                <img class="slider-img" src="/images/default_image_coming.jpg" alt="">
                            </div>
                                                        <div style="overflow:hidden;background-color: rgba(0, 0, 0, 0.8);">
                                <img class="slider-img" src="/images/default_image_coming.jpg" alt="">
                            </div>
                                                        <div style="overflow:hidden;background-color: rgba(0, 0, 0, 0.8);">
                                <img class="slider-img" src="/images/default_image_coming.jpg" alt="">
                            </div>
                                                        <div style="overflow:hidden;background-color: rgba(0, 0, 0, 0.8);">
                                <img class="slider-img" src="/images/default_image_coming.jpg" alt="">
                            </div>
                                                                            </div>
                                                <div class="slider-nav">                        
                                                        <div class="slider-bar-bg" style="overflow:hidden;background-color: rgba(0, 0, 0, 0.8);">
                                <img class="slider-bar-img" src="http://s3.amazonaws.com/sales-listing-images/b_large/5273/1318952/286329989.jpg" alt="">
                            </div>
                                                        <div class="slider-bar-bg" style="overflow:hidden;background-color: rgba(0, 0, 0, 0.8);">
                                <img class="slider-bar-img" src="/images/default_image_coming.jpg" alt="">
                            </div>
                                                        <div class="slider-bar-bg" style="overflow:hidden;background-color: rgba(0, 0, 0, 0.8);">
                                <img class="slider-bar-img" src="/images/default_image_coming.jpg" alt="">
                            </div>
                                                        <div class="slider-bar-bg" style="overflow:hidden;background-color: rgba(0, 0, 0, 0.8);">
                                <img class="slider-bar-img" src="/images/default_image_coming.jpg" alt="">
                            </div>                          
                                                        <div class="slider-bar-bg" style="overflow:hidden;background-color: rgba(0, 0, 0, 0.8);">
                                <img class="slider-bar-img" src="/images/default_image_coming.jpg" alt="">
                            </div>                                                   
                        </div>

                        <div class="listed-meta" style="text-align:right;">        
                            <div style="float:right;border:solid 3px red;padding:5px;">                    
                                <div class="logo-listed" style="float:right">                                
                                    <img src="/images/namelabel.png" style="border:solid 0px red;max-width:150px;" alt="">
                                </div>
                                <div style="float:right;margin-right:10px;margin-top:0px;">
                                    Provided By:<br>
                                    <span style="font-weight:normal;font-size:13px;">(3 Deals Recently)</span>
                                </div>
                            </div>
                        </div>

                                            </div>
                    <div class="desc">
                        <div class="name">32 East 1st Street</div>
                        <div class="adress">32 East 1st St, Manhattan NY, 10003</div>
                        <div class="det">Condo  in East Village</div>
                        <ul class="list">
                            <li>
                                <div class="text">Total Units:</div>
                                <div class="val">31</div>
                            </li>
                            <li>
                                <div class="text">Floors:</div>
                                <div class="val">10 stories</div>
                            </li>
                            <li>
                                <div class="text">Year Built:</div>
                                <div class="val">2017</div>
                            </li>
                                                        <li>
                                <div class="text">Building Type:</div>
                                <div class="val">Condo</div>
                            </li>
                                                    </ul>
                    </div>
                </div>
                <div class="content-side building">          
                    <div class="block-ttl">
                        Description:
                    </div>
                    <div class="description">
                        <i style="font-weight:normal;font-size:13px;">
                            Provieded by John Connor, XYZ Realty<br>
                            3 Deals Done Recently<br><br>
                        </i>
                        32 East 1st Street features a limited collection of thirty highly designed one to four bedroom residences at the nexus of NoHo, the East Village and Lower East Side...<a href="javascript:void(0)" class="morelink">read more</a>
                        {{--No Fee Apartments on Meserole Street! Loft like renovated apartments in this elevator building
                            one block from the Montrose Ave L train stop. Each apartment features brand new hard wood
                            floors...<a href="javascript:void(0)" class="morelink">read more</a>    --}}             
                    </div>          
                    <div class="block-ttl">Current Available:</div>
                                        <div class="available">
                        <div class="block-sub-ttl">Sale</div>
                                                <div class="available-item">
                            <a href="/show/32_East_1st_Street/71"><div class="bg-img" style="background-image: url( http://s3.amazonaws.com/sales-listing-images/u_thumb/5273/1317966/301294374.jpg )"></div></a>
                            <div class="desc-wr">
                                                                <div class="price-block">       
                                    <div class="name"><a href="/show/32_East_1st_Street/71" style="color:#1d7293">#4C</a></div>                             
                                    <div class="price">$ 3,775,000</div>
                                </div>
                                                                <ul class="benefits">
                                    <li>
                                        <span class="bed">3 beds </span>
                                    </li>
                                    <li>
                                        <span class="bath">3 bath</span>
                                    </li>
                                    <li>
                                        <span class="ft2">1511 ft <sup>2</sup></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                                                <div class="available-item">
                            <a href="/show/32_East_1st_Street/183"><div class="bg-img" style="background-image: url( http://s3.amazonaws.com/sales-listing-images/u_thumb/5273/1317965/301294859.jpg )"></div></a>
                            <div class="desc-wr">
                                                                <div class="price-block">       
                                    <div class="name"><a href="/show/32_East_1st_Street/183" style="color:#1d7293">#2A</a></div>                             
                                    <div class="price">$ 2,900,000</div>
                                </div>
                                                                <ul class="benefits">
                                    <li>
                                        <span class="bed">2 beds </span>
                                    </li>
                                    <li>
                                        <span class="bath">2 bath</span>
                                    </li>
                                    <li>
                                        <span class="ft2">1234 ft <sup>2</sup></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                                                <div class="available-item">
                            <a href="/show/32_East_1st_Street/618"><div class="bg-img" style="background-image: url( http://s3.amazonaws.com/sales-listing-images/u_thumb/5273/1318952/302216740.jpg )"></div></a>
                            <div class="desc-wr">
                                                                <div class="price-block">       
                                    <div class="name"><a href="/show/32_East_1st_Street/618" style="color:#1d7293">#PHC</a></div>                             
                                    <div class="price">$ 10,500,000</div>
                                </div>
                                                                <ul class="benefits">
                                    <li>
                                        <span class="bed">4 beds </span>
                                    </li>
                                    <li>
                                        <span class="bath">5 bath</span>
                                    </li>
                                    <li>
                                        <span class="ft2">2887 ft <sup>2</sup></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                                            </div>
                                        <div class="available">
                        <div class="block-sub-ttl">Rental</div>
                                            </div>
                    
                    

                    <div class="block-ttl">Map View:</div>
                    <div class="map-wr">
                        <!-- !!! -->
                        <!-- Warning -->
                        <!-- google map lgn and lat  -->
                        <!-- !!! -->
                        <div id="div_map"><div id="map_canvas" style="width:100%;height:350px"></div></div>
                    </div>
                </div>
                <div class="sidebar">
                    {{--
                    <div class="sidebar-ttl">Listed By:</div>
                    <ul class="listen-list">
                                                                                                    <li>
                            <div>
                                                                                                        <p style="width:77px;height:77px;display:block;position:relative;overflow: hidden;border-radius: 50%;">
                                        <a href="javascript:void(0)" target="_blank"><img src="" style="display:block;height:auto;width:100%;"></a>
                                    </p>
                                                                                                </div>
                            <div class="text-wr">
                                <div class="name">Someone</div>
                                                                <div class="phone">(123) 456 7890</div>
                                                                                                <div>
                                    <a href="javascript:void(0)" target="_blank" class="link">Website</a>
                                </div>
                                  
                                 
                                                                            <a href="javascript:void(0)" target="_blank" class="link">Some Company</a>
                                                                                                </div>
                        </li>                                                                            
                                            </ul>
                    <div class="sidebar-ttl">Need More Information?</div>
                    <div id="app" class="contact-form">
                        <send inline-template v-bind:type="'building'" v-bind:agentemail="''" v-bind:name="'Guest'" v-bind:listingid="2408">
                            <div>
                                <div class="input-wr">
                                    <label for="inp1">e-mail adress:</label>
                                    <input v-model="useremail" name="email" type="text" id="inp1" placeholder="Write here…" required>
                                </div>
                                <div class="input-wr">
                                    <label for="inp2">phone number:</label>
                                    <input v-model="phone" name="phone" type="text" id="inp2" placeholder="Write here…" required>
                                </div>
                                <div class="input-wr">
                                    <label for="inp3">Description:</label>
                                    <textarea v-model="message" name="message" id="inp3" placeholder="Write here…" required></textarea>
                                </div>
                                <button id="sendButton" type="button" v-on:click="setPost" class="button btn"><div style="width:100%;text-align:center;">Send</div></button>
                                <div id="messageResponse" style="padding-top:10px;"></div>
                            </div>
                        </send>
                    </div>--}}
                </div>
                <div class="currently">
                    <div class="container">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modalMain" class="modal">
    <div class="modal-background"></div>
    <div class="modal-content">
        <div class="box has-text-centered">
            <p>Please <a href="http://www.square81.com/login">sign in</a> if you are already a member<br>
                or <a href="http://www.square81.com/register">sign up</a> to become a member</p>
        </div>
    </div>
    <button class="modal-close"></button>
</div>

<div id="modalSubmit" class="modal">
    <div class="modal-background"></div>
    <div class="modal-content">
        <div class="box has-text-centered">
            Do you want become owner or agent?
            <br>
            <br>
            <form action="http://www.square81.com/changerole" method="POST">
                <input type="hidden" name="_token" value="pevYwEva9k7U192zFuiCM7v182MjqvKaCXeEKJua">
                <div class="buttons has-addons">
                    <label class="button is-success is-selected">
                        <input type="radio" name="type" value="2" checked>
                        Owner
                    </label>
                    <label class="button">
                        <input type="radio" name="type" value="3" >
                        Agent
                    </label>
                </div>
                <br>
                <input type="submit" value="submit" class="button is-primary">
            </form>

        </div>
    </div>
    <button class="modal-close"></button>
</div>	
@include('layouts.footerMain2')
<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4SYF6DSHc9axY_cHSRt7-YIF5GN0w64I"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-form-styler@2.0.2/dist/jquery.formstyler.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>

<script src="/js/main.js"></script>

<script src="/js/bootstrap.min.js"></script>
<script src="/js/moment.min.js"></script>
<script src="/js/fullcalendar.min.js"></script>
<script src="/js/ckeditor/ckeditor.js"></script>
<script src="/js/lightbox.min.js"></script>
<script src="/js/select2.full.min.js"></script>

<script>
    $(window).on('resize orientationchange', function() {
        //hide mobile menu button
        if ($('.header .mobile-btn').hasClass('active')) {
            $('.header .mobile-btn').removeClass('active');
            $('#mobile-menu').fadeOut();
            $('.header .mobile-btn .close').hide()
            $('.header .mobile-btn .open').fadeIn();
            $('body, html').attr('style', '')
        }
    });

    $(".showModal").click(function() {
        $("#modalMain").addClass("is-active");
    });

    $("#showModalSubmit").click(function() {
        $("#modalSubmit").addClass("is-active");
    });

    $(".modal-close").click(function() {
        $(".modal").removeClass("is-active");
    });
</script>
<script>
$(window).on("load", function(){  //wait for images fully loaded first, better than "$(document).ready"
    /*==for slider images==*/
    setTimeout(function(){resizeSlider();}, 100);
    var timer1 = null;
    $(window).on('resize orientationchange', function() {
        clearTimeout(timer1);        
        timer1 = setTimeout(function(){resizeSlider();}, 100);
    });

    $('.slider').css('max-height', '');
});

/*==for slider images==*/
resizeSlider=function() {
    var max_h = 0;

    $('.slider-img').each(function() {
        var img_h = $(this).height();
        var img_w = $(this).width();

        if (img_w > img_h && img_h > max_h){
            max_h = img_h;
            //alert(max_h);
        }
    });

    if (max_h > 0) {
        if (max_h > 380)
            max_h = 380;

        $('.slider-img').each(function() {
            var img_h = $(this).height();
            var img_w = $(this).width();

            if (img_h >= img_w) {
                $(this).height(max_h);
                $(this).width((img_w/img_h)*max_h);
            }
            else {
                $(this).parent().height(max_h);
                $(this).css({'position':'relative', 'top':'50%', 'transform': 'translateY(-50%)'});
            }
        });
    }
    else { //no horizontal images
        $('.slider-img').each(function() {
            var img_h = $(this).height();
            var img_w = $(this).width();

            if (img_h >= img_w && img_h > 380) {
                $(this).height(380);
                $(this).width((img_w/img_h)*380);
            }
        });
    }

    //for slider bar images
    var max_h = 0;

    $('.slider-bar-img').each(function() {
        var img_h = $(this).height();
        var img_w = $(this).width();

        if (img_w > img_h && img_h > max_h){
            max_h = img_h;
            //alert(max_h);
        }
    });

    if (max_h > 0) {
        if (max_h > 71)
            max_h = 71;

        $('.slider-bar-img').each(function() {
            var img_h = $(this).height();
            var img_w = $(this).width();

            if (img_h >= img_w) {
                $(this).height(max_h);
                $(this).width((img_w/img_h)*max_h);
            }
            else {
                $(this).parent().height(max_h);
                $(this).css({'position':'relative', 'top':'50%', 'transform': 'translateY(-50%)'});
            }
        });
    }
    else { //no horizontal images
        $('.slider-bar-img').each(function() {
            var img_h = $(this).height();
            var img_w = $(this).width();

            if (img_h >= img_w && img_h > 71) {
                $(this).height(71);
                $(this).width((img_w/img_h)*71);
            }
        });
    }
}

/*==for map==*/
var map = new google.maps.Map(document.getElementById('map_canvas'), {
    zoom: 16,
    center: {lat: 40.692464, lng: -74.2008182},
});
var geocoder = new google.maps.Geocoder();

geocodeAddress(geocoder, map);

function geocodeAddress(geocoder, resultsMap) {
    var address = '32 East 1st St Manhattan 10003';
    geocoder.geocode({'address': address}, function(results, status) {
        if (status === 'OK') {
            resultsMap.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
                map: resultsMap,
                position: results[0].geometry.location
            });
        } else {
            console.log('Geocode was not successful for the following reason: ' + status);
        }
    });
}
</script>

</body>

</html>
