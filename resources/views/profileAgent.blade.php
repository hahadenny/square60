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
            <section>
                <div class="main-content columns is-mobile is-centered" style="margin-bottom:40px;">
                    <div class="column is-9 is-centered agentpage" style="padding:0px;">

                        <div class="panel-body is-clearfix listing">
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <div class="column is-8-desktop is-pulled-left" style="padding-right: 20px">
                                <article class="media">
                                    <figure class="media-left">
                                        <div style="margin: 0px 20px 10px 0px;">
                                            @if (isset($agent->img))
                                                <p class="image is-128x128 agent-image" style="overflow: hidden;border-radius: 50%;background-image: url(@if (isset($agent->img) && $agent->img){{$agent->img}}@else /images/default_agent.jpg @endif);background-size:cover;background-position:top center;">
                                                    {{--<img src="{{$agent->img}}">--}}
                                                </p>
                                            @endif
                                        </div>
                                    </figure>
                                    <div class="media-content">
                                        <div class="content">
                                            <p>
                                                <strong>{{$agent->full_name}}</strong>
                                                <br />
                                                @if ($agent->path_to_logo)
                                                    @if ($agent->web_site)
                                                        <a href="{{$agent->web_site}}" target="_blank"><img src="{{$agent->path_to_logo}}" alt="" style="max-width:150px;max-height:50px;margin-top:10px;"></a>
                                                    @else
                                                        <img src="{{$agent->path_to_logo}}" alt="" style="max-width:150px;max-height:50px;margin-top:10px;">
                                                    @endif
                                                @elseif(isset($agent->company))
                                                    @if ($agent->web_site)
                                                        <a href="{{$agent->web_site}}" target="_blank">{{ucwords($agent->company)}}</a>
                                                    @else
                                                        {{ucwords($agent->company)}}
                                                    @endif
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </article>
                                <div class="bio" style="margin-top:10px;">
                                    {!! strip_tags($agent->description) !!}
                                </div>

                                <div class="sale-listing" style="margin-top: 20px;">
                                    @if(!empty($estates['sales']))
                                        <h3 class="has-text-weight-bold">
                                            <span>{{count($estates['sales'])}}</span>
                                            <span>
                                                {{ count($estates['sales']) > 1 ? "Sales" : "Sale" }}
                                            </span>
                                        </h3>
                                        <hr class="large" />

                                        @foreach ($estates['sales'] as $result)
                                            @if($result->active == 1)
                                                <div class="">
                                                    <div class="columns box-listing">
                                                        <div class="column is-4">

                                                            @if ($result->img)
                                                                <img src="{{$result->img}}" alt="" style="width:229px;">
                                                            @else
                                                                <img src="/images/default_image_coming.jpg" alt="" style="width:229px;">
                                                            @endif

                                                        </div>

                                                        <div class="column">
                                                            <div class="level" style="margin-bottom:10px;">
                                                                <a href="/show/{{str_replace(' ','_',$result->name)}}/{{$result->id}}"><h4 class="is-6 main-color a"><b>{{$result->full_address}} {{$result->unit}}</b> &nbsp;<div style="margin-top:5px;">{{$result->neighborhood}}</div></h4></a>
                                                                <span class="is-danger title is-6">{{$result->unit_type}}<div style="margin-top:10px;">{{$result->estate_type == 2 && $result->fees == 0 ? 'No Fee' : ''}}</div></span>
                                                            </div>

                                                            <div class="content">
                                                                <span id="price-toline" class="title is-6" style="margin-bottom:0px;">$ {{$result->price}}</span>
                                                                @if ($result->estate_type==2 && $result->monthly_cost)<span>$ {{$result->monthly_cost}}/monthly </span>@endif

                                                                <div id="listing-ads" class="">
                                                                    <ul class="level-left is-mobile" id="listing-ad-ul-type" style="max-width:100%;margin-top:8px;">
                                                                        <li id="listing-ad-bed">{{$result->beds}} beds | {{$result->baths}} baths | {{$result->sq_feet}} ft<sup>2</sup></li>
                                                                        {{--<li id="listing-ad-bath">{{$result->baths}} baths|</li>
                                                                        <li id="listing-ad-ft">{{$result->sq_feet}} ft<sup>2</sup></li>--}}
                                                                    </ul>
                                                                </div>

                                                                <div class="listing-ad-type" style="margin-top:8px;">
                                                                    @if ($result->agent_company)
                                                                        {{ucwords($result->agent_company)}}
                                                                    @endif
                                                                    <br>
                                                                    @if ($result->path_to_logo)
                                                                        <span class="company_logo"><img style="max-width:150px;max-height:50px;" src="{{$result->path_to_logo}}"></span>
                                                                    @endif
                                                                </div>

                                                                @if(isset($result->OpenHouse) && !$result->OpenHouse->isEmpty())
                                                                    @foreach($result->OpenHouse as $key=>$item)
                                                                        @if(Carbon\Carbon::now() > $item->end_time)
                                                                            @php unset($result->OpenHouse[$key]) @endphp
                                                                        @endif
                                                                    @endforeach
                                                                    @if(count($result->OpenHouse))
                                                                    <div style="padding-bottom:5px;padding-top:0px;">Open House:</div>
                                                                    @endif
                                                                    @foreach($result->OpenHouse as $key=>$item)
                                                                        <div class="listing-ad-open-house is-pulled-left" style="padding-left:0px;">
                                                                        <span class="button" style="border:none;padding-left:0px;">{{Carbon\Carbon::parse($item->date)->format('D M j')}}
                                                                    @if($item->appointment)
                                                                        <b>by appointment</b>
                                                                    @else
                                                                        {{Carbon\Carbon::parse($item->start_time)->format('g:i A')}} -
                                                                        {{Carbon\Carbon::parse($item->start_end)->format('g:i A')}}
                                                                     @endif</span>
                                                                        </div>
                                                                    @endforeach
                                                                @endif

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr />
                                            @endif
                                        @endforeach
                                    @endif
                                </div>

                                <div class="rental-listing">
                                    @if(!empty($estates['rentals']))
                                        <h3 class="has-text-weight-bold">
                                            <span>{{count($estates['rentals'])}}</span>
                                            <span>
                                                {{ count($estates['rentals']) > 1 ? "Rentals" : "Rental" }}
                                            </span>
                                        </h3>
                                        <hr class="large" />

                                        @foreach ($estates['rentals'] as $result)
                                            @if($result->active == 1)
                                                <div class="">
                                                    <div class="columns box-listing">
                                                        <div class="column is-4">

                                                            @if ($result->img)
                                                                <img src="{{$result->img}}" alt="" style="width:229px;">
                                                            @else
                                                                <img src="/images/default_image_coming.jpg" alt="" style="width:229px;">                                                                
                                                            @endif

                                                        </div>

                                                        <div class="column">
                                                            <div class="level" style="margin-bottom:10px;">
                                                                <a style="padding-right:10px;" href="/show/{{str_replace(' ','_',$result->name)}}/{{$result->id}}"><h4 class="is-6 main-color a"><b>{{$result->full_address}} {{$result->unit}}</b> &nbsp;<div style="margin-top:5px;">{{$result->neighborhood}}</div></h4></a>
                                                                <span class="is-danger title is-6">{{$result->unit_type}}<div style="margin-top:8px;">{{$result->estate_type == 2 && $result->fees == 0 ? 'No Fee' : ''}}</div></span>
                                                            </div>

                                                            <div class="content">
                                                                <div id="price-toline" class="title is-6" style="margin-bottom:0px;">$ {{$result->price}}</div>
                                                                @if ($result->estate_type==2 && $result->monthly_cost)<span>$ {{$result->monthly_cost}}/monthly </span>@endif

                                                                <div id="listing-ads" class="">
                                                                    <ul class="level-left is-mobile" id="listing-ad-ul-type" style="max-width:100%; margin-top:8px;">
                                                                        <li id="listing-ad-bed">{{$result->beds}} beds | {{$result->baths}} baths | {{$result->sq_feet}} ft<sup>2</sup></li>
                                                                        {{--<li id="listing-ad-bath">{{$result->baths}} baths|</li>
                                                                        <li id="listing-ad-ft">{{$result->sq_feet}} ft<sup>2</sup></li>--}}
                                                                    </ul>
                                                                </div>

                                                                <div class="listing-ad-type" style="margin-top:8px;">
                                                                    @if ($result->agent_company)
                                                                        {{ucwords($result->agent_company)}}
                                                                    @endif
                                                                    <br>
                                                                    @if ($result->path_to_logo)
                                                                        <span class="company_logo"><img style="max-width:150px;max-height:50px;" src="{{$result->path_to_logo}}"></span>
                                                                    @endif
                                                                </div>

                                                                @if(isset($result->OpenHouse) && !$result->OpenHouse->isEmpty())
                                                                    @foreach($result->OpenHouse as $key=>$item)
                                                                        @if(Carbon\Carbon::now() > $item->end_time)
                                                                            @php unset($result->OpenHouse[$key]) @endphp
                                                                        @endif
                                                                    @endforeach
                                                                    @if(count($result->OpenHouse))
                                                                    <div style="padding-bottom:5px;padding-top:0px;">Open House:</div>
                                                                    @endif
                                                                    @foreach($result->OpenHouse as $key=>$item)
                                                                        <div class="listing-ad-open-house is-pulled-left" style="padding-left:0px;">
                                                                        <span class="button" style="border:none;padding-left:0px;">{{Carbon\Carbon::parse($item->date)->format('D M j')}}
                                                                    @if($item->appointment)
                                                                        <b>by appointment</b>
                                                                    @else
                                                                        {{Carbon\Carbon::parse($item->start_time)->format('g:i A')}} -
                                                                        {{Carbon\Carbon::parse($item->start_end)->format('g:i A')}}
                                                                     @endif</span>
                                                                        </div>
                                                                    @endforeach
                                                                @endif

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr />
                                            @endif
                                        @endforeach
                                    @endif
                                </div>

                            </div>

                            <div class="sidebar column is-4-desktop is-pulled-left contact-box hidden-print" style="border:none;">

                                <div class="sidebar-ttl" style="font-size:15px;">
                                    <h3 class="has-text-weight-bold">Contact {{$agent->full_name}}</h3>
                                    @if ($agent->path_to_logo)
                                        @if ($agent->web_site)
                                            <a href="{{$agent->web_site}}" target="_blank"><img src="{{$agent->path_to_logo}}" alt="" style="max-width:150px;max-height:50px;margin-top:10px;margin-bottom:8px;"></a>
                                        @else
                                            <img src="{{$agent->path_to_logo}}" alt="" style="max-width:150px;max-height:50px;margin-top:10px;margin-bottom:8px;">
                                        @endif
                                    @elseif(isset($agent->company))
                                        <p>
                                            @if ($agent->web_site)
                                                <a href="{{$agent->web_site}}" target="_blank">{{ucwords($agent->company)}}</a>
                                            @else
                                                {{ucwords($agent->company)}}
                                            @endif
                                        </p>
                                    @endif
                                    @if (isset($agent->office_phone))
                                        <p style="margin-top:0px;">
                                            <span>Call:</span>
                                            <span>{{$agent->office_phone}}</span>
                                        </p>
                                    @endif
                                </div>
                                <div class="contact-form">
                                    <send inline-template v-bind:type="'regular'" v-bind:agentemail="'{{$agent->email}}'">
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
                                </div>

                                <div>                                    

                                    {{--
                                    <form id="contact_agent" method="post" action="/contact-agent">
                                        <send inline-template v-bind:type="'regular'" v-bind:agentemail="'{{$agent->email}}'">
                                            <div>
                                                <div class="control has-icons-left field">
                                                    <input v-model="phone" type="tel" name="phone" class="input" value="" placeholder="Phone Number" />
                                                    <span class="icon is-small is-left">
                                                    <i class="fa fa-phone"></i>
                                                </span>
                                                </div>
                                                <div class="control has-icons-left field">
                                                    <input v-model="useremail" class="input" type="email" placeholder="Email" name="email" value="" required />
                                                    <span class="icon is-small is-left">
                                                    <i class="fa fa-envelope"></i>
                                                </span>
                                                </div>
                                                <div class="control has-icons-left field">
                                                <textarea v-model="message" name="message" rows="5" class="textarea" placeholder="Add note...">
                                                </textarea>
                                                </div>

                                                <input name="agent_id" value="{{$agent->id}}" type="hidden" />
                                                <input v-model="agentemail" name="agent_email" value="{{$agent->email}}" type="hidden" />
                                                {{csrf_field()}}
                                                <button type="button" v-on:click="setPost" class="button is-info" style="background-color:#3e65a9;">Send Message</button>
                                            </div>
                                        </send>
                                    </form>      
                                    <div id="messageResponse" class="has-text-centered" style="margin-bottom:20px;"></div>
                                    --}}

                                </div>

                                {{--
                                <div>
                                    <a href="#" onclick="print(); return false;"><i class="fa fa-print"></i>&nbsp;Print</a>
                                </div>
                                <div style="margin-top:5px;">
                                    <a href="#"><i class="fa fa-facebook"></i>&nbsp;Share on Facebook</a>
                                </div>
                                --}}

                            </div>

                        </div>
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
    <script>
        function isNumberKey(evt){
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        }
    </script>
@endsection