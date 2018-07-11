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
                            <div id="messageResponse" class="has-text-centered"></div>
                            <h2 class="title is-4 has-text-centered">Your Expert profile</h2>
                            <form method="POST" action="{{ route('editProfile') }}" enctype="multipart/form-data" >
                                {{ csrf_field() }}
                                <div class="columns is-variable form-content">
                                    <div class="column">
                                        <div class="field">
                                            <label class="label">Last Name</label>
                                            <div class="control has-icons-left ">
                                                <input class="input" type="text" name="lastName" value="{{isset($agent) ? $agent->last_name : old('lastName')}}" placeholder="Last Name input" required autofocus>
                                                <input class="input" type="hidden" name="id" value="{{ Auth::user()->id }}">
                                                <span class="icon is-small is-left"><i class="fa fa-user"></i></span>
                                            </div>
                                            @if ($errors->has('lastName'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('lastName') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                            <div class="field">
                                                <label class="label">First Name</label>
                                                <div class="control has-icons-left ">
                                                    <input class="input" type="text" placeholder="First Name" name="firstName" value="{{isset($agent) ? $agent->first_name : old('firstName')}}" required>
                                                    <span class="icon is-small is-left"><i class="fa fa-user"></i></span>
                                                </div>

                                                @if ($errors->has('firstName'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('firstName') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                    </div>
                                    <div class="column">
                                        <div class="field has-text-centered">
                                            <label class="label ">
                                                @if(isset($agent) && $agent->company != '')
                                                    {{$agent->company}}
                                                @endif
                                            </label>
                                            <div class="control has-icons-left ">

                                                @if(isset($agent) && $agent->logo_path != '')
                                                    <div class="image-wrapper" id="logo-1">

                                                        @if($agent->user_id == 0)
                                                            <img src="{{ env('S3_IMG_PATH') }}{{$agent->logo_path}}"  style=" height:auto;"/>
                                                        @else
                                                            <img src="{{ env('S3_IMG_PATH_1') }}{{$agent->logo_path}}"  style="height:auto;"/>
                                                        @endif

                                                        <div class="top-right">
                                                            <deletephoto inline-template v-bind:list="'{{$agent->id}}'" v-bind:num="1" :names="'logo'">
                                                                <button type="button" v-on:click="deleteImage"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                            </deletephoto>
                                                        </div>
                                                    </div>
                                                    <label class="label">Update logo</label>
                                                    <input type="file" name="logo" class="select is-primary" value="{{ old('logo') }}" id="logo" onchange="readLogo(this);">
                                                @else
                                                    <div class="image-wrapper" id="logo-1">
                                                        <img id="blahLogo" src="#" alt="" />
                                                        <div class="top-right">
                                                            <button id="deleteLogoButton" type="button" style="display: none"><i class="fa fa-trash"></i></button>
                                                        </div>
                                                    </div>
                                                    <input type="file" name="logo" class="select is-primary" value="{{ old('logo') }}" id="logo" onchange="readLogo(this);">

                                                @endif
                                            </div>

                                            @if ($errors->has('logo'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('logo') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="column">
                                        <div class="field">
                                            <label class="label">Photo</label>
                                            <div class="control has-icons-left ">

                                                @if(isset($agent) && $agent->photo_url != '')
                                                    <div class="image-wrapper" id="images-1" style="width: 70%">

                                                        @if($agent->user_id == 0)
                                                            <img src="{{ env('S3_IMG_PATH') }}images-rental/{{$agent->logo_path}}"  style="width:150px; height:150px; border-radius:50%; margin-right:25px;"/>
                                                        @else
                                                            <img src="{{ env('S3_IMG_PATH_1') }}{{$agent->photo_url}}"  style="width:150px; height:150px; border-radius:50%; margin-right:25px;"/>
                                                        @endif

                                                        <div class="top-right">
                                                            <deletephoto inline-template v-bind:list="'{{$agent->id}}'" v-bind:num="1" :names="'images'">
                                                                <button type="button" v-on:click="deleteImage"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                            </deletephoto>
                                                        </div>
                                                    </div>
                                                    <label class="label">Update photo</label>
                                                    <input type="file" name="photo" class="select is-primary" value="{{ old('photo') }}" id="photo" onchange="readURL(this);">
                                                @else
                                                    <div class="image-wrapper" id="images-1" style="width: 70%">
                                                        <img id="blah" src="#" alt="" />
                                                        <div class="top-right">
                                                            <button id="deleteButton" type="button" style="display: none"><i class="fa fa-trash"></i></button>
                                                        </div>
                                                    </div>
                                                    <input type="file" name="photo" class="select is-primary" value="{{ old('photo') }}" id="photo" onchange="readURL(this);">

                                                @endif
                                            </div>

                                            @if ($errors->has('photo'))
                                                <span class="help-block">
                                    <strong>{{ $errors->first('photo') }}</strong>
                                </span>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                                <div class="columns is-variable form-content">
                                    <div class="column is-two-thirds">
                                        <div class="field">
                                            <label class="label" for="">Bio:</label>
                                            <textarea id="editor" name="description" class="textarea" rows="5" minlength="5" required>{{isset($agent) ? $agent->description : old('description')}} </textarea>
                                            @if ($errors->has('description'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('description') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="column">
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
                                    </div>
                                </div>

                                <div class="has-text-centered ">
                                    <button id="showMore" type="button" class="button is-primary">
                                        More
                                    </button>
                                </div>


                                <div id="more" style="display: none">
                                    <div class="field">
                                        <label class="label">Web-site</label>
                                        <div class="control has-icons-left ">
                                            <input class="input" type="text" placeholder="Web site" name="webLink" value="{{isset($agent) ? $agent->web_site : old('webLink')}}" required>
                                            <span class="icon is-small is-left"><i class="fa fa-link"></i></span>
                                        </div>

                                        @if ($errors->has('webLink'))
                                            <span class="help-block">
                                    <strong>{{ $errors->first('webLink') }}</strong>
                                </span>
                                        @endif
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
                                        <label class="label">Company</label>
                                        <div class="control has-icons-left ">
                                            <input class="input" type="text" placeholder="Company" name="company" value="{{isset($agent) ? $agent->company : old('company')}}" required>
                                            <span class="icon is-small is-left"><i class="fa fa-building"></i></span>
                                        </div>

                                        @if ($errors->has('company'))
                                            <span class="help-block">
                                    <strong>{{ $errors->first('company') }}</strong>
                                </span>
                                        @endif
                                    </div>

                                    <div class="field">
                                        <label class="label">Office Phone</label>
                                        <div class="control has-icons-left ">
                                            <input class="input" type="text" placeholder="Office Phone" name="officePhone" value="{{isset($agent) ? $agent->office_phone : old('officePhone')}}" required>
                                            <span class="icon is-small is-left">
                                        <i class="fa fa-phone"></i>
                                    </span>
                                        </div>

                                        @if ($errors->has('officePhone'))
                                            <span class="help-block">
                                    <strong>{{ $errors->first('officePhone') }}</strong>
                                </span>
                                        @endif
                                    </div>



                                    <div class="field">
                                        <label class="label">Fax</label>
                                        <div class="control has-icons-left ">
                                            <input class="input" type="text" placeholder="Fax input" name="fax" value="{{isset($agent) ? $agent->fax : old('fax')}}" required>
                                            <span class="icon is-small is-left">
                                        <i class="fa fa-fax"></i>
                                    </span>
                                        </div>

                                        @if ($errors->has('fax'))
                                            <span class="help-block">
                                    <strong>{{ $errors->first('fax') }}</strong>
                                </span>
                                        @endif
                                    </div>





                                <button type="submit" class="button is-primary">
                                    Save
                                </button>

                                </div>

                            </form>



                                <br>
                                @include('partial.listings')
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

        function readLogo(input) {
            $("#logo-1").show();
            if (input.files && input.files[0]) {

                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#blahLogo')
                        .attr('src', e.target.result)
                        .width('100%')
                        .height(200);
                };

                reader.readAsDataURL(input.files[0]);
            }
            $('#deleteLogoButton').show();
        }

        $("#deleteLogoButton").click(function(){
            $("#logo-1").hide();
            $("#logo").val('');
        });

        $("#showMore").click(function(){
            $(this).hide();
            $('#more').show();
        });
    </script>
@endsection