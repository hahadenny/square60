@extends('layouts.app2')

@section('header')
@endsection

@section('content')
	<div class="wrapper wrapper-bg" style="min-height:835px;">aabbccddee
		<div class="content" style="flex:none;">
			<div class="header test-border header-bg" style="border-bottom: solid 1px #282828;">
				<div class="promo" style="padding-bottom:0px;">
					<div class="logo test-border" style="margin-top:0px; padding-top:10px; padding-bottom:0px;">
						<img src="images/logo2.png" alt="" style="height:60px">
					</div>
				</div>
			</div>		
		</div>		
		{{--<div class="container" style="margin-top:20px;">
			<div id="jssor_1" style="position:relative;top:0px;left:0px;width:800px;height:400px;overflow:hidden;">
				<div data-u="slides" style="position:absolute;top:0px;left:0px;width:800px;height:400px;overflow:hidden;">
					<div><img data-u="image" src="/images/view1.png" /></div>
					<div><img data-u="image" src="/images/view2.png" /></div>
				</div>
            </div>
		</div>--}}
		<div class="container test-border">
			
			<div class="promo">				
				<ul class="promo-nav" style="box-shadow: 1px 4px 8px #ccc;margin-top:48px;">
					<li>
						<a href="/sales" id="a-sales">Sales</a>
					</li>
					<li>
						<a href="/rentals" id="a-rentals">Rentals</a>
					</li>					
				</ul>
				<div class="promo-preview" style="margin-top:60px;position:relative;">
					<ul class="row">
						<li>
							<div class="preview-item" style="background-image: url(/images/h3.png)">
								<a href="#" class="link"></a>
								<div class="price" style="opacity:0;">$1 935 353</div>
								<div class="details" style="opacity:0;">
									<span>3 beds</span>
									<span>1 bath</span>
									<span class="place">Hell’s Kitchen</span>
								</div>
							</div>
						</li>
						<li>
							<div class="preview-item" style="background-image: url(/images/h2.png)">
								<a href="#" class="link"></a>
								<div class="price" style="opacity:0;">$1 935 353</div>
								<div class="details" style="opacity:0;">
									<span>3 beds</span>
									<span>1 bath</span>
									<span class="place">Hell’s Kitchen</span>
								</div>
							</div>
						</li>
						<li>
							<div class="preview-item" style="background-image: url(/images/h4.png)">
								<a href="#" class="link"></a>
								<div class="price" style="opacity:0;">$1 935 353</div>
								<div class="details" style="opacity:0;">
									<span>3 beds</span>
									<span>1 bath</span>
									<span class="place">Hell’s Kitchen</span>
								</div>
							</div>
						</li>
					</ul>
					<div><img src="/images/nyc_love.jpg" class="nyclove" /></div>
				</div>
			</div>
        </div>
    </div>
@endsection
        
@section('footer')
    @include('layouts.footerMain2')
@endsection        

@section('additional_scripts')
<script>
	$(document).ready(function ($) {
		$('#a-rentals').on('mouseover', function() {
			$('#a-sales').css({'background':'linear-gradient(#efefef, #fff)', 'background-color':'none', 'color':'#595e60'});
		});

		$('#a-rentals').on('mouseout', function() {
			$('#a-sales').css({'background':'none', 'background-color':'#a6b8d5', 'color':'#595e60'});
		});

        {{--
		var options = { $AutoPlay: 1, 
						$Idle: 3000,
						$SlideshowOptions: {
							$Class: $JssorSlideshowRunner$,
							$Transitions: [{$Duration:500,$Delay:12,$Cols:10,$Rows:5,$Clip:15,$SlideOut:true,$Formation:$JssorSlideshowFormations$.$FormationStraightStairs,$Easing:$Jease$.$OutQuad,$Opacity:2,$Assembly:2049}]
						}
					  };
        var jssor_slider1 = new $JssorSlider$("jssor_1", options);

		//responsive code begin
        //remove responsive code if you don't want the slider scales
        //while window resizing
        function ScaleSlider() {
			//alert($(window).width())
			if ($(window).width() <= 767)
				var parentWidth = $('#jssor_1').parent().parent().width()-30;
			else {
            	var parentWidth = $('#jssor_1').parent().width();
				if (parentWidth < 767) 
					var parentWidth = $('#jssor_1').parent().parent().width()-30;
			}

			if (parentWidth > 800)   //maximum width: 800px
				parentWidth = 800;

			//alert(parentWidth);

            if (parentWidth) {
                jssor_slider1.$ScaleWidth(parentWidth);
				//jssor_slider1.$ScaleHeight(parentWidth/3);
            }
            else
                window.setTimeout(ScaleSlider, 30);
        }
        //Scale slider after document ready
        ScaleSlider();
                                        
        //Scale slider while window load/resize/orientationchange.
        $(window).bind("load", ScaleSlider);
        $(window).bind("resize", ScaleSlider);
        $(window).bind("orientationchange", ScaleSlider);
        //responsive code end 
		--}}
    });
</script>
@endsection