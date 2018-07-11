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

    <div>
        <div>
            @include('partial.header')  
            <section>

                <div class="main-content columns is-mobile is-centered name_label_content" style="margin-bottom:40px;">
                    <div class="content">                       
                            
                    <div id="page1" class="p_listings2" style="text-align:left;">
                        @if (session('status'))
                            <div class="alert alert-success" style="text-align:left;margin-bottom:25px;">
                                {!! session('status') !!}
                            </div>
                        @endif

                        <h4><b>Name Label on Building or Property:</b></h4>
                        <div class="columns">
                            <div class="column" style="padding-left:0px;">
                                <p>Place your name on property image or description.
                                    Enhance your exposure to clients. <br/> (Either you submit your version of image
                                    or description - Subject to site edit and approval)</p>
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column" style="padding-left:0px;">
                                <p>Each agent can submit up to 10 Building or property at Manhattan and another 10 in rest 4 Boros.</p>
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column" style="padding-left:0px;">
                                <p>Please click <a href="/terms">here</a> for term of use.</p>
                            </div>
                        </div>
                        
                        <div class="columns">
                            <div class="column" style="padding-left:0px;">
                            <h4 class="" style="margin-top:30px;"><b>Pricing:</b></h4>
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column" style="padding-left:0px;max-width:300px;">
                            <h5><b>Image:</b></h5>
                            <a href="{{route('nameLabelImage')}}" class="button is-success">Click to View Example</a><br><br>
                                <p>One Year ${{env('IMG_1Y')}}</p>
                                <p>Two Year ${{env('IMG_2Y')}}</p>
                                <p>Three Year ${{env('IMG_3Y')}}</p>
                            </div>
                            <div class="column" style="padding-left:0px;max-width:300px;">
                            <h5><b>Description:</b></h5>
                            <a href="{{route('nameLabelDescription')}}" class="button is-success">Click to View Example</a><br><br>
                                <p>One Year ${{env('DESC_1Y')}}</p>
                                <p>Two Year ${{env('DESC_2Y')}}</p>
                                <p>Three Years ${{env('DESC_3Y')}}</p>
                            </div>
                        </div>
                            
                        <div id="app">
                            @if(count($cur_buildings))
                            <h4 class="block" style="margin-top:50px;"><b>Purchased Building/Property</b></h4>
                            @endif

                            @foreach($cur_buildings as $cur_building)
                            <div class="block">
                                <div class="columns">
                                    <div class="column" style="padding-left:0px;">
                                        <label class="label" for="">Street: </label>
                                        <div style="margin-top:10px;"><a href="/building/{{$cur_building->building_name}}/{{$cur_building->building_city}}">{{$cur_building->building_address}}</a></div>
                                    </div>
                                    <div class="column" style="padding-left:0px;">
                                        <label class="label" for="">Boro: </label>
                                        <div style="margin-top:10px;">{{$cur_building->neighborhood}}</div>
                                    </div>
                                    <div class="column" style="padding-left:0px;">
                                        <label class="label" for="">City: </label>
                                        <div style="margin-top:10px;">{{$cur_building->building_city}}</div>
                                    </div>
                                    <div class="column" style="padding-left:0px;">
                                        <label class="label" for="">Zip: </label>
                                        <div style="margin-top:10px;">{{$cur_building->building_zip}}</div>
                                    </div>
                                </div>
                                <form name="form{{$cur_building->building_id}}" action="{{route('updateNameLabel')}}" method="post" enctype="multipart/form-data" style="width:100%;">
                                <div class="columns">                            
                                    {{csrf_field()}}
                                    <input type="hidden" name="building_id" value="{{$cur_building->building_id}}" />
                                    @if($cur_building->name_label)
                                    <div class="column" style="padding-left:0px;">
                                        <label class="label " for="">
                                            Image: 
                                            <span style="font-size:13px;font-weight:normal;">
                                                <br>[Ends At: {{Carbon\Carbon::parse($cur_building->img_ends_at)->format('Y-m-d')}}]
                                                @if ($cur_building->img_renew)
                                                <br>[Auto Renew On: {{Carbon\Carbon::parse($cur_building->img_ends_at)->format('Y-m-d')}}]
                                                @endif 
                                            </span>
                                        </label>

                                        @if (isset($cur_building->name_label_image_path)) 
                                        <div class="columns">
                                            @foreach($cur_building->name_label_image_path as $k => $img_path) 
                                            <div class="column" style="padding-left:0px;max-width:150px;position:relative;margin-right:10px;text-align:center;">
                                                <img id="cur-img-{{$cur_building->building_id}}-{{$k}}" src="{{$img_path}}" style="max-width:150px;max-height:150px;" />
                                                <div id="cur-img-{{$cur_building->building_id}}-{{$k}}_t" class="top-right" style="top:8px;right:2px;">
                                                    <deletebuildimg inline-template v-bind:build="'{{$cur_building->building_id}}'" v-bind:num="'{{$k}}'" v-bind:names="'cur-img'" v-bind:img_name="'{{explode('/', $img_path)[count(explode('/', $img_path))-1]}}'">
                                                        <button type="button" v-on:click="deleteImage"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                    </deletebuildimg>
                                                </div>
                                            </div>
                                            @if (($k+1) % 3 == 0)                                        
                                        </div>
                                        <div class="columns">
                                            @endif
                                            @endforeach
                                        </div>
                                        @endif 
                                        <input type="file" name="curnamelabel[]" class="select is-primary image" id="image" value="" accept="image/*" onchange="readURL(this, '{{$cur_building->building_id}}');" multiple />
                                    </div>
                                    @endif
                                    @if($cur_building->described)
                                    <div class="column cur_desc" style="padding-left:0px;">
                                        <label class="label " for="">
                                            Description: 
                                            <span style="font-size:13px;font-weight:normal;">
                                                <br>[Ends At: {{Carbon\Carbon::parse($cur_building->desc_ends_at)->format('Y-m-d')}}]
                                                @if ($cur_building->desc_renew)
                                                <br>[Auto Renew On: {{Carbon\Carbon::parse($cur_building->desc_ends_at)->format('Y-m-d')}}]
                                                @endif 
                                            </span>
                                        </label>
                                        <div class="curdescription">
                                            <textarea class="cur_editor" id="cur-editor-{{$cur_building->building_id}}" name="curdescription" class="textarea description" rows="5" minlength="5" style="height:100px;">{{$cur_building->building_description}}</textarea>
                                        </div>
                                    </div>                                
                                    @endif
                                </div>
                                <button class="button is-primary mainbgc" onclick="document.form{{$cur_building->building_id}}.submit();">Update</button>
                                </form>
                            </div>
                            @endforeach
                        </div>

                        <h4 class="block" style="margin-top:50px;"><b>Select New Building/Property</b></h4>

                        <form action="{{route('saveNameLabel')}}" method="post" id="payment-form" enctype="multipart/form-data">
                                {{ csrf_field() }}

                        <div id="inputBlock" class="block">
                            <div class="columns">
                                <div class="column" style="padding-left:0px;">
                                    <label class="label" for="">Street: </label>

                                    <select id="address-1" class="input address" name="namelabel[1][address]" style="max-width:230px;" autocomplete=off>
                                        <option></option>
                                        @foreach($buildings as $building)
                                            <option value="{{ $building->building_id }}">{{$building->building_address}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="column" style="padding-left:0px;">
                                    <label class="label" for="">Boro: </label>
                                    <input id="boro" placeholder="" type="text" class="boro input" value=""  name="namelabel[1][boro]" style="max-width:230px;" />
                                </div>
                                <div class="column" style="padding-left:0px;">
                                    <label class="label" for="">City: </label>
                                    <input id="city" placeholder="" type="text" class="city input" value=""  name="namelabel[1][city]" style="max-width:230px;" />
                                </div>
                                <div class="column" style="padding-left:0px;">
                                    <label class="label" for="">Zip: </label>
                                    <input id="zip" placeholder="" type="text" class="zip input" value=""  name="namelabel[1][zip]" style="max-width:230px;" />
                                </div>
                            </div>
                            <div class="columns">
                                <div id="img-column-1" class="column" style="padding-left:0px;">
                                    <label class="label " for="">Image: </label>
                                    <input type="file" name="namelabel[1][image][]" class="select is-primary image" id="image" accept="image/*" value="" multiple/>
            
                                    <br><br><label class="checkbox">
                                        <input id="month" type="radio" class="priceImage"  name="namelabel[1][imagePrice]" value="1y">
                                        One Year.
                                    </label><br>
                                    <label class="checkbox">
                                        <input id="year" type="radio" class="priceImage" name="namelabel[1][imagePrice]" value="2y">
                                        Two Year.
                                    </label><br>
                                    <label class="checkbox">
                                        <input id="year" type="radio" class="priceImage" name="namelabel[1][imagePrice]" value="3y">
                                        Three Year.
                                    </label><br>
                                </div>
                                <div id="desc-column-1" class="column cur_desc" style="padding-left:0px;">
                                    <label class="label " for="">Description: </label>
                                    <button class="showDesc button is-primary mainbgc">Add</button>
                                    <div class="description" style="display: none">
                                        <textarea id="editor" name="namelabel[1][description]" class="textarea description" rows="5" minlength="5" style="height:0px;"></textarea>
                                    </div>
                                    <br><br><label class="checkbox">
                                        <input id="month" type="radio" class="price" name="namelabel[1][descriptionPrice]" value="1y">
                                        One Year.
                                    </label><br>
                                    <label class="checkbox">
                                        <input id="year" type="radio" class="price" name="namelabel[1][descriptionPrice]" value="2y">
                                        Two Year.
                                    </label><br>
                                    <label class="checkbox">
                                        <input id="year" type="radio" class="price" name="namelabel[1][descriptionPrice]" value="3y">
                                        Three Year.
                                    </label><br>
                                </div>
                            </div>
                        </div>
                        
                        <div id="inputClone"></div>
                        
                        <div style="margin-top:30px;">
                            <div>
                                <a id="more" class="button is-primary mainbgc">Add More</a>
                                <br><br><a id="next" class="button is-success" onclick="nextPage()">Next</a>
                            </div>
                        </div>
                    </div>


                            <div id="page2" class="p_listings2" style="display: none;">
                                <div class="container">
                                    <p>You have selected <span id="numProperties"></span> properties.</p>
                                    <p>Payment plans are as followings:</p>
                                    <div id="info"></div>


                                    <h5><b>Total: <span id="total"></span></b></h5>

                                    <div class="form-row">
                                        <label for="card-element">
                                            Credit/Debit Card:
                                        </label>

                                        <div id="card-element" class="stripe_paym" style="margin-top:30px;">
                                        </div>

                                        <label for="customer-name">
                                            {{--Name--}}
                                        </label>
                                        <div id="customer-name"></div>

                                        <div id="card-errors" role="alert" style="margin-top:10px;color:red"></div>
                                    </div>
                                    <div class="container" style="margin-top:30px;padding-left:0px;">
                                        Auto Renew&nbsp;<label class="checkbox">
                                            <input type="checkbox" name="renew" value="1">
                                            Yes.
                                        </label>
                                    </div>
                                    <br><br>
                                    <button class="button is-primary mainbgc">Check Out</button>
                                    <br><br>
                                    <a id="back" class="button is-link mainbgc" onclick="previousPage()">Previous</a><br><br>
                                </div>
                            </div>

                            </form>

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
    <script src="https://js.stripe.com/v3/"></script>
    <script>       

        var buildings = {!! $buildings !!};

        var address = $('#address-1');

        address.select2({
            selectOnBlur: true,
        });

        address.on('change', function(e) {
            var buildingId = $(this).val(); 

            //check if this building is selected already
            var already_selected = false;
            $('.address').each(function(){
                if ($(this).attr('id') != 'address-1' && $(this).val() == buildingId) {
                    already_selected = true;
                    swal('You already selected this building.');
                    return false;
                }
            });

            if (already_selected) {
                $('#select2-address-1-container').html('');
                $(this).val('');
                return false;
            }

            for (var key in buildings) {
                if (buildings.hasOwnProperty(key)) {
                    if(buildings[key]['building_id'] == buildingId){
                        $('input[id=boro]').val(buildings[key]['neighborhood']);
                        $('input[id=city]').val(buildings[key]['building_city']);
                        $('input[id=zip]').val(buildings[key]['building_zip']);
                        
                        if (buildings[key]['described'] == 1) {
                            $('input[name="namelabel[1][descriptionPrice]"]:checked').prop('checked',false);
                            $('#desc-column-1').hide();
                        }
                        else {
                            $('#desc-column-1').show();
                        }

                        if (buildings[key]['name_label'] == 1) {
                            $('input[name="namelabel[1][imagePrice]"]:checked').prop('checked',false);
                            $('#img-column-1').hide();
                        }
                        else {
                            $('#img-column-1').show();
                        }
                    }
                }
            }

        });

        var editor = CKEDITOR.replace('editor');

        $('.showDesc').on('click', function(){
            $('.description').show();
            $(this).hide();
        });

        //for purchased description
        $('.cur_editor').each(function(){    
            editor = CKEDITOR.replace($(this).attr('id'));
        });

        var i = 1;

        $('#more').click(function() {
            if (!$('#inputBlock input[type="radio"]').is(':checked')){
                swal("Please select at least one period for images or description!");
                return false;
            }

            if (($(".clone-"+i)).length){
                if(!$('.clone-'+i+' input[type="radio"]').is(':checked')) {
                    swal("Please select at least one period for images or description!");
                    return false;
                }                
            }

            if (($("#address-"+i)).length){
                if(!$('#address-'+i).val()) {
                    swal("Please select a building/property address!");
                    return false;
                }
            }

            if (($("#select-"+i)).length){
                if(!$('#select-'+i).val()) {
                    swal("Please select a building/property address!");
                    return false;
                }
            }

            i++;

            $('.block:last').after('<div class="block clone-'+i+'" style="margin-top:15px;"><div class="columns">\n' +
                '                                <div class="column" style="padding-left:0px;">\n' +
                '                                    <label class="label" for="">Street: </label>\n' +
                    '<select id="select-'+i+'" class="input address" name="namelabel['+i+'][address]" style="max-width:230px;">+\n' +
                '                                        <option></option>\n' +
                '                                        @foreach($buildings as $building)\n' +
                '                                            <option value="{{ $building->building_id }}">{{$building->building_address}}</option>\n' +
                '                                        @endforeach\n' +
                '                                    </select>'+
                '                                </div>\n' +
                '                                <div class="column" style="padding-left:0px;">\n' +
                '                                    <label class="label" for="">Boro: </label>\n' +
                '                                    <input placeholder="" id="boro-'+i+'" type="text" class="input boro" value=""  name="namelabel['+i+'][boro]" style="max-width:230px;" />\n' +
                '                                </div>\n' +
                '                                <div class="column" style="padding-left:0px;">\n' +
                '                                    <label class="label" for="">City: </label>\n' +
                '                                    <input placeholder="" id="city-'+i+'" type="text" class="input city" value="NY"  name="namelabel['+i+'][city]" style="max-width:230px;" />\n' +
                '                                </div>\n' +
                '                                <div class="column" style="padding-left:0px;">\n' +
                '                                    <label class="label" for="">Zip: </label>\n' +
                '                                    <input placeholder="" id="zip-'+i+'" type="text" class="input zip" value=""  name="namelabel['+i+'][zip]" style="max-width:230px;" />\n' +
                '                                </div>\n' +
                '                            </div>\n' +
                '                            <div class="columns">\n' +
                '                                <div id="img-column-'+i+'" class="column" style="padding-left:0px;">\n' +
                '                                    <label class="label " for="">Image: </label>\n' +
                '                                    <input type="file" name="namelabel['+i+'][image][]" class="select is-primary image" id="image" accept="image/*" value="" multiple />\n' +
                '                                    <br><br><label class="checkbox">\n' +
                '                                        <input id="month" type="radio" class="priceImage" name="namelabel['+i+'][imagePrice]" value="1y">\n' +
                '                                        One Year.\n' +
                '                                    </label><br>\n' +
                '                                    <label class="checkbox">\n' +
                '                                        <input id="year" type="radio" class="priceImage" name="namelabel['+i+'][imagePrice]" value="2y">\n' +
                '                                        Two Year.\n' +
                '                                    </label><br>\n' +
                '                                    <label class="checkbox">\n' +
                '                                        <input id="year" type="radio" class="priceImage" name="namelabel['+i+'][imagePrice]" value="3y">\n' +
                '                                        Three Year.\n' +
                '                                    </label><br>\n' +
                '                                </div>\n' +
                '                                <div id="desc-column-'+i+'" class="column cur_desc" style="padding-left:0px;">\n' +
                '                                    <label class="label " for="">Description: </label>\n' +
                '                                   <button class="showDesc-'+i+' button is-primary mainbgc">Add</button> ' +
                '                                   <div class="description-'+i+'" style="display: none">\n' +
                '                                        <textarea id="editor-'+i+'" name="namelabel['+i+'][description]" class="textarea description" rows="5" minlength="5" style="height:0px;"></textarea>\n' +
                '                                    </div>'+
                '                                    <br><br><label class="checkbox">\n' +
                '                                        <input id="month" type="radio" class="price" name="namelabel['+i+'][descriptionPrice]" value="1y">\n' +
                '                                        One Year.\n' +
                '                                    </label><br>\n' +
                '                                    <label class="checkbox">\n' +
                '                                        <input id="year" type="radio" class="price" name="namelabel['+i+'][descriptionPrice]" value="2y">\n' +
                '                                        Two Year.\n' +
                '                                    </label><br>\n' +
                '                                    <label class="checkbox">\n' +
                '                                        <input id="year" type="radio" class="price" name="namelabel['+i+'][descriptionPrice]" value="3y">\n' +
                '                                        Three Year.\n' +
                '                                    </label><br>\n' +
                '                                </div></div><span class="remove button is-danger">Remove Option</span>');

            $('#select-'+i).select2({
                tags: true,
                selectOnBlur: true,
            });

            $('.showDesc-'+i).on('click', function(){
                $('.description-'+i).show();
                editor = CKEDITOR.replace( 'editor-'+i );
                $(this).hide();
            });

            $('#select-'+i).on('change', function(e) {
                var buildingId = $(this).val();

                //check if this building is selected already
                var already_selected = false;
                $('.address').each(function(){
                    if ($(this).attr('id') != 'select-'+i && $(this).val() == buildingId) {
                        already_selected = true;
                        swal('You already selected this building.');
                        return false;
                    }
                });

                if (already_selected) {
                    $('#select2-select-'+i+'-container').html('');
                    $(this).val('');
                    return false;
                }


                for (var k in buildings) {
                    if ( buildings.hasOwnProperty(k)) {

                        if(buildings[k]['building_id'] == buildingId){

                            $('input[id=boro-'+i+']').val(buildings[k]['neighborhood']);
                            $('input[id=city-'+i+']').val(buildings[k]['building_city']);
                            $('input[id=zip-'+i+']').val(buildings[k]['building_zip']);

                            if (buildings[k]['described'] == 1) {
                                $('input[name="namelabel['+i+'][descriptionPrice]"]:checked').prop('checked',false);
                                $('#desc-column-'+i).hide();
                            }
                            else {
                                $('#desc-column-'+i).show();
                            }

                            if (buildings[k]['name_label'] == 1) {
                                $('input[name="namelabel['+i+'][imagePrice]"]:checked').prop('checked',false);
                                $('#img-column-'+i).hide();
                            }
                            else {
                                $('#img-column-'+i).show();
                            }
                        }
                    }
                }

                var selectBuildings = $("input[name=boro]");
                var count = 0;
                for (var key in selectBuildings) {
                    if ( selectBuildings.hasOwnProperty(key)) {

                        if(selectBuildings[key]['value'] === "Manhattan"){
                            count++;
                        }
                    }
                }

                if(count > 10){
                    swal('You can add only 10 buildings from Manhattan, please change boro' );
                    $('select[id=select-'+i+']').val(1).trigger('change.select2');
                    $('input[id=boro-'+i+']').val('');
                    $('input[id=city-'+i+']').val('');
                    $('input[id=zip-'+i+']').val('');
                }
            });

            if(i > 20){
                $('#more').attr('disabled','disabled');
            }

        });

        $(document).on('click','.remove',function() {
            $(this).parent('div').remove();
        });

        var arr = [];

        function nextPage(){

            var all_filled = true;

            $('.address').each(function(){
                if (!$(this).val()){
                    all_filled = false;
                    swal("Please select a building/property address!");
                    return false;
                }
            });          
            
            if (!all_filled)
                return false;

            if (!$('#inputBlock input[type="radio"]').is(':checked')){
                all_filled = false;
                swal("Please select at least one period for images or description!");
                return false;
            }

            if (!all_filled)
                return false;

            for (j=0; j<i; j++) {
                if (($(".clone-"+i)).length){
                    if(!$('.clone-'+i+' input[type="radio"]').is(':checked')) {
                        all_filled = false;
                        swal("Please select at least one period for images or description!");
                        return false;
                    }                
                }
            }

            if (!all_filled)
                return false;

            var count = 0;

            $.each($('.block'), function(index, value) {
                if ($(this).find('select.address').length) {
                    arr.push({
                        id: $(this).find('select.address').val(),
                        address: $(this).find('select.address option:selected').text(),
                        boro: $(this).find('input.boro').val(),
                        city: $(this).find('input.city').val(),
                        zip: $(this).find('input.zip').val(),
                        image: $(this).find('input.image').val(),
                        description: $(this).find('textarea.description').val(),
                        imagePrice: $(this).find('.priceImage:radio:checked').val(),
                        descriptionPrice: $(this).find('.price:radio:checked').val(),
                    });
                    count++;
                }
            });

            $('#numProperties').html(count);

            $.each(arr, function(index, value) {
                $('#info').append( "<p><b>"+value.address + " | "+ value.city + " | "+value.zip +"</b><br>"
                                    +"Image plan: <b>" +planImage(value.imagePrice) +"</b><br>"
                                    +"Description plan: <b>" +planDescription(value.descriptionPrice) +"</b><br>"+"</p><hr>");
            });

            function planImage(price){
                if(price == '1y'){
                    return "One Year";
                }else if(price == '2y'){
                    return "Two Year";
                }else if(price == '3y'){
                    return "Three Year";
                }else if(price == undefined){
                    return "N/A";
                }
            }

            function planDescription(price){
                if(price == '1y'){
                    return "One Year";
                }else if(price == '2y'){
                    return "Two Year";
                }else if(price == '3y'){
                    return "Three Year";
                }else if(price == undefined){
                    return "N/A";
                }
            }

            var all = $(".price:checked").map(function() {
                if(this.value == '1y'){
                    return "{{env('DESC_1Y')}}";
                }else if(this.value == '2y'){
                    return "{{env('DESC_2Y')}}";
                }else if(this.value == '3y'){
                    return "{{env('DESC_3Y')}}";
                }
            }).get();


            var totalDescription = eval(all.join('+'));

            var allImage = $(".priceImage:checked").map(function() {
                if(this.value == '1y'){
                    return "{{env('IMG_1Y')}}";
                }else if(this.value == '2y'){
                    return "{{env('IMG_2Y')}}";
                }else if(this.value == '3y'){
                    return "{{env('IMG_3Y')}}";
                }
            }).get();

            var totalImage = eval(allImage.join('+'));

            if(totalDescription == null){
                totalDescription = 0;
            }

            if(totalImage == null){
                totalImage = 0;
            }

            var total = totalDescription+totalImage;

            $('#total').html('$'+total);
            $("#page1").hide();
            $("#page2").show();

            window.scrollTo(0,0);
        }

        function previousPage(){
            arr = [];
            $('#info').empty();
            $("#page2").hide();
            $("#page1").show();
        }

        // Create a Stripe client.
        var stripe = Stripe('{{env('STRIPE_KEY')}}');

        var elements = stripe.elements();

        var style = {
            base: {
                color: '#32325d',
                lineHeight: '18px',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {style: style});

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        // Handle real-time validation errors from the card Element.
        card.addEventListener('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Handle form submission.
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    // Inform the user if there was an error.
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    // Send the token to your server.
                    stripeTokenHandler(result.token);
                }
            });
        });

        function stripeTokenHandler(token) {
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            // Submit the form
            form.submit();
        }

        function readURL(input, building_id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#cur-img-'+building_id)
                        .attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection