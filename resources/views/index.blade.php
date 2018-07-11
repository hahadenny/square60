@extends('layouts.app')

@section('header')
<header class="hidden" xmlns="http://www.w3.org/1999/html">
    @include('layouts.header')
</header>
@endsection

@section('content')
<div id="app">
  <div>
    <div class="container">
        <div id="logo" class="text-c">
            <img src="{{ asset('images/square_60.png') }}" alt="" width="400px">
        </div>
        <div class="is-clearfix search-wrap">
            @include('partial.search')
        </div>
        <div class="tabs is-toggle is-centered n">
            <ul>
                <li class="nem">
                    <a class="nem2" data-type="1">
                        SALES
                    </a>
                </li>
                <li class="nem">
                    <a class="nem2" data-type="2">
                        RENTALS
                    </a>
                </li>
            </ul>
        </div>
    </div>
<form id="mainFrom" method="post" action="/search">

    <div class="box col_details column is-10 is-offset-1 is-mobile" style="display:none;">
        <div class="">

            <div class="tabs is-toggle is-centered">
                <ul id="cities" class="is-mobile mar_10">
                    @foreach ( $districts as $district)
                    <li><a id="district_{{$district->filter_data_id}}" class="s_city" onclick="showSubDistricts({{$district->filter_data_id}})" value="{{$district->filter_data_id}}" id="{{$district->filter_data_id}}">{{$district->value}}</a></li>
                    @endforeach
                </ul>
            </div>

            <input id="real_estate_type" type="hidden" value="0" name="estate_type">
            <div class="columns is-gapless">
                <div class="column is-8 col_bor_1 ">
                    <div class="">
                       <!-- <h4 class="title is-5 ">SELECT LOCATION</h4> -->
                    </div>


                    <div class="column loc-arr">
                        @foreach ( $districts as $district)
                            <div class="first-neigh">
                                <label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="all_{{$district->filter_data_id}}"><input id="all_{{$district->filter_data_id}}" onchange="checkChildBoro({{$district->filter_data_id}})" type="checkbox"><span class="all-neigh">All {{$district->value }}</span></label>
                            </div>
                            @if($district->value == 'Manhattan')
                                <div class="columns is-gapless checkline">
                                    <div class="column loc-arr">
                                            @foreach ($district->subdistritcs as $subdistritc)
                                                @foreach ($subdistritc as $k=>$sub)

                                                    @if($sub->district_id == 1)
                                                        @if($k == 0)
                                                        <div>
                                                            <strong>
                                                                <label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}">
                                                                    <input type='checkbox' id="{{$sub->filter_data_id}}" data-id="{{$sub->filter_data_id}}" class="parent" onchange="checkChild({{$sub->district_id}}, {{$sub->filter_data_id}})" value="{{$sub->filter_data_id}}"  name='districts[]'>{{$sub->value}}</label>
                                                            </strong>
                                                        </div>
                                                        @else
                                                             <div>
                                                               <label class="subdistricts sub-child parent-{{$sub->district_id}}" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}">
                                                                   <input type='checkbox' id="{{$sub->filter_data_id}}" data-parent=10 class="parent-{{$sub->district_id}}" value="{{$sub->filter_data_id}}" name='districts[]'>{{$sub->value}}</label>
                                                             </div>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endforeach
                                    </div>
                                    <div class="column loc-arr2">

                                            @foreach ($district->subdistritcs as $subdistritc)
                                                @foreach ($subdistritc as $k=>$sub)
                                                    @if($sub->district_id == 2)
                                                        @if($k == 0)
                                                            <div>
                                                                <strong>
                                                                    <label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}">
                                                                        <input type='checkbox' id="{{$sub->filter_data_id}}" class="parent" onchange="checkChild({{$sub->district_id}}, {{$sub->filter_data_id}})" value="{{$sub->filter_data_id}}" name='districts[]'>{{$sub->value}}</label>
                                                                </strong>
                                                            </div>
                                                        @else
                                                            <div>
                                                                <label class="subdistricts sub-child parent-{{$sub->district_id}}" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}">
                                                                    <input type='checkbox' id="{{$sub->filter_data_id}}" class="parent-{{$sub->district_id}}" value="{{$sub->filter_data_id}}" name='districts[]'>{{$sub->value}}</label>
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endforeach

                                    </div>
                                    <div class="column loc-arr3">

                                            @foreach ($district->subdistritcs as $subdistritc)
                                                @foreach ($subdistritc as $k=>$sub)
                                                    @if($sub->district_id == 3)
                                                            @if($k == 0)
                                                                <div>
                                                                    <strong>
                                                                        <label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}">
                                                                            <input type='checkbox' id="{{$sub->filter_data_id}}" class="parent" onchange="checkChild({{$sub->district_id}}, {{$sub->filter_data_id}})" value="{{$sub->filter_data_id}}" name='districts[]'>{{$sub->value}}</label>
                                                                    </strong>
                                                                </div>
                                                            @else
                                                                <div>
                                                                    <label class="subdistricts sub-child parent-{{$sub->district_id}}" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}">
                                                                        <input type='checkbox' id="{{$sub->filter_data_id}}" class="parent-{{$sub->district_id}}" value="{{$sub->filter_data_id}}" name='districts[]'>{{$sub->value}}</label>
                                                                </div>
                                                            @endif
                                                    @endif
                                                        @if($sub->district_id == 4)
                                                            @if($k == 0)
                                                                <div>
                                                                    <strong>
                                                                        <label class=" subdistricts" data-parent="{{$district->filter_data_id}}"   for="{{$sub->filter_data_id}}">
                                                                            <input type='checkbox' id="{{$sub->filter_data_id}}" value="{{$sub->filter_data_id}}" class="parent" onchange="checkChild({{$sub->district_id}}, {{$sub->filter_data_id}})" name='districts[]'>{{$sub->value}}</label>
                                                                    </strong>
                                                                </div>
                                                            @else
                                                                <div>
                                                                    <label class="subdistricts sub-child parent-{{$sub->district_id}}" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}">
                                                                        <input type='checkbox' id="{{$sub->filter_data_id}}" class="parent-{{$sub->district_id}}" value="{{$sub->filter_data_id}}" name='districts[]'>{{$sub->value}}</label>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @if($sub->district_id == 5)
                                                        @if($k == 0)
                                                            <div>
                                                                <strong>
                                                                    <label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}">
                                                                        <input type='checkbox' id="{{$sub->filter_data_id}}" class="parent" onchange="checkChild({{$sub->district_id}}, {{$sub->filter_data_id}})" value="{{$sub->filter_data_id}}" name='districts[]'>{{$sub->value}}</label>
                                                                </strong>
                                                            </div>
                                                        @else
                                                            <div>
                                                                <label class="subdistricts sub-child parent-{{$sub->district_id}}" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}">
                                                                    <input type='checkbox' id="{{$sub->filter_data_id}}" class="parent-{{$sub->district_id}}" value="{{$sub->filter_data_id}}" name='districts[]'>{{$sub->value}}</label>
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endforeach

                                    </div>
                                </div>
                            @else
                                <div class="columns is-gapless checkline">
                                    <div class="column loc-arr">
                                            @foreach ( $district->subdistritcs as $sub)
                                                @if($sub->left)

                                                @if ($sub->mainboro && isset($subBorougths[$sub->district_id]))
                                                <div >
                                                    <strong>
                                                        <label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}">
                                                            <input type='checkbox' id="{{$sub->filter_data_id}}" class="parent" onchange="checkChild({{$sub->district_id}}, {{$sub->filter_data_id}})" value="{{$sub->filter_data_id}}" name='districts[]'>{{$sub->value}}</label>
                                                    </strong>
                                                </div>
                                                @foreach($subBorougths[$sub->district_id] as $subBoro)
                                                    <div>
                                                        <label class="subdistricts sub-child parent-{{$subBoro->district_id}}" data-parent="{{$district->filter_data_id}}"   for="{{$subBoro->filter_data_id}}"><input class="parent-{{$subBoro->district_id}}" type='checkbox' id="{{$subBoro->filter_data_id}}" value="{{$subBoro->filter_data_id}}" name='districts[]'>{{$subBoro->value}}</label>
                                                    </div>
                                                @endforeach
                                                @else
                                                    <div >
                                                        <label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}"><input type='checkbox' id="{{$sub->filter_data_id}}" value="{{$sub->filter_data_id}}" name='districts[]'>{{$sub->value}}</label>
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach

                                    </div>
                                    <div class="column loc-arr2">

                                            @foreach ( $district->subdistritcs as $sub)
                                                @if($sub->center)
                                                @if ($sub->mainboro  && isset($subBorougths[$sub->district_id]))
                                                        <div >
                                                        <strong>
                                                            <label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}">
                                                                <input type='checkbox' id="{{$sub->filter_data_id}}" class="parent" onchange="checkChild({{$sub->district_id}}, {{$sub->filter_data_id}})" value="{{$sub->filter_data_id}}" name='districts[]'>{{$sub->value}}</label>
                                                        </strong>
                                                        </div>
                                                        @foreach($subBorougths[$sub->district_id] as $subBoro)
                                                            <div>
                                                                <label class="subdistricts sub-child parent-{{$subBoro->district_id}}" data-parent="{{$district->filter_data_id}}"   for="{{$subBoro->filter_data_id}}"><input class="parent-{{$subBoro->district_id}}" type='checkbox' id="{{$subBoro->filter_data_id}}" value="{{$subBoro->filter_data_id}}" name='districts[]'>{{$subBoro->value}}</label>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                <div >
                                                    <label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}"><input type='checkbox' id="{{$sub->filter_data_id}}" value="{{$sub->filter_data_id}}" name='districts[]'>{{$sub->value}}</label>
                                                </div>

                                            @endif
                                            @endif
                                            @endforeach

                                    </div>
                                    <div class="column loc-arr3">
                                     @foreach ( $district->subdistritcs as $sub)
                                                @if($sub->rigth)
                                                @if ($sub->mainboro && isset($subBorougths[$sub->district_id]))
                                                <div >
                                                    <strong>
                                                        <label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}">
                                                            <input type='checkbox' id="{{$sub->filter_data_id}}" class="parent" onchange="checkChild({{$sub->district_id}}, {{$sub->filter_data_id}})" value="{{$sub->filter_data_id}}" name='districts[]'>{{$sub->value}}</label>
                                                    </strong>
                                                </div>
                                                @foreach($subBorougths[$sub->district_id] as $subBoro)
                                                    <div>
                                                        <label class="subdistricts sub-child parent-{{$subBoro->district_id}}" data-parent="{{$district->filter_data_id}}"   for="{{$subBoro->filter_data_id}}"><input class="parent-{{$subBoro->district_id}}" type='checkbox' id="{{$subBoro->filter_data_id}}" value="{{$subBoro->filter_data_id}}" name='districts[]'>{{$subBoro->value}}</label>
                                                    </div>
                                                @endforeach
                                                @else
                                                    <div >
                                                        <label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}"><input type='checkbox' id="{{$sub->filter_data_id}}" value="{{$sub->filter_data_id}}" name='districts[]'>{{$sub->value}}</label>
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach

                                    </div>
                                </div>
                            @endif

                        @endforeach
                            <br />
                    </div>

                </div>
                <div class="column col-right">
                    <div class="content">
                        <h4 class="title typeh is-5 ">TYPE</h4></div>
                    <div class="columns is-gapless type-block">
                        @foreach ( $types as $k=>$type)

                            @if($k%2)
                                <label for="{{$type->filter_data_id}}"><input name="types[]" type="checkbox" id="{{$type->filter_data_id}}" value="{{$type->filter_data_id}}" >{{$type->value}}</label>
                                </div>
                                @else
                                <div class="column">
                                    <label for="{{$type->filter_data_id}}"><input name="types[]" type="checkbox" id="{{$type->filter_data_id}}" value="{{$type->filter_data_id}}" >{{$type->value}}</label><br>
                            @endif
                            @endforeach
                            <label id="extraTypes" for="extraCheckbox"></label>

                    </div>

                    <div class="fields content">

                        <label class="label">Bed: </label>

                        <div class="select">
                            <select name="beds[]" id="bedFor">
                                <option value="0" selected="selected">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select></div>
                        <label for="">to</label>
                        <div class="select">
                            <select name="beds[]" id="bedTO">
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10" selected="selected">10</option>
                            </select></div>

                    </div>
                    <div class="fields content">

                        <label class="label">Bath: </label>
                        <div class="select">
                            <select name="baths[]" id="bathFor">
                                <option value="0" selected="selected">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>

                            </select></div>
                        <label for="">to</label>
                        <div class="select">
                            <select name="baths[]" id="bathTo">
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10" selected="selected">10</option>


                            </select></div>
                    </div>
                    <div class="fields content">
                        <label id="price" class="label" for="">Rent: </label>
                        <input id="priceFor" placeholder="min" type="text" class="select is-primary" name="price[]" />
                        <label>to</label>
                        <input id="priceTo" placeholder="max" type="text" class="select is-primary" name="price[]" />
                    </div>

                    <div class="fix checkline">
                       <label class="label">Filters: </label>
                       
                        <div>
                               <ul class="filters_list">
                                   <li class=" filters ">
                                     @foreach ( $filters as $k=>$filter)
                                        <li class=" filters "><label for="{{$filter->filter_data_id}}"><input name="filters[]" type="checkbox" id="{{$filter->filter_data_id}}" value="{{$filter->filter_data_id}}" >{{$filter->value}}</label></li>
                                        @endforeach
                                </ul>
                        </div>
