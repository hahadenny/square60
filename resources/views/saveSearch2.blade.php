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
            <div id="app" class="container">
                <ul class="menu-list">
                    <li>
                        <a href="#" class="print" onclick="window.print()">Print</a>
                    </li>
                    <li>
                        <a href="#" class="email" id="email-topline" @click="showModal = true">E-mail</a>
                        @if (Auth::guest())
                        <div class="wrap-modal">
                            <modal v-if="showModal" @close="showModal = false">
                                <div slot="header">
                                    <label class="label">Enter email</label>
                                </div>
                                <div slot="body">
                                    <sendresults inline-template v-bind:searchid="{{$id}}">
                                        <div>
                                            <div class="field">
                                                <div class="control has-icons-left ">
                                                    <input v-model="guestEmail" class="input" id="email" type="email" name="email" value="" required autofocus>
                                                    <span class="icon is-small is-left"><i class="fa fa-envelope"></i></span>
                                                </div>
                                            </div>
                                            <button class="sendButton button is-primary" type="button" v-on:click="setPost">Send</button>
                                        </div>
                                    </sendresults>
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
                                    <sendresults inline-template v-bind:searchid="{{$id}}" v-bind:email="'{{Auth::user()->email}}'">
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
                                    </sendresults>
                                </div>
                                <div slot="footer"></div>
                            </modal>

                            <modal v-if="saveModal" @close="saveModal = false">
                                <div slot="header"></div>
                                <div slot="body" class="has-text-centered">
                                    <save inline-template v-bind:search="{{$id}}"  v-bind:ids="'{{$listingIds}}'" v-bind:user="'{{Auth::user()->id}}'" v-bind:type="{{$type}}">
                                        <div>
                                            <div class="field">
                                                <div class="control has-text-centered">
                                                    <div class="select is-small">
                                                        <select v-model="saveId">
                                                            <option value="">-- Save to existing search --</option>
                                                            <option v-for="item in data" :value="item.id">
                                                                @{{item.title}}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="control">
                                                    <a v-on:click="seen = !seen" style="color: #3273dc; cursor:pointer;"> Add New Search Title</a>
                                                </div>
                                            </div>


                                                <div class="field" v-if="seen">
                                                    <div class="control has-text-centered">
                                                        <input type="text" class="input" name="title" v-model="title">
                                                    </div>
                                                </div>

                                            <a class="button is-primary " id="search-toline" v-on:click="setPost">
                                                <span>Save</span></a><br><br>

                                        </div>
                                    </save>
                                </div>
                                <div slot="footer"></div>
                            </modal>
                        </div>
                        @endif
                    </li>
                    <li>
                    @if (Auth::guest())
                        <a href="{{route('login')}}" class="save" id="search-toline">Save Search</a>
                    @else
                        <a class="save" id="search-toline" @click="saveModal = true" style="cursor:pointer;">Save</a>
                    @endif
                    </li>
                    <li class="width_sumbenu advance">
                        <div class="item-ttl">Advance Search
                            <div class="sub-menu">
                                <form method="post" action="/search">

                                    @if(isset($searchData))
                                        @if (isset($searchData->estate_type))
                                                <input type="hidden" name="estate_type" value="{{$searchData->estate_type}}">
                                        @endif
                                        @if(isset($searchData->districts))
                                            @foreach($searchData->districts as $item)
                                                <input name="districts[]" type="hidden" value="{{$item}}" >
                                            @endforeach
                                        @endif
                                    @endif
                                    @if(isset($searchData))
                                        @if(isset($searchData->types))
                                            @foreach($searchData->types as $item)
                                                <input name="types[]" type="hidden" value="{{$item}}" >
                                            @endforeach
                                        @endif
                                    @endif
                                    @if(isset($searchData))
                                        @if(isset($searchData->beds))
                                            @foreach($searchData->beds as $item)
                                                <input name="beds[]" type="hidden" value="{{$item}}" >
                                            @endforeach
                                        @endif
                                    @endif
                                    @if(isset($searchData))
                                        @if(isset($searchData->baths))
                                            @foreach($searchData->baths as $item)
                                                <input name="baths[]" type="hidden" value="{{$item}}" >
                                            @endforeach
                                        @endif
                                    @endif
                                    @if(isset($searchData))
                                        @if(isset($searchData->price))
                                            @foreach($searchData->price as $item)
                                                <input name="price[]" type="hidden" value="{{$item}}" >
                                            @endforeach
                                        @endif
                                    @endif

                                    <ul class="list">
                                        @foreach ( $filters as $k=>$filter)
                                          <li><input name="filters[]" type="checkbox" class="checkbox" id="{{$filter->filter_data_id}}" value="{{$filter->filter_data_id}}"
                                                   @if(isset($searchData) && isset($searchData->filters) && is_array($searchData->filters) && in_array($filter->filter_data_id, $searchData->filters))
                                                        checked
                                                   @endif
                                                  ><label for="{{$filter->filter_data_id}}">{{$filter->value}}</label></li>
                                        @endforeach
                                    </ul>
                                    {{csrf_field()}}
                                    <button id="search_main" class="btn"><div style="width:100%;text-align:center;">Search</div></button>
                                </form>
                            </div>
                        </div>
                    </li>
                    <li class="width_sumbenu sort">
                        <div class="item-ttl" style="@if(!$count)display:none; @else display:flex;@endif">Sort By{{--<br><span style="font-size:14px;color:red;">@if($sort == 'newest') Newest @elseif($sort == 'oldest') Oldest @elseif($sort == 'lowest') Lowest Price @elseif($sort == 'highest') Highest Price @endif</span>--}}
                            <ul class="sub-menu">
                                <li>
                                    <a href="/search/{{$id}}/newest" @if ($sort == 'newest') class="active" @endif>Newest</a>
                                </li>
                                <li>
                                    <a href="/search/{{$id}}/oldest" @if ($sort == 'oldest') class="active" @endif>Oldest</a>
                                </li>
                                <li>
                                    <a href="/search/{{$id}}/lowest" @if ($sort == 'lowest') class="active" @endif>Lowest Price</a>
                                </li>
                                <li>
                                    <a href="/search/{{$id}}/highest" @if ($sort == 'highest') class="active" @endif>Highest Price</a>
                                </li>
                            </ul>                            
                        </div>
                    </li>
                </ul>
                <div class="search-form">
                    @include('partial.search')
                    <form method="post" class="is-hidden" action="/search">
                        @if(isset($searchData))
                            @if (isset($searchData->estate_type))
                                    <input type="hidden" name="estate_type" value="{{$searchData->estate_type}}">
                            @endif
                            @if(isset($searchData->districts))
                                @foreach($searchData->districts as $item)
                                    <input name="districts[]" type="hidden" value="{{$item}}" >
                                @endforeach
                            @endif
                        @endif
                        @if(isset($searchData))
                            @if(isset($searchData->types))
                                @foreach($searchData->types as $item)
                                    <input name="types[]" type="hidden" value="{{$item}}" >
                                @endforeach
                            @endif
                        @endif
                        @if(isset($searchData))
                            @if(isset($searchData->beds))
                                @foreach($searchData->beds as $item)
                                    <input name="beds[]" type="hidden" value="{{$item}}" >
                                @endforeach
                            @endif
                        @endif
                        @if(isset($searchData))
                            @if(isset($searchData->baths))
                                @foreach($searchData->baths as $item)
                                    <input name="baths[]" type="hidden" value="{{$item}}" >
                                @endforeach
                            @endif
                        @endif
                        @if(isset($searchData))
                            @if(isset($searchData->price))
                                @foreach($searchData->price as $item)
                                    <input name="price[]" type="hidden" value="{{$item}}" >
                                @endforeach
                            @endif
                        @endif
                        @foreach ( $filters as $k=>$filter)
                            <input name="filters[]" type="checkbox" class="checkbox" id="{{$filter->filter_data_id}}" value="{{$filter->filter_data_id}}" style="display:none;"
                                @if(isset($searchData) && isset($searchData->filters) && is_array($searchData->filters) && in_array($filter->filter_data_id, $searchData->filters))
                                    checked
                                @endif
                            >
                        @endforeach
                        {{csrf_field()}}
                        <input name="svalue" placeholder="Search" type="search">
                        <button>
                            <img src="/images/ico-search.svg" alt="">
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="breadcrumbs">
            <div class="container">
                <ul>
                    <li>
                        <a href="javascript:void(0);" onclick="goBack();">Previous Page</a>
                    </li>
                    <li>
                        <span>Search Result</span>
                    </li>
                </ul>
            </div>
        </div>

        <div id="app2" class="search-result">        
            <div class="container">

                <div id="searches" class="content" style="margin-bottom:25px;margin-top:10px;">
                    <div class="content" style="margin-bottom:10px;">
                        <h3 class="title is-4">Saved Searches</h3>
                    </div>
                    <div class="ui form">
                        <div class="inline fields">
                            <div class="field">
                                <form method="POST" action="{{ route('deleteSearch') }}" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                <div class="select" style="margin-right:5px;margin-bottom:10px;">
                                    <select name="search" onchange="handleSelect(this)">
                                        @foreach(Auth::user()->getUserResult() as $item)
                                                @if ($id == $item->search_id)
                                                    <option value="{{$item->search_id}}" selected>{{$item->title}}</option>
                                                @else
                                                    <option value="{{$item->search_id}}">{{$item->title}}</option>
                                                @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="delete_btn">
                                    <input type="submit" class="button is-primary" style="background-color:#3e65a9;font-size:15px;" value="Delete Search" onclick="return confirm('Are you sure to delete this item?');">
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                @if($count)
                    @if($type==2)
                    <h1 class="page-ttl" id="listing-search-result">{{$count}} APARTMENTS FOR RENT</h1>
                    @else
                    <h1 class="page-ttl" id="listing-search-result">{{$count}} APARTMENTS FOR SELLING</h1>
                    @endif
                @else
                <h1 class="page-ttl" id="listing-search-result">NOTHING FOUND</h1>
                @endif
                <div class="result-list">
                    <ul>
                        @if(isset($features) && !$features->isEmpty())
                            @foreach ($features as $key => $feature)
                                @if($feature->active == 1)   
                        <li class="item" v-on:mouseenter="changeEstateId('feature', {{$key}})">
                            <div class="bg-item" style="background-image: @if($feature->img) url({{$feature->img}}); @else url(/images/default_image_coming.jpg); @endif">
                                <div class="is-uppercase featured has-text-centered">featured</div>
                                @if($feature->estate_type == 2 && $feature->fees == 0)
                                    <div class="is-uppercase nofee has-text-centered">no fee</div>
                                @endif
                            </div>
                            <div class="text-wr">
                                <a href="/show/{{str_replace(' ','_',$feature->name)}}/{{$feature->id}}" class="item-name" style="font-size:16px;">{{$feature->full_address}} {{$feature->unit}}</a>
                                <div class="item-place" style="font-size:12px;">{{$feature->neighborhood}}</div>
                                <div class="item-price">
                                    <span class="price" style="margin-top:7px">$ {{$feature->price}}@if($feature->estate_type=='1')<br><span style="font-size:12px">($ {{$feature->monthly_cost}}/monthly)</span>@endif</span>
                                    <div class="name-desc">
                                        <div class="agent">{{$feature->unit_type}}</div>
                                        {{--<div class="additional">{{$feature->estate_type == 2 && $feature->fees == 0 ? 'No Fee' : ''}}</div>--}}
                                    </div>
                                </div>
                                <ul class="benefits">
                                    <li>
                                        <span class="bed">{{$feature->beds}} beds</span>
                                    </li>
                                    <li>
                                        <span class="bath">{{$feature->baths}} bath</span>
                                    </li>
                                    <li>
                                        <span {{--class="ft"--}} style="padding-left:5px;">{{$feature->sq_feet}} ft <sup>2</sup></span>
                                    </li>
                                </ul>
                                <div class="item-time">
                                    <div class="time">
                                    @if(isset($feature->OpenHouse) && !$feature->OpenHouse->isEmpty())
                                        @foreach($feature->OpenHouse as $key=>$item)
                                            @if(Carbon\Carbon::now() > $item->end_time)
                                                @php unset($feature->OpenHouse[$key]) @endphp
                                            @endif
                                        @endforeach
                                        @if(count($feature->OpenHouse))
                                        <div style="padding-bottom:5px;">Open House:</div>
                                        @endif
                                        @foreach($feature->OpenHouse as $key=>$item)
                                            <b>{{Carbon\Carbon::parse($item->date)->format('D M j')}}</b>&nbsp;
                                            @if($item->appointment)
                                                <b>By Appointment</b>
                                            @else
                                                {{Carbon\Carbon::parse($item->start_time)->format('g:i A')}} -
                                                {{Carbon\Carbon::parse($item->end_time)->format('g:i A')}}
                                            @endif
                                            <br>
                                        @endforeach
                                    @endif
                                    </div>
                                    @if ($feature->path_to_logo)
                                        <img src="{{$feature->path_to_logo}}" alt="" class="item-logo" style="max-width:150px;max-height:50px;">
                                    @elseif ($feature->agent_company)
                                        <div class="item-logo" style="padding-bottom:10px;">{{ucwords($feature->agent_company)}}</div>
                                    @endif
                                </div>
                            </div>
                        </li>
                                @endif
                            @endforeach
                        @endif

                        @foreach ($results as $key => $result)
                            @if($result->active == 1)
                        <li class="item" v-on:mouseenter="changeEstateId('result', {{$key}})">
                            <div class="bg-item" style="background-image: @if ($result->img) url({{$result->img}}); @else url(/images/default_image_coming.jpg); @endif">
                                @if($result->premium)
                                    <div class="is-uppercase premium has-text-centered">premium</div>
                                @endif
                                @if($result->estate_type == 2 && $result->fees == 0)
                                    <div class="is-uppercase nofee has-text-centered">no fee</div>
                                @endif
                            </div>
                            <div class="text-wr">
                                <a href="/show/{{str_replace(' ','_',$result->name)}}/{{$result->id}}" class="item-name" style="font-size:16px;">{{$result->full_address}} {{$result->unit}}</a>
                                <div class="item-place" style="font-size:12px;">{{$result->neighborhood}}</div>
                                <div class="item-price">
                                    <span class="price" style="margin-top:7px">$ {{$result->price}}@if($result->estate_type=='1')<br><span style="font-size:12px">($ {{$result->monthly_cost}}/monthly)</span>@endif</span>
                                    <div class="name-desc">
                                        <div class="agent">{{$result->unit_type}}</div>
                                        {{--<div class="additional">{{$result->estate_type == 2 && $result->fees == 0 ? 'No Fee' : ''}}</div>--}}
                                    </div>
                                </div>
                                <ul class="benefits">
                                    <li>
                                        <span class="bed">{{$result->beds}} beds</span>
                                    </li>
                                    <li>
                                        <span class="bath">{{$result->baths}} bath</span>
                                    </li>
                                    <li>
                                        <span {{--class="ft"--}} style="padding-left:5px;">{{$result->sq_feet}} ft <sup>2</sup></span>
                                    </li>
                                </ul>
                                <div class="item-time">
                                    <div class="time">                        
                                    @if(isset($result->OpenHouse) && !$result->OpenHouse->isEmpty())
                                        @foreach($result->OpenHouse as $key=>$item)
                                            @if(Carbon\Carbon::now() > $item->end_time)
                                                @php unset($result->OpenHouse[$key]) @endphp
                                            @endif
                                        @endforeach
                                        @if(count($result->OpenHouse))
                                        <div style="padding-bottom:5px;">Open House:</div>
                                        @endif
                                        @foreach($result->OpenHouse as $key=>$item)
                                            <b>{{Carbon\Carbon::parse($item->date)->format('D M j')}}</b>&nbsp;
                                            @if($item->appointment)
                                                <b>By Appointment</b>
                                            @else
                                                {{Carbon\Carbon::parse($item->start_time)->format('g:i A')}} -
                                                {{Carbon\Carbon::parse($item->end_time)->format('g:i A')}}
                                            @endif
                                            <br>
                                        @endforeach
                                    @endif
                                    </div>
                                    @if ($result->path_to_logo)
                                        <img src="{{$result->path_to_logo}}" alt="" class="item-logo" style="max-width:150px;max-height:50px;">
                                    @elseif ($result->agent_company)
                                        <div class="item-logo" style="padding-bottom:10px;">{{ucwords($result->agent_company)}}</div>
                                    @endif
                                </div>
                            </div>
                        </li>
                            @endif
                        @endforeach                       
                    </ul>
                </div>
                <div class="result-sidebar hidden-print">
                    <div>
                        <maps inline-template name="result" v-bind:id="estateMap.id" v-bind:type="estateMap.type" v-bind:features="{{ isset($features) ? json_encode($features) : '' }}" v-bind:results="{{ isset($results) ? json_encode($results) : '' }}" v-bind:estate="{{ (isset($features) && !$features->isEmpty()) ? $features[0] : (isset($results) && !$results->isEmpty()) ? $results[0] : '' }}">
                            <div id="div_map">
                                <div v-bind:id="mapName" class="google-map"></div>
                            </div>
                        </maps>
                    </div>
                </div>
                <nav class="pagination is-centered" role="navigation" aria-label="pagination">
                {{$results->links()}}
                </nav>
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
    function sortresults(e){
        document.location.replace('/saved-searches/{{$id}}/'+ e.value);
    }

    function showModalListing(id){
        $('#modal_listings_email').show();
    }

    function handleSelect(elm)
    {
        window.location = '/saved-searches/'+elm.value;
    }

    @if(session('searchData'))
    var searchData = {!! session('searchData') !!};
    @else
    var searchData = null;
    @endif

    //console.log(searchData);

    function goBack() {
        var path = window.location.protocol+'//'+window.location.hostname;

        if ($.inArray(document.referrer, [path+'/', path+'/rentals', path+'/sales']) != -1) {
            //alert(document.referrer);
            window.location.href = document.referrer;		
        }
        else {
            if ($.inArray(document.referrer, [path+'/savesearch']) != -1 && searchData) {
                if (searchData.estate_type && searchData.estate_type==2)
                    window.location.href = path+'/rentals';
                else
                    window.location.href = path+'/sales';
            }
            else
                history.go(-1);
        }

        return false;
    }

    $(function(){

        if (!!$('.result-sidebar').offset()) {

        var stickyTop = $('.search-result').offset().top;

        $(window).scroll(function(){
            var windowTop = $(window).scrollTop();

            if (stickyTop < windowTop){
                $('.result-sidebar > div').addClass('is-sticky');
            }
            else {
                $('.result-sidebar > div').removeClass('is-sticky');
            }

        });

    }

    });
</script>
@endsection