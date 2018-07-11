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
<div class="main-content columns is-mobile is-centered" style="margin-bottom:40px;">
    
    <div class="column is-half is-narrow">

        <div class="hero is-small" style="margin-top:0px;">
            <div class="hero-body">
                <div class="box">
                    <div class="is-size-3 has-text-centered">Sign Up</div>
                    <hr/>

                    <form  name="registerForm" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="field">
                            <label class="label">Type</label>
                            <div class="control">
                                <div class="buttons has-addons">
                                    <label class="button is-success is-selected" style="margin-bottom:10px;">
                                        <input type="radio" name="type" value="1" @if(old('type','type')== "1") checked @endif  checked />
                                        User
                                    </label>
                                    <label class="button">
                                        <input type="radio" name="type" value="2" @if(old('type','type') == "2") checked @endif>
                                        Owner
                                    </label>
                                    <label class="button">
                                        <input type="radio" name="type" value="3" @if(old('type','type') == "3") checked @endif>
                                        Agent
                                    </label>
                                    <label class="button">
                                        <input type="radio" name="type" value="5" @if(old('type','type') == "5") checked @endif>
                                        Management
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="agentID" class="field" style="display:none;">
                            <label class="label">Copy of Your License <span style="color:red;display:inline;">*</span></label>
                            <div class="control has-icons-left ">
                                    <div id="images-1" class="image-wrapper" style="display:none;width: 50%; min-width:200px; height:auto; padding:0px;">
                                        <img id="blah" src="#" alt="" />
                                    </div>
                                    <input id="agent_license" type="file" name="license" class="select is-primary" value="{{ old('license') }}" id="license" accept="image/*,application/pdf" onchange="readURL(this);">
                            </div>

                            @if ($errors->has('license'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('license') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div id="agentLink" class="field" style="display:none;">
                            <label class="label">OR<div style="margin-top:10px;">Real Estate Agent Profile Link <span style="color:red;display:inline;">*</span></div></label>
                            <div class="control has-icons-left ">
                                <input id="agent_web_site" class="input" type="text" placeholder="Web Site" name="web_site" value="{{old('web_site')}}">
                                <span class="icon is-small is-left">
                                  <i class="fa fa-globe"></i>
                                </span>
                            </div>

                            @if ($errors->has('web_site'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('web_site') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="field">
                            <label class="label">Name</label>
                            <div class="control has-icons-left ">
                                <input class="input" type="text" name="name" value="{{ old('name') }}" placeholder="Name input" autofocus>
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
                            <label class="label">Telephone</label>
                            <div class="control has-icons-left ">
                                <input class="input" type="text" placeholder="Email telephone" name="phone" value="{{ old('phone') }}" >
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
                            <label class="label">Email</label>
                            <div class="control has-icons-left ">
                                <input class="input" type="email" placeholder="Email input" name="email" value="{{ old('email') }}" required>
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

                        <div id="emailConfirm" class="field" style="display: none">
                            <label class="label">Confirm Email</label>
                            <div class="control has-icons-left ">
                                <input class="input" id="email-confirm" type="email" name="email_confirmation"   placeholder="Confirm email" value="{{ old('email_confirmation') }}">
                                <span class="icon is-small is-left">
                                  <i class="fa fa-envelope"></i>
                                </span>
                            </div>

                            @if ($errors->has('email_confirmation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email_confirmation') }}</strong>
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

                        <button type="submit" class="button is-primary mainbgc" onclick="validateForm();">
                            Register
                        </button>


                    </form>

                </div>
            </div>
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


@section('additional_scripts')
    <script>

        var oldType = '{{old("type")}}';

        if(oldType == 2 || oldType == 5){
            $('label.is-success').removeClass('is-success is-selected');
            $(':radio:checked').closest('label').addClass('is-success is-selected');
            $('#emailConfirm').show();
        }else if(oldType == 3){
            $('label.is-success').removeClass('is-success is-selected');
            $(':radio:checked').closest('label').addClass('is-success is-selected');
            $('#emailConfirm').show();
            $('#agentID').show();
            $('#agentLink').show();
        }else if (oldType == 1){
            $('#emailConfirm').hide();
        }

        $("input[name=type]:radio").change(function () {

            $(':radio:checked').not(this).prop('checked',false)
            $('label.is-success').removeClass('is-success is-selected'); //Reset selection

            $(this).closest('label').addClass('is-success is-selected');   //Add class to list item

            $('#agentID').hide();
            $('#agentLink').hide();

            var value = $(this).val();
            if(value == 3) {
                $('#agentID').show();
                $('#agentLink').show();
                $('#emailConfirm').show();
            }
            if(value == 2 || value == 5){
                $('#emailConfirm').show();
            }else if (value == 1){
                $('#emailConfirm').hide();
            }

        });

        function readURL(input) {
            $("#images-1").show();
            if (input.files && input.files[0]) {

                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width('100%').height('auto');
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function validateForm() {
            if($("input[name=type]:radio:checked").val() == 3) {
                if (!$('#agent_license').val() && !$('#agent_web_site').val()) {
                    swal('All agents must provide either an Agent License or a Real Estate Agent Profile Link.');
                    return false;
                }
            }
            document.registerForm.submit();
        }
    </script>
@endsection
