@extends('layouts.app2')

@section('header')
@endsection

@section('content')
<div class="mobile-menu" id="mobile-menu">
    @include('layouts.header2_1')        
</div>
<div class="wrapper">
    <div class="content">
        @include('partial.header')  
        <div class="action-menu">
            <div class="container" id="app2">
                <ul class="menu-list">
                    <li>
                        <a href="javascript:void(0);" class="print" onclick="window.print()">Print</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="email" id="email-topline" @click="showModal = true">E-mail</a>
                        @if (Auth::guest())
                        <div class="wrap-modal">
                            <modal v-if="showModal" @close="showModal = false">
                                <div slot="header">
                                    <label class="label">Enter email</label>
                                </div>
                                <div slot="body">
                                    <sendbuilding inline-template v-bind:building_name="'{{str_replace(' ', '_', $result->building_name)}}'" v-bind:building_city="'{{str_replace(' ', '_', $result->building_city)}}'">
                                        <div>
                                            <div class="field">
                                                <div class="control has-icons-left ">
                                                    <input v-model="guestEmail" class="input" id="email" type="email" name="email" value="" required autofocus>
                                                    <span class="icon is-small is-left"><i class="fa fa-envelope"></i></span>
                                                </div>
                                            </div>
                                            <button class="sendButton button is-primary" type="button" v-on:click="setPost">Send</button>
                                        </div>
                                    </sendbuilding>
                                </div>
                                <div slot="footer"></div>
                            </modal>
                        </div>
                        @else
                        <div class="wrap-modal">
                            <modal v-if="showModal" @close="showModal = false">
                                <div slot="header">
                                    <label class="label">Enter email</label>
                                </div>
                                <div slot="body">
                                    <sendbuilding inline-template v-bind:building_name="'{{str_replace(' ', '_', $result->building_name)}}'" v-bind:building_city="'{{str_replace(' ', '_', $result->building_city)}}'" v-bind:email="'{{Auth::user()->email}}'">
                                        <div>
                                            <div class="field">
                                                <div class="control has-icons-left ">
                                                    <input v-model="email" class="input" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                                                    <span class="icon is-small is-left"><i class="fa fa-envelope"></i></span>
                                                </div>
                                            </div>
                                            <button class="sendButton button is-primary" type="button" v-on:click="setPost">Send</button>
                                            <button class="sendButton button is-primary" type="button" v-on:click="setPost">To me</button>
                                        </div>
                                    </sendbuilding>
                                </div>
                                <div slot="footer"></div>
                            </modal>
                        </div>
                        @endif
                    </li>
                    <li>
                    @if (Auth::guest())
                        <a href="{{route('login')}}" class="save" id="search-toline">Save</a>
                    @else        
                        <form name="saveForm" method="post" action="/savebuilding">   
                            <input type="hidden" name="type" value="3" />
                            <input type="hidden" name="user_id" value="{{Auth::user()->id}}" />  
                            <input type="hidden" name="name" value="{{$name}}" />
                            <input type="hidden" name="city" value="{{$city}}" />
                            {{csrf_field()}}         
                        </form>
                        @if($saved)
                        <a href="javascript:void(0);" class="save">Saved &nbsp;<span style="color:green">&#10004;</span></a>
                        @else
                        <a href="javascript:void(0);" class="save" onclick="document.saveForm.submit();">Save</a>
                        @endif
                    @endif
                    </li>
                </ul>
                <div class="search-form">
                    @include('partial.search')
                </div>
            </div>
        </div>
        
        @if($currentAvailableSales->isEmpty() && $currentAvailableRentals->isEmpty())
        <div style="width:100%;text-align:center;color:red;font-size:18px;margin-top:20px;">Currently Not For Sale or Rent</div>
        @endif

        <div class="breadcrumbs">
            <div class="container">
                @if(session('status'))<div style="margin-bottom:20px;">{{session('status')}}</div>@endif
                <ul>
                    <li>
                        <a href="javascript:void(0);" onclick="window.history.go(-1); return false;">Previous Page</a>
                    </li>
                    <li>
                        <span>Building Page</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="listing" id="app">
            <div class="container">
                <div class="building-desc">
                    <div class="slider" style="max-height:380px;overflow:hidden;">
                        <div class="slider-for">
                        @if ($result->name_label && isset($name_label) && count($name_label))
                            @foreach ($name_label as $k => $image)
                            <div style="overflow:hidden;background-color: rgba(0, 0, 0, 0.8);">
                                <img class="slider-img" src="{{$image}}" alt="">
                            </div>
                            @endforeach 
                        @elseif (count($images) && is_array($images))
                            @foreach ($images as $k => $image)
                                @if ($result->building_build_year > 2008)
                                    @if ($k == 0)
                            <div style="overflow:hidden;background-color: rgba(0, 0, 0, 0.8);">
                                <img class="slider-img" src="{{$image}}" alt="">
                            </div>
                                    @else
                            <div style="overflow:hidden;background-color: rgba(0, 0, 0, 0.8);">
                                <img class="slider-img" src="/images/default_image_coming.jpg" alt="">
                            </div>
                                    @endif
                                @else
                            <div style="overflow:hidden;background-color: rgba(0, 0, 0, 0.8);">
                                <img class="slider-img" src="{{$image}}" alt="">
                            </div>
                                @endif
                            @endforeach
                        @else
                            <div style="overflow:hidden;background-color: rgba(0, 0, 0, 0.8);">
                                <img class="slider-img" src="/images/default_image_coming.jpg" alt="">
                            </div>
                        @endif
                        </div>
                        <div class="slider-nav">  
                        @if ($result->name_label && isset($name_label) && count($name_label))
                            @foreach ($name_label as $k => $image)
                            <div class="slider-bar-bg" style="overflow:hidden;background-color: rgba(0, 0, 0, 0.8);">
                                <img class="slider-bar-img" src="{{$image}}" alt="">
                            </div>
                            @endforeach 
                        @elseif (count($images) && count($images) > 0 && is_array($images))                                              
                            @foreach ($images as $k => $image)
                                @if ($result->building_build_year > 2008)
                                    @if ($k == 0)
                            <div class="slider-bar-bg" style="overflow:hidden;background-color: rgba(0, 0, 0, 0.8);">
                                <img class="slider-bar-img" src="{{$image}}" alt="">
                            </div>
                                    @else  
                            <div class="slider-bar-bg" style="overflow:hidden;background-color: rgba(0, 0, 0, 0.8);">
                                <img class="slider-bar-img" src="/images/default_image_coming.jpg" alt="">
                            </div>
                                    @endif
                                @else
                            <div class="slider-bar-bg" style="overflow:hidden;background-color: rgba(0, 0, 0, 0.8);">
                                <img class="slider-bar-img" src="{{$image}}" alt="">
                            </div>
                                @endif
                            @endforeach                    
                        @else   
                            <div class="slider-bar-bg" style="overflow:hidden;background-color: rgba(0, 0, 0, 0.8);">
                                <img class="slider-bar-img" src="/images/default_image_coming.jpg" alt="">
                            </div>
                        @endif
                        </div>

                        
                        <div class="listed-meta" style="text-align:right;margin-top:10px;">                            
                            @if ($result->name_label)
                            <div class="logo-listed" style="float:right;margin-bottom:10px;">
                                <div style="font-size:20px;float:left;"><i>{{$result->desc_name}}</i></div>
                                @if(isset($result->desc_company) && $result->desc_company)
                                    <div style="clear:both;"></div>
                                    <div style="font-size:20px;float:right;font-weight:bold;"><i>{{$result->desc_company}}</i></div>
                                @endif
                            </div>
                            <div style="float:right;margin-right:15px;margin-top:6px;">
                                Provided By:<br>
                                <span style="font-weight:normal;font-size:13px;">({{count($currentAvailableSales)+count($currentAvailableRentals)}} Deals Recently)</span>
                            </div>
                            @else              
                            <div class="logo-listed" style="float:right">                                                 
                                <div style="max-width:300px;text-align:left;">
                                    @if(Auth::guest()) 
                                    <a href="{{route('login')}}" style="text-decoration:underline;color:#282828;">You are invited to submit images for this building. For detail, click here.</a>
                                    @else
                                    <a href="{{route('nameLabelImage')}}" style="text-decoration:underline;color:#282828;">You are invited to submit images for this building. For detail, click here.</a>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                        
                    </div>
                    <div class="desc">
                        <div class="name"><h1 style="font-size:inherit;">{{$result->building_name}}</h1></div>
                        <div class="adress">{{$result->building_address}}, {{$result->building_city}} {{$result->building_state}}, {{$result->building_zip}}</div>
                        <div class="det">@if(isset($result->filterType)){{$result->filterType->value}}  in @endif{{$result->neighborhood}}</div>
                        <ul class="list">
                            <li>
                                <div class="text">Total Units:</div>
                                <div class="val">
                                    @if (!$result->building_units)
                                        N/A
                                    @elseif ($result->building_units)
                                        {{$result->building_units}}                               
                                    @endif
                                </div>
                            </li>
                            <li>
                                <div class="text">Floors:</div>
                                <div class="val">{{$result->building_stories}} stories</div>
                            </li>
                            <li>
                                <div class="text">Year Built:</div>
                                <div class="val">
                                    @if ($result->building_build_year)
                                        {{$result->building_build_year}}@if ($result->building_build_year < 1945) (prewar) @endif
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </li>
                            @if(isset($result->filterType))
                            <li>
                                <div class="text">Building Type:</div>
                                <div class="val">{{$result->filterType->value}}</div>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="content-side building">

                    {{--TODO: for name label desc --}}
                    <div class="block-ttl">Description:</div>
                    <div class="description">
                        @if ($result->described && $result->building_description)
                            <i style="font-weight:normal;font-size:13px;">
                                Provieded by {{$result->desc_name}}@if(isset($result->desc_company) && $result->desc_company), {{$result->desc_company}}@endif<br>
                                {{count($currentAvailableSales)+count($currentAvailableRentals)}} Deals Done Recently<br><br>
                            </i>
                            <span class="dmore">{!! strip_tags($result->building_description) !!}</span>
                        @else   
                            @if(Auth::guest())
                            <a href="{{route('login')}}" style="text-decoration:underline;color:#282828;"><div style="margin-top:10px;">You are invited to submit the description for this building. Your name will be displayed. For detail, click here.</div></a>
                            @else
                            <a href="{{route('nameLabelDescription')}}" style="text-decoration:underline;color:#282828;"><div style="margin-top:10px;">You are invited to submit the description for this building. Your name will be displayed. For detail, click here.</div></a>
                            @endif
                        @endif
                    </div>

                    <div class="block-ttl">Amenities/Features:</div>
                    <ul class="item-list"> 
                        @foreach (explode(',', $result->building_amenities[0]) as $b_amenities)
                            @if($b_amenities)
                        <li>{{$b_amenities}}</li>
                            @endif
                        @endforeach
                    </ul>

                    <div class="block-ttl">Current Available:</div>
                    
                    <div class="available">
                        <div class="block-sub-ttl">Sale</div>
                        @foreach($currentAvailableSales as $item)
                        <div class="available-item">
                            <a href="{{$item->name_link}}"><div class="bg-img" style="background-image: url(@if ($item->img) {{$item->img}} @else /images/default_image_coming.jpg @endif)"></div></a>
                            <div class="desc-wr">
                                @if($item->unit)
                                <div class="price-block">       
                                    <div class="name"><a href="{{$item->name_link}}" style="color:#1d7293">{{$item->unit}}</a></div>                             
                                    <div class="price">$ {{number_format($item->price,0,'.',',')}}</div>
                                </div>
                                @else
                                <div class="price-block" style="margin-top:0px">  
                                    <div class="name"><a href="{{$item->name_link}}" style="color:#1d7293">{{$item->full_address}}</a></div>
                                </div>
                                <div class="price-block" style="margin-top:5px">                                    
                                    <div class="price">$ {{number_format($item->price,0,'.',',')}}</div>
                                </div>
                                @endif
                                <ul class="benefits">
                                    <li>
                                        <span class="bed">{{$item->beds}} beds </span>
                                    </li>
                                    <li>
                                        <span class="bath">{{$item->baths}} bath</span>
                                    </li>
                                    <li>
                                        <span {{--class="ft"--}}>{{$item->sq_feet}} ft <sup>2</sup></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="available">
                        <div class="block-sub-ttl">Rental</div>
                        @foreach($currentAvailableRentals as $item)
                        <div class="available-item">
                            <a href="{{$item->name_link}}"><div class="bg-img" style="background-image: url(@if ($item->img) {{$item->img}} @else /images/default_image_coming.jpg @endif)"></div></a>
                            <div class="desc-wr">
                                @if($item->unit)
                                <div class="price-block">       
                                    <div class="name"><a href="{{$item->name_link}}" style="color:#1d7293">{{$item->unit}}</a></div>                             
                                    <div class="price">$ {{number_format($item->price,0,'.',',')}}</div>
                                </div>
                                @else
                                <div class="price-block" style="margin-top:0px">  
                                    <div class="name"><a href="{{$item->name_link}}" style="color:#1d7293">{{$item->full_address}}</a></div>
                                </div>
                                <div class="price-block" style="margin-top:5px">                                    
                                    <div class="price">$ {{number_format($item->price,0,'.',',')}}</div>
                                </div>
                                @endif
                                <ul class="benefits">
                                    <li>
                                        <span class="bed">{{$item->beds}} beds </span>
                                    </li>
                                    <li>
                                        <span class="bath">{{$item->baths}} bath</span>
                                    </li>
                                    <li>
                                        <span {{--class="ft"--}}>{{$item->sq_feet}} ft <sup>2</sup></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    {{--
                    <div class="block-ttl">Past Sales:</div>
                    <div class="custom-table">
                        <div class="table-header">
                            <div class="tr">
                                <div class="td">Date</div>
                                <div class="td">Number</div>
                                <div class="td">Price</div>
                                <div class="td">Options</div>
                            </div>
                        </div>
                        <div class="table-body">
                            <div class="tr">
                                <div class="td">
                                    <span class="mobile-text">Date</span>8/25/2017</div>
                                <div class="td">
                                    <span class="mobile-text">Number</span>10A</div>
                                <div class="td">
                                    <span class="mobile-text">Price</span>$2,000,000</div>
                                <div class="td">
                                    <span class="mobile-text">Options</span>3 bed / 3 bath</div>
                            </div>
                            <div class="tr">
                                <div class="td">
                                    <span class="mobile-text">Date</span>8/25/2017</div>
                                <div class="td">
                                    <span class="mobile-text">Number</span>10A</div>
                                <div class="td">
                                    <span class="mobile-text">Price</span>$2,000,000</div>
                                <div class="td">
                                    <span class="mobile-text">Options</span>3 bed / 3 bath</div>
                            </div>
                            <div class="tr">
                                <div class="td">
                                    <span class="mobile-text">Date</span>8/25/2017</div>
                                <div class="td">
                                    <span class="mobile-text">Number</span>10A</div>
                                <div class="td">
                                    <span class="mobile-text">Price</span>$2,000,000</div>
                                <div class="td">
                                    <span class="mobile-text">Options</span>3 bed / 3 bath</div>
                            </div>
                            <div class="tr">
                                <div class="td">
                                    <span class="mobile-text">Date</span>8/25/2017</div>
                                <div class="td">
                                    <span class="mobile-text">Number</span>10A</div>
                                <div class="td">
                                    <span class="mobile-text">Price</span>$2,000,000</div>
                                <div class="td">
                                    <span class="mobile-text">Options</span>3 bed / 3 bath</div>
                            </div>


                        </div>
                    </div>
                    <div class="block-ttl">Past Rental:</div>
                    <div class="custom-table">
                        <div class="table-header">
                            <div class="tr">
                                <div class="td">Date</div>
                                <div class="td">Number</div>
                                <div class="td">Price</div>
                                <div class="td">Options</div>
                            </div>
                        </div>
                        <div class="table-body">
                            <div class="tr">
                                <div class="td">
                                    <span class="mobile-text">Date</span>8/25/2017</div>
                                <div class="td">
                                    <span class="mobile-text">Number</span>10A</div>
                                <div class="td">
                                    <span class="mobile-text">Price</span>$2,000,000</div>
                                <div class="td">
                                    <span class="mobile-text">Options</span>3 bed / 3 bath</div>
                            </div>
                            <div class="tr">
                                <div class="td">
                                    <span class="mobile-text">Date</span>8/25/2017</div>
                                <div class="td">
                                    <span class="mobile-text">Number</span>10A</div>
                                <div class="td">
                                    <span class="mobile-text">Price</span>$2,000,000</div>
                                <div class="td">
                                    <span class="mobile-text">Options</span>3 bed / 3 bath</div>
                            </div>
                            <div class="tr">
                                <div class="td">
                                    <span class="mobile-text">Date</span>8/25/2017</div>
                                <div class="td">
                                    <span class="mobile-text">Number</span>10A</div>
                                <div class="td">
                                    <span class="mobile-text">Price</span>$2,000,000</div>
                                <div class="td">
                                    <span class="mobile-text">Options</span>3 bed / 3 bath</div>
                            </div>
                            <div class="tr">
                                <div class="td">
                                    <span class="mobile-text">Date</span>8/25/2017</div>
                                <div class="td">
                                    <span class="mobile-text">Number</span>10A</div>
                                <div class="td">
                                    <span class="mobile-text">Price</span>$2,000,000</div>
                                <div class="td">
                                    <span class="mobile-text">Options</span>3 bed / 3 bath</div>
                            </div>
                        </div>
                    </div>
                    --}}

                    <div class="block-ttl">Map View:</div>
                    <div class="map-wr">
                        <!-- !!! -->
                        <!-- Warning -->
                        <!-- google map lgn and lat  -->
                        <!-- !!! -->
                        <div id="div_map"><div id="map_canvas" style="width:100%;height:350px"></div></div>
                    </div>
                </div>
                <div class="sidebar">
                    {{-- no agents on buildings
                    <div class="sidebar-ttl">Listed By:</div>
                    <ul class="listen-list">
                        @php $all_emails = array(); @endphp
                        @foreach ($agents as $agent)
                            @php if (trim($agent->email)) $all_emails[] = $agent->email; @endphp
                        <li>
                            <div>
                                @if (isset($agent->img) && $agent->img)
                                    @if (isset($agent->web_site) && $agent->web_site)
                                    <p style="width:77px;height:77px;display:block;position:relative;overflow: hidden;border-radius: 50%;">
                                        <a href="{{$agent->web_site}}" target="_blank"><img src="{{$agent->img}}" style="display:block;height:auto;width:100%;"></a>
                                    </p>
                                    @else
                                    <p style="width:77px;height:77px;display:block;position:relative;overflow: hidden;border-radius: 50%;">
                                        <img src="{{$agent->img}}" style="display:block;height:auto;width:100%;">
                                    </p>
                                    @endif
                                @endif
                            </div>
                            <div class="text-wr">
                                <div class="name">{{$agent->name}}</div>
                                @if (isset($agent->phone) && $agent->phone)
                                <div class="phone">{{$agent->phone}}</div>
                                @endif
                                @if(isset($agent->web_site) && $agent->web_site)
                                <div>
                                    <a href="{{$agent->web_site}}" target="_blank" class="link">Website</a>
                                </div>
                                @endif  
                                @if (isset($agent->web_site) && $agent->web_site) 
                                    @if(isset($agent->path_to_logo) && $agent->path_to_logo)
                                        <a href="{{$agent->web_site}}" target="_blank"><img src="{{$agent->path_to_logo}}" style="margin-top:10px;max-width:150px;max-height:50px;"></a>
                                    @elseif($result->path_to_logo)
                                        <a href="{{$agent->web_site}}" target="_blank"><img src="{{$result->path_to_logo}}" style="margin-top:10px;max-width:150px;max-height:50px;"></a>
                                    @elseif(isset($agent->company) && $agent->company)
                                        <a href="{{$agent->web_site}}" target="_blank" class="link">{{ucwords($agent->company)}}</a>
                                    @endif
                                @else
                                    @if(isset($agent->path_to_logo) && $agent->path_to_logo)
                                        <a href="{{$agent->web_site}}" target="_blank"><img src="{{$agent->path_to_logo}}" style="margin-top:10px;max-width:150px;max-height:50px;"></a>
                                    @elseif($result->path_to_logo)
                                        <a href="{{$agent->web_site}}" target="_blank"><img src="{{$result->path_to_logo}}" style="margin-top:10px;max-width:150px;max-height:50px;"></a>
                                        @elseif(isset($agent->company) && $agent->company)
                                        <a href="javascript:void(0);" class="link">{{ucwords($agent->company)}}</a>
                                    @endif
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    <div class="sidebar-ttl">Need More Information?</div>
                    <div id="app" class="contact-form">
                        <send inline-template v-bind:type="'building'" v-bind:agentemail="'@if(count($all_emails)){{implode(',',$all_emails)}}@else{{''}}@endif'" v-bind:name="'@if(isset(Auth::user()->name)){{Auth::user()->name}}@else{{'Guest'}}@endif'" v-bind:listingid="{{$result->id}}">
                            <div>
                                <div class="input-wr">
                                    <label for="inp1">e-mail adress:</label>
                                    <input v-model="useremail" name="email" type="text" id="inp1" placeholder="Write here…" required>
                                </div>
                                <div class="input-wr">
                                    <label for="inp2">phone number:</label>
                                    <input v-model="phone" name="phone" type="text" id="inp2" placeholder="Write here…" required>
                                </div>
                                <div class="input-wr">
                                    <label for="inp3">Description:</label>
                                    <textarea v-model="message" name="message" id="inp3" placeholder="Write here…" required></textarea>
                                </div>
                                <button id="sendButton" type="button" v-on:click="setPost" class="button btn"><div style="width:100%;text-align:center;">Send</div></button>
                                <div id="messageResponse" style="padding-top:10px;"></div>
                            </div>
                        </send>
                    </div>--}}
                </div>
                <div class="currently">
                    <div class="container">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
	@include('layouts.footerMain2')
