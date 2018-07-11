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

            <section class="is-menu-custom" style="padding-left: 10px; padding-right: 10px;">
                <div class="main-content columns is-mobile is-centered" style="margin-bottom:40px;">

                    @include('partial.sidemenu')

                    <div class="column is-half is-narrow is-custom">

                        <div class="panel-body">
                            @if (session('status'))
                                <div class="alert alert-success" style="margin-bottom:40px;">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <div id="messageResponse" class="has-text-centered"></div>
                            <h2 class="title is-4 has-text-centered">Your Profile</h2>
                            <form method="POST" action="{{ route('editProfileMan') }}" enctype="multipart/form-data" >
                                {{ csrf_field() }}
                                <div class="columns is-variable form-content">
                                    <div class="column">
                                        <div class="field">
                                            <label class="label">Name</label>
                                            <div class="control has-icons-left ">
                                                <input class="input" type="hidden" name="id" value="{{ Auth::user()->id }}">
                                                <input class="input" type="text" placeholder="First Name" name="firstName" value="{{isset(Auth::user()->name) ? Auth::user()->name : old('firstName')}}" required>
                                                <span class="icon is-small is-left"><i class="fa fa-user"></i></span>
                                            </div>

                                            @if ($errors->has('firstName'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('firstName') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="field">
                                    <label class="label">Email</label>
                                    <div class="control has-icons-left ">
                                        <input class="input" type="email" placeholder="Email input" name="email" value="{{isset(Auth::user()->email) ? Auth::user()->email : old('email')}}" required>
                                        <span class="icon is-small is-left">
                                            <i class="fa fa-envelope"></i>
                                        </span>
                                    </div>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="field">
                                    <label class="label">Cell Phone</label>
                                    <div class="control has-icons-left ">
                                        <input class="input" type="text" placeholder="Phone input" name="cellPhone" value="{{isset(Auth::user()->phone) ? Auth::user()->phone : old('cellPhone')}}" required>
                                        <span class="icon is-small is-left">
                                        <i class="fa fa-mobile"></i>
                                    </span>
                                    </div>

                                    @if ($errors->has('cellPhone'))
                                        <span class="help-block">
                                    <strong>{{ $errors->first('cellPhone') }}</strong>
                                </span>
                                    @endif
                                </div>

                                <button type="submit" class="button is-primary mainbgc" style="margin-top:20px;">
                                    Save
                                </button>

                            </form>

                        </div>
                        <br>
                        @include('partial.listings')
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
        var editor = CKEDITOR.replace( 'editor' );

        function readURL(input) {
            $("#images-1").show();
            if (input.files && input.files[0]) {

                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width('100%')
                        .height(200);
                };

                reader.readAsDataURL(input.files[0]);
            }
            $('#deleteButton').show();
        }

        $("#deleteButton").click(function(){
            $("#images-1").hide();
            $("#photo").val('');
        });
    </script>
@endsection