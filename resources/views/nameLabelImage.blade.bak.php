@extends('layouts.app')

@section('header')
    {{--<header>
        @include('layouts.header')
    </header>--}}
@endsection

@section('content')
    <div class="mobile-menu" id="mobile-menu">
        @include('layouts.header2_1')        
    </div>
    <div id="app">
        <div>

            <div class="header test-border">
                <div class="container">
                    <div class="logo">
                        <a href="/">
                            <img src="/images/white-logo.svg" alt="">
                        </a>
                    </div>
                    <div class="header-nav">
                        @include('layouts.header2_1')        
                        <div class="mobile-btn" style="cursor:pointer;">
                            <div class="open">
                                <span class="hamb">
                                    <img src="/images/menu2.png" alt="" style="width:25px;">
                                </span>
                                <span class="text" style="color:#fff;">menu</span>
                            </div>
                            <div class="close">
                                <span class="hamb">
                                    <img src="/images/Close2.png" alt="" style="width:25px;">
                                </span>
                                <span class="text" style="color:#fff;">close</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <section class="hero" style="margin-top:0px;">
                <div class="hero-body" style="margin-bottom:40px;">
                    <div class="container">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/" onclick="window.history.go(-1); return false;">Previous page</a>
                            </li>
                        </ol>
                        <div class="columns">
                            <div class="column is-5 is-offset-1">
                                <div class="image-gallery">
                                    <div id="buildingImages">
                                        <div class="">
                                            <img src="http://squareyard360.com/images/default_image_coming.jpg" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-5">

                                <br>
                                <p>
                                    <span class="is-size-5">Building name: One Sixty Madison</span><br>
                                    <span class="is-size-5">160 madison Avenue, Manhattan NY 1016</span>

                                </p>
                                <br>
                                <div class="columns">
                                    <div class="column">
                                        <ul>
                                            <li>Condo</li>
                                            <li>Units: 319</li>
                                        </ul>
                                    </div>
                                    <div class="column">
                                        <ul>
                                            <li>Midtown South</li>
                                            <li>Stories: 45</li>
                                            <li>2015</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="column is-5 is-offset-1">
                            <p class="is-size-4">provided by Corcoran Group</p>
                            <p class="is-size-4"><img src="http://s3.amazonaws.com/images-rental/logo/30061/2296510/corcoran_2x.png" /> John Doe</p>

                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
@endsection

@section('footer')
    {{--@include('layouts.footer')--}}
    @include('layouts.footerMain2')
@endsection

@section('additional_scripts')
    <script>



    </script>
@endsection