@endsection   

@section('additional_scripts')
<script>
$(window).on("load", function(){  //wait for images fully loaded first, better than "$(document).ready"
    /*==for slider images==*/
    resizeSlider();
    var timer1 = null;
    $(window).on('resize orientationchange', function() {
        clearTimeout(timer1);        
        timer1 = setTimeout(function(){resizeSlider();}, 100);
    });

    $('.slider').css('max-height', '');
});

/*==for slider images==*/
resizeSlider=function() {
    var max_h = 0;

    $('.slider-img').each(function() {
        var img_h = $(this).height();
        var img_w = $(this).width();

        if (img_w > img_h && img_h > max_h){
            max_h = img_h;
            //alert(max_h);
        }
    });

    if (max_h > 0) {
        if (max_h > 380)
            max_h = 380;

        $('.slider-img').each(function() {
            var img_h = $(this).height();
            var img_w = $(this).width();

            if (img_h >= img_w) {
                $(this).height(max_h);
                $(this).width((img_w/img_h)*max_h);
            }
            else {
                $(this).parent().height(max_h);
                $(this).css({'position':'relative', 'top':'50%', 'transform': 'translateY(-50%)'});
            }
        });
    }
    else { //no horizontal images
        $('.slider-img').each(function() {
            var img_h = $(this).height();
            var img_w = $(this).width();

            if (img_h >= img_w && img_h > 380) {
                $(this).height(380);
                $(this).width((img_w/img_h)*380);
            }
        });
    }

    //for slider bar images
    var max_h = 0;

    $('.slider-bar-img').each(function() {
        var img_h = $(this).height();
        var img_w = $(this).width();

        if (img_w > img_h && img_h > max_h){
            max_h = img_h;
            //alert(max_h);
        }
    });

    if (max_h > 0) {
        if (max_h > 71)
            max_h = 71;

        $('.slider-bar-img').each(function() {
            var img_h = $(this).height();
            var img_w = $(this).width();

            if (img_h >= img_w) {
                $(this).height(max_h);
                $(this).width((img_w/img_h)*max_h);
            }
            else {
                $(this).parent().height(max_h);
                $(this).css({'position':'relative', 'top':'50%', 'transform': 'translateY(-50%)'});
            }
        });
    }
    else { //no horizontal images
        $('.slider-bar-img').each(function() {
            var img_h = $(this).height();
            var img_w = $(this).width();

            if (img_h >= img_w && img_h > 71) {
                $(this).height(71);
                $(this).width((img_w/img_h)*71);
            }
        });
    }
}

