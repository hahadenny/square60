@extends('layouts.app')

@section('header')
    <header>
        @include('layouts.header')
    </header>
@endsection

@section('content')
    <div id="app">
        <div>
            <section >
                <div class="main-content columns is-mobile is-centered">
                    <div class="column is-half is-narrow">

                        <div class="panel-body">
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <h2 class="title is-4 has-text-centered">{{isset($article) ? 'Your article' : 'Your new  article'}}</h2>

                            <div id="messageResponse" class="has-text-centered"></div>

                            <form method="POST" action="{{ route('addArticle') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <div class="column margin_col">
                                    <div class="fields content">
                                        <label class="label" for="">Title:</label>
                                        <input name="title" class="input" value="{{isset($article) ? $article->title : old('title')}}" />
                                        @if ($errors->has('title'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('title') }}</strong>
                                            </span>
                                        @endif
                                        @if(isset($article))
                                            <input name="id" type="hidden" class="input" value="{{isset($article) ? $article->id : old('id')}}" />
                                        @endif
                                    </div>
                                    <div class="fields content">
                                        <label class="label" for="">Text:</label>
                                        <textarea id="editor" name="text" class="textarea" rows="5" minlength="5" > {{isset($article) ? $article->text : \Illuminate\Support\Facades\Input::old('text')}}</textarea>
                                        @if ($errors->has('text'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('text') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="fields content has-text-right">
                                        <button type="submit" class="button is-primary ">
                                            save
                                        </button>
                                    </div>
                                </div>

                            </form>

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
        var editor = CKEDITOR.replace( 'editor' );
    </script>
@endsection