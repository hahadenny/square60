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
                <div class="column is-8">
                    @foreach($newsList as $item)
                        <a href="/news/{{$item->id}}">{{$item->title}}</a><br>
                    @endforeach
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