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
                                    <sendlisting inline-template v-bind:listing_id="{{$result->id}}">
                                        <div>
                                            <div class="field">
                                                <div class="control has-icons-left ">
                                                    <input v-model="guestEmail" class="input" id="email" type="email" name="email" value="" required autofocus>
                                                    <span class="icon is-small is-left"><i class="fa fa-envelope"></i></span>
                                                </div>
                                            </div>
                                            <button class="sendButton button is-primary" type="button" v-on:click="setPost">Send</button>
                                        </div>
                                    </sendlisting>
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
                                    <sendlisting inline-template v-bind:listing_id="{{$result->id}}" v-bind:email="'{{Auth::user()->email}}'">
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
                                    </sendlisting>
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
                        <form name="saveForm" method="post" action="/saveitem">   
                            <input type="hidden" name="type" value="{{$result->estate_type}}" />
                            <input type="hidden" name="save_id" value="{{$result->id}}" />
                            <input type="hidden" name="user_id" value="{{Auth::user()->id}}" />  
                            <input type="hidden" name="name" value="{{$name}}" />
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
        <div class="breadcrumbs">
            <div class="container">
                @if(session('status'))<div style="margin-bottom:20px;">{{session('status')}}</div>@endif
                <ul>
                    <li>
                        <a href="javascript:void(0);" onclick="window.history.go(-1); return false;">Previous Page</a>
                    </li>
                    <li>
                        <span>{{$result->full_address}} {{$result->unit}} {{$result->city}} {{$result->state}}, {{$result->zip}}</span>
                    </li>
                </ul>
            </div>
        </div>
        <div id="app" class="listing">
            <div class="container">
                <div class="page-header">
                    <div class="left-side">
                        <h1 class="page-ttl">{{$result->full_address}} {{$result->unit}} {{$result->city}} {{$result->state}}, {{$result->zip}}</h1>
                        <div style="width:100%">
                        <div class="price" style="float:left;">${{number_format($result->price,0,'.',',')}}</div>
                        @if($result->estate_type == 2 && $result->fees == 0)
                        <div style="float:right;font-size:20px;color:green;font-weight:800;padding-top:3px;">NO FEE</div>
                        @endif
                        </div>
                    </div>
                    <div class="right-side">
                        @if ($result->building && $result->building->id)
                        <a href="/building/{{str_replace(' ', '_', $result->building->building_name)}}/{{str_replace(' ', '_', $result->building->building_city)}}" class="link">Current Available in Building</a>
                        @else
                        <a href="javascript:void(0);" class="link">Current Available in building</a>
                        @endif
                    </div>
                </div>
                <div class="content-side">
                    <div class="slider" style="max-height:380px;overflow:hidden;">
                        <div class="slider-for">
                        @if (count($images) || count($result->path_for_floorplans))
                            @foreach ($images as $image)
                            <?php if (preg_match('/\.[a-zA-Z]{3,4}$/', $image)) { ?>
                            <div style="overflow:hidden;background-color: rgba(0, 0, 0, 0.8);">
                                <img class="slider-img" src="{{$image}}" alt="">
                            </div>
                            <?php } ?>
                            @endforeach
                            @foreach ($result->path_for_floorplans as $fimage)
                            <?php if (preg_match('/\.[a-zA-Z]{3,4}$/', $fimage)) { ?>
                            <div style="overflow:hidden;background-color: rgba(0, 0, 0, 0.8);">
                                <img class="slider-img" src="{{$fimage}}" alt="">
                            </div>
                            <?php } ?>
                            @endforeach
                        @else
                            <div style="overflow:hidden;background-color: rgba(0, 0, 0, 0.8);">
                                <img class="slider-img" src="/images/default_image_coming.jpg" alt="">
                            </div>
                        @endif
                        </div>
                        
                        <div class="slider-nav">
                        @if (count($images) + count($result->path_for_floorplans) > 0)                        
                            @foreach ($images as $image)
                            <?php if (preg_match('/\.[a-zA-Z]{3,4}$/', $image)) { ?>
                            <div class="slider-bar-bg" style="overflow:hidden;background-color: rgba(0, 0, 0, 0.8);">
                                <img class="slider-bar-img" src="{{$image}}" alt="">
                            </div>
                            <?php } ?>
                            @endforeach
                            @foreach ($result->path_for_floorplans as $fimage)
                            <?php if (preg_match('/\.[a-zA-Z]{3,4}$/', $fimage)) { ?>
                            <div class="slider-bar-bg" style="overflow:hidden;background-color: rgba(0, 0, 0, 0.8);">
                                <img class="slider-bar-img"src="{{$fimage}}" alt="">
                            </div>
                            
                            <?php } ?>
                            @endforeach
                        @else
                            <div class="slider-bar-bg" style="overflow:hidden;background-color: rgba(0, 0, 0, 0.8);">
                                <img class="slider-bar-img" src="/images/default_image_coming.jpg" alt="">
                            </div>
                        @endif
                        </div>
                    
                    </div>
                    <div class="listed-meta">
                        <div class="left-side">
                            <div class="block-ttl">Listed:</div>
                            <div class="date">{{$result->listed}}</div>
                        </div>
                        <div class="logo-listed" style="float:right">
                            @if ($result->path_to_logo)
                            <img src="{{$result->path_to_logo}}" alt="" style="max-width:150px;max-height:50px;">
                            @elseif (isset($result->agent_company) && $result->agent_company)
                            {{ucwords($result->agent_company)}}
                            @endif
                        </div>
                    </div>
                    <div class="block-ttl">Detail:</div>                    
                    <div class="details-block">
                        <ul>
                            <li>
                                <div class="ttl">Beds:</div>
                                <div class="val">{{$result->beds}}</div>
                            </li>
                            <li>
                                <div class="ttl">Bath:</div>
                                <div class="val">{{$result->baths}}</div>
                            </li>
                            <li>
                                <div class="ttl">Size:</div>
                                <div class="val">
                                    @if(!$result->sq_feet) N/A @else {{$result->sq_feet}} ft <sup>2</sup> @endif
                                </div>
                            </li>
                            <li>
                                <div class="ttl">Condition:</div>
                                <div class="val">{{$result->condition}}</div>
                            </li>
                        </ul>
                        <ul style="margin-top:15px">
                            <li>
                                <div class="ttl">Tax:</div>
                                <div class="val">${{$result->tax}}</div>
                            </li>                           
                            <li>
                                <div class="ttl">Maintenance:</div>
                                <div class="val">${{$result->maint}}</div>
                            </li>
                            <li>
                                <div class="ttl">Type:</div>
                                <div class="val" style="font-size:17px;padding-top:5px;">
                                    {{$result->unit_type}}
                                </div>
                            </li>
                            <li>
                                <div class="ttl">Neighborhood:</div>
                                <div class="val" style="font-size:17px;padding-top:5px;">
                                    {{$result->neighborhood}}
                                </div>
                            </li>
                        </ul>
                    </div>
                    {{--
                    <ul class="item-list" style="margin-top:15px;">
                        @if($result->estate_type == 2 && $result->fees == 0)
                        <li>No Fee</li>
                        @endif
                    </ul>
                    --}}

                    @foreach($result->openHouse as $key=>$v)
                        @if(Carbon\Carbon::now() > $v->end_time)
                            @php unset($result->openHouse[$key]) @endphp
                        @endif
                    @endforeach

                    {{--@if(isset($result->openHouse) && !empty($result->openHouse) && count($result->openHouse))--}}     
                    @if(isset($result->openHouse))        
                    <div class="building" style="margin-top:30px;margin-bottom:30px;">
                        <div class="block-ttl">Open House:</div>
                        <div class="custom-table" style="max-width:280px;">
                            <div class="table-header" style="display:block;">
                                <div class="tr" style="padding:0 10px;">
                                    <div class="td" style="width:40%;margin-bottom: 10px;margin-top:10px;justify-content:left;">Date</div>
                                    <div class="td" style="width:30%;margin-bottom: 10px;margin-top:10px;justify-content:left;">Start</div>
                                    <div class="td" style="width:30%;margin-bottom: 10px;margin-top:10px;justify-content:left;">End</div>
                                </div>
                            </div>
                            <div class="table-body">
                                @foreach($result->openHouse as $value)
                                <div class="tr" style="padding:0 10px;">
                                    <div class="td" style="width:40%;margin-bottom: 10px;margin-top:10px;justify-content:left;">
                                        {{Carbon\Carbon::parse($value->date)->format('D M j')}}</div>
                                    @if ($value->appointment)
                                    <div class="td" style="width:60%;margin-bottom: 10px;margin-top:10px;test-align:left;justify-content:left;">
                                        By Appointment</div>
                                    @else
                                    <div class="td" style="width:30%;margin-bottom: 10px;margin-top:10px;justify-content:left;">
                                        {{Carbon\Carbon::parse($value->start_time)->format('g:iA')}}</div>
                                    <div class="td" style="width:30%;margin-bottom: 10px;margin-top:10px;justify-content:left;">
                                        {{Carbon\Carbon::parse($value->end_time)->format('g:iA')}}</div>      
                                    @endif                              
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="block-ttl">Description:</div>
                    <div class="description">
                        @if ($result->unit_description)
                            <span class="dmore">{!! strip_tags($result->unit_description) !!}</span>
                        @endif
                        {{--<p>No Fee Apartments on Meserole Street! Loft like renovated apartments in this elevator building
                            one block from the Montrose Ave L train stop. Each apartment features brand new hard wood
                            floors, a minimum of FOUR windows, extra high… Lorem ipsum dolor sit amet, consectetur adipisicing
                            elit. Neque, velit.</p>
                        <a href="#" class="more">More</a>--}}
                    </div>
                    <div class="block-ttl">Amenities:</div>
                    <ul class="item-list">
                        @foreach (explode(',', $result->b_amenities) as $b_amenities)
                            @if($b_amenities)
                        <li>{{$b_amenities}}</li>
                            @endif
                        @endforeach
                    </ul>
                    <div class="block-ttl">Apartment Features:</div>
                    <ul class="item-list">
                        @foreach (explode(',', implode(', ',$result->amenities)) as $amenities)
                            @if($amenities)
                        <li>{{$amenities}}</li>
                            @endif
                        @endforeach
                    </ul>

                    <a href="javascript:void(0);" class="calculator" onclick="toggleCalc()">mortage calculator</a>
                    <div id ="calcContainer" class="calcContainer" style="display:none; width:250px; margin-top:15px; margin-bottom:30px;">
                    <form id="calcForm" onreset="load(), resetTotal()">
                        <div class="field">
                            <label class="label">Enter the Loan Amount:</label>
                            <div class="control has-icons-left ">
                                <input type="text" class="input" id="inAmount"/>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Enter the APR (Interest Rate):</label>
                            <div class="control has-icons-left ">
                                <input type="text" class="input" size="17.5" id="inAPR"/>
                                <span class="icon is-small is-left">
                                    <i class="fa fa-percent"></i>
                                </span>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Enter the Period (in years):</label>
                            <div class="control has-icons-left ">
                                <input type="text" class="input" id="inPeriod"/>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Enter the Down Payment (optional):</label>
                            <div class="control has-icons-left ">
                                <input type="text" class="input" onchange="doIT()" id="inDown"/>
                            </div>
                        </div>
                    </form>
                    <br/>
                    <button class="button is-primary" id="btnCalculate" onclick="doIT()"><span>Calculate Payments</span></button>
                    <button id="btnClear"  class="button" onclick="wipeIT(),play()">Reset</button>
                    <p class="is-size-6" style="padding-top:20px;"><b>Total Monthly Payment: <div id="outFinal" style="margin-top:10px;"></div></b></p>
                    </div>

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
                    <div class="sidebar-ttl">Listed By:</div>
                    <ul class="listen-list listing">
                        @php $all_emails = array(); @endphp
                        @foreach ($agents as $agent) 
                            @php 
                                if (trim($agent->email)) 
                                    $all_emails[] = $agent->email; 

                                if (isset($agent->full_name) && $agent->full_name) 
                                    $name = str_replace(' ', '_', $agent->full_name);
                                elseif (isset($agent->first_name) && $agent->first_name)
                                    $name = str_replace(' ', '_', $agent->first_name) . '_'. str_replace(' ', '_', $agent->last_name);
                            @endphp
                        <li>                            
                            <div>
                                <a href="@if($result->amazon_id==0 || isset($agent->user) && $agent->user->premium && isset($agent->id))/agent/{{$name}}/{{$agent->id}}@else javascript:void(0); @endif">
                                    <p style="width:77px;height:77px;display:block;position:relative;overflow: hidden;border-radius: 50%;background-image: url(@if (isset($agent->img) && $agent->img){{$agent->img}}@else /images/default_agent.jpg @endif);background-size:cover;background-position:top center;">   
                                        {{--<img src="@if (isset($agent->img) && $agent->img){{$agent->img}}@else /images/default_agent.jpg @endif" style="display:block;height:auto;width:100%;">--}}   
                                    </p>
                                </a>
                            </div>
                            <div class="text-wr">
                                <div class="name"><a style="color:inherit;" href="@if($result->amazon_id==0 || isset($agent->user) && $agent->user->premium && isset($agent->id))/agent/{{$name}}/{{$agent->id}}@else javascript:void(0); @endif">
                                @if($agent->name){{$agent->name}}@else{{$agent->full_name}}@endif 
                                @if(isset($agent->role_id))
                                    @if($agent->role_id == 2)
                                    <br><span style="font-size:13px;">(Owner)</span>
                                    @elseif($agent->role_id == 5)
                                    <br><span style="font-size:13px;">(Management)</span>
                                    @endif
                                @endif
                                </a></div>
                                @if (isset($agent->phone) && $agent->phone)
                                <div class="phone">{{$agent->phone}}</div>
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
                                        <a href="javascript:void(0);" target="_blank"><img src="{{$agent->path_to_logo}}" style="margin-top:10px;max-width:150px;max-height:50px;"></a>
                                    @elseif($result->path_to_logo)
                                        <a href="javascript:void(0);" target="_blank"><img src="{{$result->path_to_logo}}" style="margin-top:10px;max-width:150px;max-height:50px;"></a>
                                    @elseif(isset($agent->company) && $agent->company)
                                        <a href="javascript:void(0);" class="link">{{ucwords($agent->company)}}</a>
                                    @endif
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    <div class="sidebar-ttl">Need More Information?</div>
                    <div class="contact-form">
                        <send inline-template v-bind:type="'regular'" v-bind:agentemail="'@if(count($all_emails)){{implode(',',$all_emails)}}@else{{''}}@endif'" v-bind:name="'@if(isset(Auth::user()->name)){{Auth::user()->name}}@else{{'Guest'}}@endif'" v-bind:listingid="{{$result->id}}">
                            <div>                                
                                <div class="input-wr">
                                    <label for="inp2">phone number:</label>
                                    <input v-model="phone" name="phone" type="text" id="inp2" placeholder="Write here…" onkeypress="return isNumberKey(event)" required>
                                </div>
                                <div class="input-wr">
                                    <label for="inp1">e-mail address:</label>
                                    <input v-model="useremail" name="email" type="text" id="inp1" placeholder="Write here…" required>
                                </div>
                                <div class="input-wr">
                                    <label for="inp3">Add Note:</label>
                                    <textarea v-model="message" name="message" id="inp3" placeholder="Write here…" required></textarea>
                                </div>
                                <button id="sendButton" type="button" v-on:click="setPost" class="button btn"><div style="width:100%;text-align:center;">Send Message</div></button>
                                <div id="messageResponse" style="padding-top:10px;"></div>
                            </div>
                        </send>
                        {{--<send inline-template v-bind:agentemail="'@if(isset($agent)){{$agent->email}}@else{{''}}@endif'" v-bind:name="'@if(isset(Auth::user()->name)){{Auth::user()->name}}@else{{'Guest'}}@endif'" v-bind:listingid="{{$result->id}}">
                            <div class="input-wr">
                                <label for="inp1">e-mail address:</label>
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
                            <button id="sendButton" v-on:click="setPost" class="btn"><div style="width:100%;text-align:center;">Send</div></button>
                            <div id="messageResponse" style="padding-top:10px;"></div>
                        </send>--}}
                    </div>
                </div>

                <div class="currently">
                    <div class="container">
                        @if(!$currentAvailableSales->isEmpty())
                        <div class="block-ttl">Currently Available Sales</div>
                        <ul style="display:block;">
                            @foreach($currentAvailableSales as $k => $item)
                            <li class="item">
                                <div class="bg-item" style="background-image: url(@if ($item->images) {{$item->path_for_images}} @else /images/default_image_coming.jpg @endif)"></div>
                                <div class="text-wr">
                                    <a href="/show/{{$item->name}}/{{$item->id}}" class="item-name">{{$item->full_address}} {{$item->unit}}</a>
                                    <div class="item-place">{{$item->neighborhood}}</div>
                                    <div class="item-price">
                                        <span class="price">${{number_format($item->price,0,'.',',')}}</span>
                                        <div class="name-desc">
                                            <div class="agent">{{$item->unit_type}}</div>
                                            @if($item->estate_type == 2 && $item->fees == 0)
                                            <div class="additional">No Fee</div>
                                            @endif
                                        </div>
                                    </div>
                                    <ul class="benefits">
                                        <li>
                                            <span class="bed">{{$item->beds}} beds </span>
                                        </li>
                                        <li>
                                            <span class="bath">{{$item->baths}} bath</span>
                                        </li>
                                        <li>
                                            <span {{--class="ft"--}} style="padding-left:5px;">{{$item->sq_feet}} ft <sup>2</sup></span>
                                        </li>
                                    </ul>
                                    <div class="item-time">
                                        <div class="time">
                                            @foreach($item->OpenHouse as $key=>$v)
                                                @if(Carbon\Carbon::now() > $v->end_time)
                                                    @php unset($item->OpenHouse[$key]) @endphp
                                                @endif
                                            @endforeach
                                            @if(isset($item->OpenHouse) && !$item->OpenHouse->isEmpty())
                                                @if(count($item->OpenHouse))
                                                Open House:
                                                @endif

                                                @foreach($item->OpenHouse as $key=>$v)
                                                <span>
                                                    {{Carbon\Carbon::parse($v->date)->format('D M j')}} 
                                                    @if($v->appointment) 
                                                        By Appointment 
                                                    @else
                                                        {{Carbon\Carbon::parse($v->start_time)->format('g:i A')}} - {{Carbon\Carbon::parse($v->start_end)->format('g:i A')}}
                                                    @endif
                                                </span>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>     
                            @endforeach                                              
                        </ul>
                        @endif
                    </div>
                </div>

                <div class="currently">
                    <div class="container">
                        @if(!$currentAvailableRentals->isEmpty())
                        <div class="block-ttl">Currently Available Rentals</div>
                        <ul style="display:block;">
                            @foreach($currentAvailableRentals as $k => $item)                            
                            <li class="item">
                                <div class="bg-item" style="background-image: url(@if ($item->images) {{$item->path_for_images}} @else /images/default_image_coming.jpg @endif)"></div>
                                <div class="text-wr">
                                    <a href="/show/{{$item->name}}/{{$item->id}}" class="item-name">{{$item->full_address}} {{$item->unit}}</a>
                                    <div class="item-place">{{$item->neighborhood}}</div>
                                    <div class="item-price">
                                        <span class="price">${{number_format($item->price,0,'.',',')}}</span>
                                        <div class="name-desc">
                                            <div class="agent">{{$item->unit_type}}</div>
                                            @if($item->estate_type == 2 && $item->fees == 0)
                                            <div class="additional">No Fee</div>
                                            @endif
                                        </div>
                                    </div>
                                    <ul class="benefits">
                                        <li>
                                            <span class="bed">{{$item->beds}} beds </span>
                                        </li>
                                        <li>
                                            <span class="bath">{{$item->baths}} bath</span>
                                        </li>
                                        <li>
                                            <span {{--class="ft"--}} style="padding-left:5px;">{{$item->sq_feet}} ft <sup>2</sup></span>
                                        </li>
                                    </ul>
                                    <div class="item-time">
                                        <div class="time">
                                            @foreach($item->OpenHouse as $key=>$v)
                                                @if(Carbon\Carbon::now() > $v->end_time)
                                                    @php unset($result->OpenHouse[$key]) @endphp
                                                @endif
                                            @endforeach
                                            @if(isset($item->OpenHouse) && !$item->OpenHouse->isEmpty())
                                                @if(count($item->OpenHouse))
                                                Open House:
                                                @endif

                                                @foreach($item->OpenHouse as $key=>$v)
                                                <span>
                                                    {{Carbon\Carbon::parse($v->date)->format('D M j')}} 
                                                    @if($v->appointment) 
                                                        By Appointment 
                                                    @else
                                                        {{Carbon\Carbon::parse($v->start_time)->format('g:i A')}} - {{Carbon\Carbon::parse($v->start_end)->format('g:i A')}}
                                                    @endif
                                                </span>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>     
                            @endforeach                                              
                        </ul>
                        @endif
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

