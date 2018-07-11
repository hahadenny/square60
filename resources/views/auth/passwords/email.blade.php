@extends('layouts.app')

@section('header')
    {{--<header >
        @include('layouts.header')
    </header>--}}
@endsection

@section('content')
{{--<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Send Password Reset Link
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>--}}

<div class="mobile-menu" id="mobile-menu">
    @include('layouts.header2_1')        
</div>

@include('partial.header')  

<div class="main-content columns is-mobile is-centered" style="margin-bottom:40px;">
    <div class="column is-half is-narrow">

        <div class="hero is-small" style="margin-top:0px;">
            <div class="hero-body">
                <div class="box">
                    <div class="is-size-3 has-text-centered">Reset Password</div>
                    <hr/>



                    <form method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}

                        <div class="field">
                            <label class="label">Email</label>
                            <div class="control has-icons-left ">
                                <input class="input" id="email" type="email" name="email" value="{{ old('email') }}" required>
                                <span class="icon is-small is-left">
                                  <i class="fa fa-envelope"></i>
                                </span>
                            </div>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif

                            @if (session('status'))
                                <div class="alert alert-success">
                                    <span class="help-block">
                                    <strong>{{ session('status') }}</strong>
                                    </span>
                                </div>
                            @endif

                        </div>

                        <button type="submit" class="button is-primary mainbgc" style="margin-top:20px;margin-bottom:20px;">Send Password Reset Link</button>


                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    {{--@include('layouts.footer')--}}
    @include('layouts.footerMain2')
@endsection
