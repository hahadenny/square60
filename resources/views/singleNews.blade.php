@extends('layouts.app')
@section('header')
    <header>
        @include('layouts.header')
    </header>
@endsection
@section('content')
    <div id="app">
        <div>
            <section class="hero">
                <div class="hero-body">
                    <div class="container">
                        <div class="columns">
                            <div class="column is-12">
                                <h1>{{$news->title}}</h1>
                                <br>
                                <p>{!! $news->text !!}</p>
                            </div>
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

    </script>
@endsection