/*==for map==*/
var map = new google.maps.Map(document.getElementById('map_canvas'), {
    zoom: 16,
    center: {lat: 40.692464, lng: -74.2008182},
});
var geocoder = new google.maps.Geocoder();

geocodeAddress(geocoder, map);

function geocodeAddress(geocoder, resultsMap) {
    var address = '{{$result->building_address}} {{$result->building_city}} {{$result->building_zip}}';
    geocoder.geocode({'address': address}, function(results, status) {
        if (status === 'OK') {
            resultsMap.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
                map: resultsMap,
                position: results[0].geometry.location
            });
        } else {
            console.log('Geocode was not successful for the following reason: ' + status);
        }
    });
}

/*======for description========*/
var showChar = 255;
var ellipsestext = "...";
var moretext = "read more";
var lesstext = "less";
$('.dmore').each(function() {
    var content = $(this).html();
    if(content.length > showChar) {
        var c = content.substr(0, showChar);
        //alert(c);
        var h = content.substr(showChar, content.length - showChar);
        var html = c + '<span class="moreelipses">'+ellipsestext+'</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">'+moretext+'</a></span>';
        $(this).html(html);
        //alert(html);
    }

});

$(".morelink").click(function(){
    if($(this).hasClass("less")) {
        $(this).removeClass("less");
        $(this).html(moretext);
    } else {
        $(this).addClass("less");
        $(this).html(lesstext);
    }
    $(this).parent().prev().toggle();
    $(this).prev().toggle();
    return false;
});

$(".morelink").prev().toggle();
</script>
@endsection