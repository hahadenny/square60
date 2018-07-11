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

                <div class="main-content columns is-mobile is-centered" style="margin-bottom:40px;">
                    <div class="content p_listings" style="text-align:center;">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <h4>{{Auth::user()->name}}'s Billing</h4><br>

                            <div class="columns">
                                <div class="column">
                                    <a href="{{route('upgrade')}}{{--{{route('expertBilling')}}--}}" class="button is-primary mainbgc nem" style="width:200px;">
                                        Membership
                                    </a>
                                </div>
                                <div class="column">
                                    <a href="{{route('nameLabelBilling')}}" class="button is-primary mainbgc nem" style="width:200px;">
                                        Name Label
                                    </a>
                                </div>
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