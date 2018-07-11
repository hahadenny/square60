@extends('layouts.app2')

@section('header')
@endsection

@section('content')
	<div class="wrapper">
		<div class="content">
			<div class="container" style="border:solid 1px;">
				<div class="promo">
					<div class="logo" style="margin-top:40px; border:solid 1px;">
						<img src="images/logo.svg" alt="">
					</div>					
					<ul class="promo-nav" style="box-shadow: 1px 4px 8px #ccc;">
                        <li>
							<a href="/sales">Sales</a>
						</li>
						<li>
							<a href="/rentals">Rentals</a>
						</li>					
					</ul>
					<div class="promo-preview">
						<ul class="row">
							<li>
								<div class="preview-item" style="background-image: url(images/bg-home-1.jpg)">
									<a href="#" class="link"></a>
									<div class="price">$1 935 353</div>
									<div class="details">
										<span>3 beds</span>
										<span>1 bath</span>
										<span class="place">Hell’s Kitchen</span>
									</div>
								</div>
							</li>
							<li>
								<div class="preview-item" style="background-image: url(images/bg-home-2.jpg)">
									<a href="#" class="link"></a>
									<div class="price">$1 935 353</div>
									<div class="details">
										<span>3 beds</span>
										<span>1 bath</span>
										<span class="place">Hell’s Kitchen</span>
									</div>
								</div>
							</li>
							<li>
								<div class="preview-item" style="background-image: url(images/bg-home-3.jpg)">
									<a href="#" class="link"></a>
									<div class="price">$1 935 353</div>
									<div class="details">
										<span>3 beds</span>
										<span>1 bath</span>
										<span class="place">Hell’s Kitchen</span>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
        </div>
    </div>
@endsection
        
@section('footer')
    @include('layouts.footerMain2')
@endsection        

@section('additional_scripts')
@endsection