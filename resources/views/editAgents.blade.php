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

                            <h2 class="title is-4 has-text-centered">Agent profile</h2>
                            <form method="POST" action="{{ route('updateAgent') }}" enctype="multipart/form-data" >
                                {{ csrf_field() }}
                                <div class="columns is-variable form-content">
                                    <div class="column">
                                        <div class="field">
                                            <label class="label">Last Name</label>
                                            <div class="control has-icons-left ">
                                                <input class="input" type="text" name="lastName" value="{{isset($agent) ? $agent->last_name : old('lastName')}}" placeholder="Last Name input" autofocus>
                                                <input class="input" type="hidden" name="id" value="{{isset($agent) ? $agent->id : old('id')}}">
                                                <span class="icon is-small is-left"><i class="fa fa-user"></i></span>
                                            </div>


                                            @if ($errors->has('lastName'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('lastName') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="column">
                                        <div class="field">
                                            <label class="label">First Name</label>
                                            <div class="control has-icons-left ">
                                                <input class="input" type="text" placeholder="First Name" name="firstName" value="{{isset($agent) ? $agent->first_name : old('firstName')}}">
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
                                    <label class="label">Web-site</label>
                                    <div class="control has-icons-left ">
                                        <input class="input" type="text" placeholder="Web site" name="webLink" value="{{isset($agent) ? $agent->web_site : old('webLink')}}">
                                        <span class="icon is-small is-left"><i class="fa fa-link"></i></span>
                                    </div>

                                    @if ($errors->has('webLink'))
                                        <span class="help-block">
                                    <strong>{{ $errors->first('webLink') }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="field">
                                    <label class="label">Photo</label>
                                    <div class="control has-icons-left" >

                                        @if(isset($agent) && $agent->photo_url != '')
                                            <div class="image-wrapper" id="images-1"  style="width: 30%">

                                                @if($agent->user_id == 0)
                                                    @if(!$photo->isEmpty() && $photo[0]->b_listing_type==2)
                                                        <img src="{{ env('S3_IMG_PATH') }}images-rental/{{$agent->photo_url}}"  width="30%"/>
                                                    @else
                                                        <img src="{{ env('S3_IMG_PATH') }}images-rental/{{$agent->photo_url}}"  width="30%"/>
                                                    @endif
                                                @else
                                                    <img src="{{ env('S3_IMG_PATH_1') }}{{$agent->photo_url}}"  width="30%"/>
                                                @endif

                                                <div class="top-right">
                                                    <deletephoto inline-template v-bind:list="'{{$agent->id}}'" v-bind:num="1" :names="'images'">
                                                        <button type="button" v-on:click="deleteImage"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                    </deletephoto>
                                                </div>
                                            </div>
                                            <label class="label">Update photo</label>
                                            <input type="file" name="photo" class="select is-primary" value="{{ old('photo') }}" id="photo">
                                        @else
                                            <img id="blah" src="#" alt="" />
                                            <input type="file" name="photo" class="select is-primary" value="{{ old('photo') }}" id="photo">
                                        @endif
                                    </div>

                                    @if ($errors->has('photo'))
                                        <span class="help-block">
                                    <strong>{{ $errors->first('photo') }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="field">
                                    <label class="label">Company</label>
                                    <div class="control has-icons-left ">
                                        <input class="input" type="text" placeholder="Company" name="company" value="{{isset($agent) ? $agent->company : old('company')}}">
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
                                        <input class="input" type="text" placeholder="Office Phone" name="officePhone" value="{{isset($agent) ? $agent->office_phone : old('officePhone')}}">
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

                                <div class="field">
                                    <label class="label" for="">Description:</label>
                                    <textarea id="editor" name="description" class="textarea" rows="5" minlength="5" >{{isset($agent) ? $agent->description : old('description')}} </textarea>
                                    @if ($errors->has('description'))
                                        <span class="help-block">
                                                    <strong>{{ $errors->first('description') }}</strong>
                                                </span>
                                    @endif
                                </div>

                                <button type="submit" class="button is-primary">
                                    Save
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

@section('additional_scripts')
    <script>
        var editor = CKEDITOR.replace( 'editor' );
    </script>
@endsection