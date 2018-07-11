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
    @include('partial.header')  
    <section>

        <div class="tabs is-toggle is-centered type-select" style="margin-top:20px;">
            <ul>
                <li class="nem" style="background-color:#3e65a9;@if(Auth::user()->isMan()) display:none; @endif">
                    <a href="{{route('sell')}}" class="nem2" data-type="0" style="color:#fff;">
                        New Sale
                    </a>
                </li>
                <li class="nem" style="background-color:#3e65a9;">
                    <a href="{{route('rental')}}" class="nem2" data-type="0" style="color:#fff;">
                        New Rental
                    </a>
                </li>
            </ul>
        </div>

        <div class="main-content columns is-mobile is-centered" style="margin-bottom:40px;">
            <div class="content" style="margin-top:20px;">
                @if (session('status'))
                    <div class="alert alert-success" style="margin-bottom:2rem;text-align:center;">
                        {!! session('status') !!}
                    </div>
                @endif

                <h4 style="margin-bottom:2.5rem; width:100%; text-align:center;">{{Auth::user()->name}}'s Listings</h4>

                @if(Auth::user()->premium)
                <div class="p_listings" style="margin-bottom:2rem;color:red;text-align:left;font-weight:600;">* You will have 25% discount on feature listing!<br><br></div>
                @endif
                
                <div class="p_listings">
                @include('partial.listings')
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