<div>
                        <input type="button" class="button" value="More Filters" id="more-filters"/>
                        <button class="button is-info" id="search_main">Search</button>
</div>
                    </div>

                </div>


            </div>

        </div>

    </div>

    {{csrf_field()}}
</form>

    <section id="grid">
        <div class="column is-half is-offset-one-quarter text-c">
            <!-- <h2 class="title is-3">FEATURES</h2> -->
            <div class="columns is-gapless mar-2">
                <div class="column">
                    <div class="image-caption">
                        <ul class="level maxzi is-mobile" id="listing-ad-ul-type">
                            <li id="listing-ad-bed">3 beds</li>
                            <li id="listing-ad-bath">1 bath</li>
                        </ul>
                        <span id="price-toline" class="title maxzi is-5">$935,353,00</span>
                        <span id="neigh-caption">Hell's Kitchen</span>
                        <img src="https://photos.zillowstatic.com/p_h/ISe877d46n9obb0000000000.jpg" id="over-cap" alt=""></div>
                    <div class="image-caption">
                        <ul class="level maxzi is-mobile" id="listing-ad-ul-type">
                            <li id="listing-ad-bed">2 beds</li>
                            <li id="listing-ad-bath">2 baths</li>
                        </ul>
                        <span id="price-toline" class="title maxzi is-5">$1003,353,00</span>
                        <span id="neigh-caption">West Chelsea</span>
                        <img src="https://photos.zillowstatic.com/p_h/IS2fg5fre25wgq0000000000.jpg" alt=""></div>
                </div>
                <div class="column">
                    <div class="image-caption">
                        <ul class="level maxzi is-mobile" id="listing-ad-ul-type">
                            <li id="listing-ad-bed">7 beds</li>
                            <li id="listing-ad-bath">4 baths</li>
                        </ul>
                        <span id="price-toline" class="title maxzi is-5">$537,353,00</span>
                        <span id="neigh-caption">Lenox Hill</span>
                        <img src="https://photos.zillowstatic.com/p_h/ISqd35dopprchq0000000000.jpg" alt="">
                    </div>
                    <div class="image-caption">

                        <div class="image-caption">
                            <ul class="level maxzi is-mobile" id="listing-ad-ul-type">
                                <li id="listing-ad-bed">4 beds</li>
                                <li id="listing-ad-bath">2 baths</li>
                            </ul>
                            <span id="price-toline" class="title maxzi is-5">$952,432,00</span>
                            <span id="neigh-caption">Tribeca</span>
                            <img src="https://photos.zillowstatic.com/p_h/IS6m05zq4gyfvq0000000000.jpg" alt="">

                        </div>
                    </div>
                </div>
            </div>
    </section>
  </div>
