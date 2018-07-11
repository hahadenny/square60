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

                            <h2 class="title is-4 has-text-centered">Update Building</h2>

                            <div id="messageResponse" class="has-text-centered"></div>

                            <form method="POST" action="{{ route('updateBuilding') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <div class="column margin_col">

                                    <div class="fields content">
                                        <label class="label">Listing Type:</label>

                                        <div class="select">
                                            <select name="listingType" id="district">

                                                    @if (\Illuminate\Support\Facades\Input::old('type') == 1)
                                                        <option value="1" selected>Sale</option>
                                                        <option value="2" >Rental</option>
                                                    @elseif(isset($list) && $list->b_listing_type == 1)
                                                        <option value="1" selected>Sale</option>
                                                        <option value="2" >Rental</option>
                                                    @else
                                                        <option value="2" selected>Rental</option>
                                                        <option value="1">Sale</option>
                                                    @endif
                                            </select>
                                            @if ($errors->has('listingType'))
                                                <span class="help-block">
                                                        <strong>{{ $errors->first('listingType') }}</strong>
                                                    </span>
                                            @endif
                                        </div>

                                        <div class="columns is-variable form-content">
                                            <div class="column">
                                                <label class="label" for="">Building ID</label>
                                                <input placeholder="" type="text" name="buildingID" class="input" value="{{isset($list) ? $list->building_id : old('buildingID')}}" />
                                                @if ($errors->has('buildingID'))
                                                    <span class="help-block">
                                                            <strong>{{ $errors->first('buildingID') }}</strong>
                                                        </span>
                                                @endif

                                                <label class="label form-content" for="">Building Address</label>
                                                <input placeholder="" type="text" name="buildingAddress" class="input" value="{{isset($list) ? $list->building_address : old('buildingAddress')}}" />
                                                @if ($errors->has('buildingAddress'))
                                                    <span class="help-block">
                                                            <strong>{{ $errors->first('buildingAddress') }}</strong>
                                                        </span>
                                                @endif
                                            </div>

                                            <div class="column ">
                                                <label class="label" for="">Building Name</label>
                                                <input placeholder="" type="text" name="buildingName" class="input" value="{{isset($list) ? $list->building_name : old('buildingName')}}" />
                                                <input type="hidden" name="name_label" class="input" value="{{isset($list) ? $list->name_label : old('name_label')}}" />
                                                <input type="hidden" name="amazon_id" class="input" value="{{isset($list) ? $list->amazon_id : old('amazon_id')}}" />
                                                @if ($errors->has('buildingName'))
                                                    <span class="help-block">
                                                            <strong>{{ $errors->first('buildingName') }}</strong>
                                                        </span>
                                                @endif

                                                <label class="label form-content" for="">Building City</label>
                                                <input placeholder="" type="text" name="buildingCity" class="input" value="{{isset($list) ? $list->building_city : old('buildingCity')}}" />
                                                @if ($errors->has('buildingCity'))
                                                    <span class="help-block">
                                                            <strong>{{ $errors->first('buildingCity') }}</strong>
                                                        </span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="fields content">

                                        <div class="columns is-variable form-content">
                                            <div class="column">
                                                <label class="label" for="">Building State</label>
                                                <input placeholder="" type="text" name="buildingState" class="input" value="{{isset($list) ? $list->building_state : old('buildingState')}}" />
                                                @if ($errors->has('buildingState'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('buildingState') }}</strong>
                                                    </span>
                                                @endif
                                                @if(isset($list))
                                                    <input type="hidden" name="id" value="{{$list->id}}">
                                                @elseif(old('id')) <input type="hidden" name="id" value="{{old('id')}}">
                                                @endif

                                                <label class="label form-content" for="">Building Units</label>
                                                <input placeholder="" type="text" name="buildingUnits" class="input" value="{{isset($list) ? $list->building_units : old('buildingUnits')}}" />
                                                @if ($errors->has('buildingUnits'))
                                                    <span class="help-block">
                                                            <strong>{{ $errors->first('buildingUnits') }}</strong>
                                                        </span>
                                                @endif
                                            </div>

                                            <div class="column">
                                                <label class="label" for="">Building Zip</label>
                                                <input placeholder="" type="text" name="buildingZip" class="input" value="{{isset($list) ? $list->building_zip : old('buildingZip')}}" />
                                                @if ($errors->has('buildingZip'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('buildingZip') }}</strong>
                                                    </span>
                                                @endif

                                                <label class="label form-content" for="">Building Stories</label>
                                                <input placeholder="" type="text" name="buildingStories" class="input" value="{{isset($list) ? $list->building_stories : old('buildingStories')}}" />
                                                @if ($errors->has('buildingStories'))
                                                    <span class="help-block">
                                                            <strong>{{ $errors->first('buildingStories') }}</strong>
                                                        </span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="fields ">

                                        <div class="columns is-variable form-content">
                                            <div class="column">
                                                <label class="label" for="">Build Year</label>
                                                <input placeholder="" type="text" name="buildingBuildYear" class="input form-input-width" value="{{isset($list) ? $list->building_build_year : old('buildingBuildYear')}}" />
                                                @if ($errors->has('buildingBuildYear'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('buildingBuildYear') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="fix checkline">
                                            <label class="label">Building Amenities: </label>
                                            <ul class="filters_list">
                                                @foreach ( $filters as $k=>$filter)
                                                    @if($filter->parent_id == 0)
                                                        <li class="">
                                                            <label class="" for="{{$filter->filter_data_id}}">
                                                                <input name="buildingAmenities[]" class="checkbox" type="checkbox" id="{{$filter->filter_data_id}}" value="{{$filter->filter_data_id}}"
                                                                       @if(isset($list) && is_array($list->building_amenities) && in_array($filter->filter_data_id, $list->building_amenities))  checked
                                                                       @elseif(is_array(old('buildingAmenities')) && in_array($filter->filter_data_id, old('buildingAmenities')))
                                                                       checked
                                                                        @endif
                                                                >
                                                                {{$filter->value}}</label>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                        @if ($errors->has('buildingAmenities'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('buildingAmenities') }}</strong>
                                            </span>
                                        @endif

                                        <label class="label form-content" for="">Building Description:</label>
                                        <textarea id="editor" name="buildingDescription" class="textarea" rows="5" minlength="5"> {{isset($list) ? $list->building_description : \Illuminate\Support\Facades\Input::old('buildingDescription')}}</textarea>
                                        @if ($errors->has('buildingDescription'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('buildingDescription') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-content columns is-variable">
                                        <div class="column">
                                            <label class="label " for="">Building Images: </label>
                                            <input type="file" name="buildingImages[]" class="select is-primary" id="image" value="{{ old('buildingImages') }}" multiple >
                                            @if ($errors->has('buildingImages'))
                                                <span class="help-block">
                                               <strong>{{ $errors->first('buildingImages') }}</strong>
                                            </span>
                                            @endif
                                            @if(isset($list) && isset($list->path_for_building_images_on_s3))
                                                @foreach($list->path_for_building_images_on_s3 as $k=>$item)
                                                    @if($item !== '')
                                                        <div class="image-wrapper" id="images-{{$k}}">

                                                            @if($list->b_listing_type == 2 && $list->amazon_id == 0)
                                                                <img src="{{ env('S3_IMG_PATH') }}images-rental/{{$item}}" />
                                                            @elseif($list->b_listing_type == 2 && $list->amazon_id == 1)
                                                                <img src="{{ env('S3_IMG_PATH_1') }}{{$item}}" />
                                                            @elseif($list->b_listing_type == 1 && $list->amazon_id == 0)
                                                                <img src="{{ env('S3_IMG_PATH') }}sales-listing-images/{{$item}}" />
                                                            @elseif($list->b_listing_type == 1 && $list->amazon_id == 1)
                                                                <img src="{{ env('S3_IMG_PATH_1') }}{{$item}}" />
                                                            @endif

                                                            <div class="top-right">
                                                                <deletebuildimages inline-template v-bind:list="'{{$list->id}}'" v-bind:num="'{{$k}}'" :names="'images'">
                                                                    <button type="button" v-on:click="deleteImage"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                                </deletebuildimages>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>



                                    <button id="save" type="submit" class="button is-primary">
                                        save
                                    </button>
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