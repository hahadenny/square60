@extends('layouts.app')

@section('header')
    <header>
        @include('layouts.header')
    </header>
@endsection

@section('content')
    <div id="app">
        <div>
            <section>
                <div class="main-content columns is-mobile is-centered">
                    <div class="column is-half is-narrow">

                        <h3 class="has-text-centered">Update user information</h3>
                        <br>

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

                            <form method="POST" action="{{ route('updateUser') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <div class="field">
                                    <label class="label">Name</label>
                                    <div class="control has-icons-left ">
                                        <input class="input" type="text" name="name" value="{{ $userDate->name }}" placeholder="Name input" autofocus>
                                        <input class="input" type="hidden" name="id" value="{{ $userDate->id }}">
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
                                        <input class="input" type="email" placeholder="Email input" name="email" value="{{ $userDate->email }}" >
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
                                    <label class="label">Phone</label>
                                    <div class="control has-icons-left ">
                                        <input class="input" type="text" placeholder="Phone input" name="phone" value="{{ $userDate->phone }}">
                                        <span class="icon is-small is-left">
                                        <i class="fa fa-phone"></i>
                                    </span>
                                    </div>

                                    @if ($errors->has('phone'))
                                        <span class="help-block">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="field">
                                    <label class="label">Password</label>
                                    <div class="control">
                                        <input class="input" id="password" type="password" name="password">
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
                                        <input class="input" id="password-confirm" type="password" name="password_confirmation">
                                    </div>
                                </div>

                                <button type="submit" class="button is-primary">
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
    @include('layouts.footer')
@endsection