</div>
@endsection

@section('footer')
    @include('layouts.footerMain')
@endsection


@section('additional_scripts')
    <script>

        @if(session('searchData'))
        var searchData = {!! session('searchData') !!};
	    @else
	    var searchData = null;
        @endif

	    console.log(searchData);

        if(typeof searchData !== 'undefined' && searchData !== null) {

            var type = searchData.estate_type;

	    $('.nem2[data-type='+type+']').addClass('active');
            $('#real_estate_type').val(type);

	        if(type === '1'){
                $('#price').html('Price: ');
                $('.extrafilter').remove();
                $('#extraTypes').html('');
                $('.type-block, .typeh').show();
            }

            if(type === '2'){
                $('#price').html('Rent: ');
                $('#extraTypes').html('<input id="extraCheckbox" name="types[]" type="checkbox"  value="0" />rental Buidling');
                $('.extrafilter').remove();
                $('.filters_list').append('<li class="extrafilter filters "><label for="354" style=""><input name="filters[]" id="354" value="354" type="checkbox">no Fee</label></li>');
                $('.type-block, .typeh').hide();
            }

            $('#mainFrom .box').show();

            $('.n').css('margin-top', '5vh');
            $('.search-wrap').css('margin-top', '10vh').fadeIn('fast');
            $('header').removeClass('hidden');
            $('#logo').fadeOut('fast');
            $('header').addClass('active');
            $('.col_details').removeClass('hidden');
            $('.loc-arr label').hide();

            if(searchData.districts){
                var districts = searchData.districts;

                var subDistrictId = $('#'+districts[0]).parent().attr('data-parent');

                $('[data-parent='+subDistrictId+']').show();

                $('#district_'+subDistrictId).val(subDistrictId).addClass('active');
		        $('[data-parent='+subDistrictId+']').show();

                for (var i = 0; i < districts.length; i++) {
                    $('#'+districts[i]).attr('checked', 'checked');
                }
            }

            if(searchData.types){
                var types = searchData.types;

                for (var i = 0; i < types.length; i++) {

                    $('#'+types[i]).attr('checked', 'checked');

                }
            }

            $('#bedFor').val(searchData.beds[0]);
            $('#bedTO').val(searchData.beds[1]);

            $('#bathFor').val(searchData.baths[0]);
            $('#bathTo').val(searchData.baths[1]);

            $('#priceFor').val(searchData.price[0]);
            $('#priceTo').val(searchData.price[1]);

                        if(searchData.filters){
                            var filters = searchData.filters;

                            for (var i = 0; i < filters.length; i++) {
                                $('#'+filters[i]).attr('checked', 'checked');
                            }
                        }
        }

        $('#search_main').on('click', function() {

            if (!$('.loc-arr input[type="checkbox"]').is(':checked')) {
                alert("Please select at least one location!");
                return false;
            }

        });

        function checkChild(id,e){

                $('.parent-' + id).prop('checked', $('#'+e).prop('checked'));

        }

        function checkChildBoro(id){
            $('[data-parent='+id+']').find('input').prop('checked' , $('#all_'+id).prop('checked'));
        }

    </script>
@endsection