function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

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
    var address = '{{$result->full_address}} {{$result->city}} {{$result->state}} {{$result->zip}}';
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

/*for calculator*/
const invalidColor = "rgb(237, 190, 190)";

//formula is c= ( (r*p) * Math.pow((1+r), n))  / (Math.pow((1+r),n) - 1)
//@param p double is Amount
//@param r the interest as percent.
//@param n Term in years
function calculateMortgage(p,r,n)
{
    //convert to decimal
    r = convertToDecimal(r);
    //convert to months
    n = convertToMonth(n);

    var total = ( (r*p) * Math.pow((1+r), n))  / (Math.pow((1+r),n) - 1);

    return parseFloat(total.toFixed(2));

}

function convertToDecimal(percent)
{
    return ((percent/12)/100);
}

function convertToMonth(year)
{
    return (year*12);
}

function postPayment(payment)
{
    var htmlEL = document.getElementById("outFinal");
    htmlEL.innerHTML = "$" + payment;
}

function resetTotal()
{
    var total = document.getElementById("outFinal");
    total.innerHTML = "";
}

//calculate the data, JUST DO IT
function doIT()
{
    var cost = document.getElementById("inAmount").value;
    if(cost == "")
    {
        alert("Please Enter an amount")
        return false;
    }
    if(cost <= 0 )
    {
        alert("Invalid Cost")

        return false;
    }

    var downPayment = document.getElementById("inDown").value;

    if(downPayment < 0)
    {
        alert("Invalid Down Payment")
        return false;
    }

    var interest = document.getElementById("inAPR").value;

    if(interest == "")
    {
        alert("Please Enter an interest rate")
        return false;
    }
    if(interest <= 0 || !(interest >=0 && interest <=100))
    {
        alert("Invalid interest rate")
        return false;
    }

    var term = document.getElementById("inPeriod").value;

    if(term == "")
    {
        alert("Please Enter a term")
        return false;
    }
    if(term <= 0)
    {
        alert("Invalid Term")
        return false;
    }

    var loanAmount = cost - downPayment;

    var pmt = calculateMortgage(loanAmount,interest,term);

    postPayment(pmt);

};

function load()
{
    document.getElementById("inAmount").focus();
}

function wipeIT()
{
    document.getElementById("calcForm").reset();

}

function play() {
    document.getElementById("snd").currentTime = 0;
    snd.play();
}

function toggleCalc(){
    $('#calcContainer').toggle();
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