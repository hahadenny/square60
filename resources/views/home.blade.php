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
            <div class="column is-half is-narrow">

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                        @if (session('error'))
                            <div class="alert alert-error">
                                {{ session('error') }}
                            </div>
                        @endif

                    <form method="POST" action="{{ route('edit') }}" >
                        {{ csrf_field() }}
                        <div class="field">
                            <label class="label">Name</label>
                                <div class="control has-icons-left ">
                                    <input class="input" type="text" name="name" value="{{ Auth::user()->name }}" placeholder="Name input" required autofocus>
                                    <input class="input" type="hidden" name="id" value="{{ Auth::user()->id }}">
                                    <span class="icon is-small is-left">
                                        <i class="fa fa-user"></i>
                                    </span>
                                </div>

                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="field">
                            <label class="label">Email</label>
                                <div class="control has-icons-left ">
                                    <input class="input" type="email" placeholder="Email input" name="email" value="{{ Auth::user()->email }}" required>
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

                        {{--<div class="field">
                            <label class="label">Phone</label>
                                <div class="control has-icons-left ">
                                    <input class="input" type="text" placeholder="Phone input" name="phone" value="{{ Auth::user()->phone }}" required>
                                    <span class="icon is-small is-left">
                                        <i class="fa fa-phone"></i>
                                    </span>
                                </div>

                            @if ($errors->has('phone'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                            @endif
                        </div>--}}

                        <div class="field">
                            <label class="label">Current password</label>
                            <div class="control">
                                <input class="input" id="currentPassword" type="password" name="currentPassword" required>
                            </div>
                            @if ($errors->has('currentPassword'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('currentPassword') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="field">
                            <label class="label">Password</label>
                                <div class="control">
                                    <input class="input" id="password" type="password" name="password" required>
                                </div>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="field">
                            <label class="label">Confirm Password</label>
                                <div class="control">
                                    <input class="input" id="password-confirm" type="password" name="password_confirmation" required>
                                </div>
                        </div>

                        <button type="submit" class="button is-primary mainbgc" style="margin-top:10px;">
                            Update
                        </button>

                    </form>
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
