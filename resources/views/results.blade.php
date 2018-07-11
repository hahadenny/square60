@extends('layouts.app')

@section('header')
    <header>
        @include('layouts.header')
    </header>
@endsection

@section('content')
    <div id="app">
        <div>

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
                                            <input v-model="guestEmail" class="input" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
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
                                                    <option value="">--Save to existing search--</option>
                                                    <option v-for="item in data" :value="item.id">
                                                        @{{item.title}}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="control">
                                            <a v-on:click="seen = !seen"> Add New Search Title</a>
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

            <section id="filters" class=>
                <div class="container">
                    <div class="columns">
                        <div class="column is-8">
                            <ul class="level is-mobile" id="topline">
                                <li><a href="#" class="" id="print-topline" onclick="window.print()"> <span>Print</span></a>
                                    <span class="icon"><i class="fa fa-print"></i></span></li>
                                <li><a class="" id="email-topline" @click="showModal = true"><span>Email</span></a>
                                    <span class="icon"><i class="fa fa-envelope-o"></i></span>
                                </li>

                                <li>
                                    @if (Auth::guest())
                                        <a href="{{route('login')}}" class="" id="search-toline">
                                            <span>Save seach</span></a><span class="icon">
                                    <i class="fa fa-folder-open-o"></i></span>
                                    @else
                                        <a class="" id="search-toline" @click="saveModal = true">
                                            <span>Save</span></a><span class="icon">
                                                            <i class="fa fa-folder-open-o"></i></span>
                                    @endif

                                </li>

                                <li><a class="" id="advance-topline" onclick="toggleSearch()"><span>Advance Search</span></a>
                                    <span class="icon"><i class="fa fa-sort-amount-asc"></i></span>
                                </li>
                                <div class="">
                                    <div >
                                        @if($count == 0)
                                            <select class="select-cust">
                                        @else
                                            <select class="select-cust" onchange="return sortresults(this);">
                                        @endif
                                            <option value="0">Sort by</option>
                                            <option value="newest" @if ($sort == 'newest') selected="selected" @endif>Newest listing</option>
                                            <option value="oldest" @if ($sort == 'oldest') selected="selected" @endif>Oldest listing</option>
                                            <option value="lowest" @if ($sort == 'lowest') selected="selected" @endif>Lowest price</option>
                                            <option value="highest" @if ($sort == 'highest') selected="selected" @endif>Highest price</option>
                                        <!-- <option value="popular" @if ($sort == 'popular') selected="selected" @endif>Popular</option> -->
                                        </select>
                                    </div>
                                </div>

                            </ul>

                        </div>

                        <div class="column is-4">
                            @include('partial.search')
                        </div>
                    </div>
                </div>
            </section>
                <div id="search" style="display: none">
                    <form method="post" action="/search">

                        <div class="box column is-3 is-offset-4 is-mobile">

                              <div class="columns is-gapless">


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


                                 <div class="fix checkline">
                                      <ul class="filters_list">
                                        @foreach ( $filters as $k=>$filter)
                                          <li><label for="{{$filter->filter_data_id}}"><input name="filters[]" type="checkbox" id="{{$filter->filter_data_id}}" value="{{$filter->filter_data_id}}"
                                                   @if(isset($searchData) && isset($searchData->filters) && is_array($searchData->filters) && in_array($filter->filter_data_id, $searchData->filters))
                                                        checked
                                                   @endif
                                                  >{{$filter->value}}</label></li>
                                        @endforeach
                                      </ul>
                                     <button class="button is-info" id="search_main">Search</button>
                                 </div>
                            </div>
                        </div>
                        {{csrf_field()}}
                    </form>
                </div>

            <section id="top-search">
                <div class="container">


                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a id="go-back" href="javascript:void(0);" onclick="goBack();">Previous page</a></li>
                        <li class="breadcrumb-item active"><span>Search Results</span></li>
                    </ol>

                    <div id="messageResponse" class="has-text-centered"></div>



                    <div class="columns">

                        <div class="column is-8">

                            <div class="content">
                                @if($count)
                                    @if($type==2)
                                    <h3 class="title is-4" id="listing-search-result">{{$count}} apartments for rent</h3>

                                    @else
                                     <h3 class="title is-4" id="listing-search-result">{{$count}} apartments for selling</h3>
                                    @endif

                                @else
                                    <h3 class="title is-4" id="listing-search-result">Nothing found</h3>
                                @endif
                            </div>

                            @if(isset($features) && !$features->isEmpty())
                            @foreach ( $features as $key => $feature)
                                @if($feature->active == 1)
                                    <div class="" v-on:mouseenter="changeEstateId('feature', {{$key}})">
                                        <div class="columns box-listing">
                                            <div class="column is-4">

                                                @if ($feature->img)
                                                    <img src="{{$feature->img}}" alt="">
                                                    <div class="is-uppercase featured has-text-centered">featured</div>
                                                @else
                                                    <img src="http://squareyard360.com/images/default_image_coming.jpg" alt="img2">
                                                    No photo for this listing
                                                @endif

                                            </div>

                                            <div class="column">
                                                <div class="level">
                                                    <a href="/show/{{str_replace(' ','_',$feature->name)}}/{{$feature->id}}"><h4 class="is-6 main-color a">{{$feature->full_address}} {{$feature->unit}} <br>{{$feature->neighborhood}}</h4></a>
                                                    <span class="is-danger title is-6">{{$feature->unit_type}}<br><br>{{$feature->estate_type == 2 && $feature->fees == 0 ? 'NoFee' : ''}}</span>
                                                </div>

                                                <div class="content">
                                                    <span id="price-toline" class="title is-6">$ {{$feature->price}} @if ($feature->estate_type==2) @endif</span>
                                                    <span>$ {{$feature->monthly_cost}}/monthly </span>

                                                    <div id="listing-ads" class="">
                                                        <ul class="level-left is-mobile" id="listing-ad-ul-type">
                                                            <li id="listing-ad-bed">{{$feature->beds}} beds|</li>
                                                            <li id="listing-ad-bath">{{$feature->baths}} baths|</li>
                                                            <li id="listing-ad-ft">{{$feature->sq_feet}} ft<sup>2</sup></li>
                                                        </ul>
                                                    </div>

                                                    <div class="listing-ad-type">
                                                        @if ($feature->agent_company)
                                                            {{$feature->agent_company}}
                                                        @endif
                                                        <br>
                                                        @if ($feature->path_to_logo)
                                                            <span class="company_logo"><img style="max-width:150px;" src="{{$feature->path_to_logo}}"></span>
                                                        @endif
                                                    </div>

                                                @if(isset($feature->OpenHouse) && !$feature->OpenHouse->isEmpty())
                                                    @foreach($feature->OpenHouse as $key=>$item)

                                                    <div class="listing-ad-open-house is-pulled-right">
                                                          <span class="button">Open House: {{Carbon\Carbon::parse($item->date)->format('D M j')}}
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
                                @endif
                            @endforeach
                            @endif

                            @foreach ( $results as $key => $result)
                                @if($result->active == 1)
                                    <div class="" v-on:mouseenter="changeEstateId('result', {{$key}})">
                                        <div class="columns box-listing">
                                            <div class="column is-4">

                                                @if ($result->img)
                                                    <img src="{{$result->img}}" alt="">
                                                @else
                                                    <img src="http://squareyard360.com/images/default_image_coming.jpg" alt="img2">
                                                    No photo for this listing
                                                @endif

                                            </div>

                                            <div class="column">
                                                <div class="level">
                                                    <a href="/show/{{str_replace(' ','_',$result->name)}}/{{$result->id}}"><h4 class="is-6 main-color a">{{$result->full_address}} {{$result->unit}} <br>{{$result->neighborhood}}</h4></a>
                                                    <span class="is-danger title is-6">{{$result->unit_type}}<br><br>{{$result->estate_type == 2 && $result->fees == 0 ? 'NoFee' : ''}}</span>
                                                </div>

                                                <div class="content">
                                                    <span id="price-toline" class="title is-6">$ {{$result->price}} @if ($result->estate_type==2) @endif</span>
                                                    <span>$ {{$result->monthly_cost}}/monthly </span>

                                                    <div id="listing-ads" class="">
                                                        <ul class="level-left is-mobile" id="listing-ad-ul-type">
                                                            <li id="listing-ad-bed">{{$result->beds}} beds|</li>
                                                            <li id="listing-ad-bath">{{$result->baths}} baths|</li>
                                                            <li id="listing-ad-ft">{{$result->sq_feet}} ft<sup>2</sup></li>
                                                        </ul>
                                                    </div>

                                                    <div class="listing-ad-type">
                                                        @if ($result->agent_company)
                                                            {{$result->agent_company}}
                                                        @endif
                                                        <br>
                                                        @if ($result->path_to_logo)
                                                            <span class="company_logo"><img style="max-width:150px;" src="{{$result->path_to_logo}}"></span>
                                                        @endif
                                                    </div>

                                                    @if(isset($result->OpenHouse) && !$result->OpenHouse->isEmpty())
                                                        @foreach($result->OpenHouse as $key=>$item)

                                                            <div class="listing-ad-open-house is-pulled-right">
                                                          <span class="button">Open House: {{Carbon\Carbon::parse($item->date)->format('D M j')}}
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
                                @endif
                            @endforeach
                        </div>

                        <div class="column is-4 hidden-print">
                            <div class="sidebar" >
                                <maps inline-template name="result" v-bind:id="estateMap.id" v-bind:type="estateMap.type" v-bind:features="{{ isset($results) ? json_encode($features) : '' }}" v-bind:results="{{ isset($results) ? json_encode($results) : '' }}" v-bind:estate="{{ (isset($features) && !$features->isEmpty()) ? $features[0] : (isset($results) && !$results->isEmpty()) ? $results[0] : '' }}">
                                    <div id="div_map">
                                        <div v-bind:id="mapName" class="google-map"></div>
                                    </div>
                                </maps>
                            </div>
                        </div>
                    </div>



                    <nav class="pagination is-centered" role="navigation" aria-label="pagination">
                        {{$results->links()}}
                    </nav>

                </div>
            </section>

            {{--@if($next)

                <result inline-template v-bind:searchid="{{$id}}"  v-bind:sort="'{{$sort}}'">
                    <div>
                        <div class="reload2" style="display:none">
                            <div class="container">
                                <div class="columns">
                                    <div class="column is-8">

                                        <ul>
                                            <li v-for="post in posts">
                                                <ul>
                                                    <li v-for="advert in post.results">

                                                        <div class="">
                                                            <div class="columns box-listing">

                                                                <div class="column is-4">
                                                                        <img :src="advert.img" alt="">
                                                                </div>
                                                                <div class="column">
                                                                    <div class="level">
                                                                        <a  :href = "advert.name_link"><h4 class="is-6 main-color a">@{{advert.name}}</h4></a>
                                                                        <span class="is-danger title is-6" ><span class="">@{{advert.building_type}}</span></span>
                                                                    </div>
                                                                    <div class="content">
                                                                        <p class="">
                                                                            <span id="price-toline" class="title is-6">$@{{ advert.price }} @if ($result->estate_type==2)@endif</span>
                                                                        </p>
                                                                        <p id="listing-ads" class="">
                                                                        <ul class="level-left is-mobile" id="listing-ad-ul-type">
                                                                            <li id="listing-ad-bed">@{{advert.beds}} beds|</li>
                                                                            <li id="listing-ad-bath">@{{advert.baths}} baths|</li>
                                                                            <li id="listing-ad-ft">@{{advert.sq_feet}} ft^2</li>
                                                                        </ul>
                                                                        </p>
                                                                        <div class="listing-ad-type">
                                                                            <!-- <span id="listing-ad-co-op">Co - Op in Stuyvesant Heights</span> -->
                                                                        </div>

                                                                        <div class="listing-ad-type">
                                                                            @if ($result->agent_company)
                                                                                {{$result->agent_company}}
                                                                            @endif
                                                                        </div>

                                                                        <!-- <div class="listing-ad-open-house is-pulled-right">
                                                                            <span class="button">Open House:7:30 am 8/29</span>
                                                                        </div> -->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>

                                            </li>
                                        </ul>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </result>


                <div class="container reload" style="margin-top: 15px;margin-left: 30%">
                    <img src="https://nvp.bikerent.nyc/images/805.svg" alt="" class=""></div>


            @endif--}}


            <div class="modal fade modal-lg" tabindex="-1" role="dialog"  aria-hidden="false" id="modal_listings_email">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <h3>Send message to Agent</h3>


                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>

                        <div class="modal-body">
                            <form>
                                <div>
                                    <dl>
                                        <dt>Your email:</dt>
                                        <dd><input class="form-control" type="email" required="required" placeholder="Please enter your email"/></dd>
                                    </dl>

                                    <dl>
                                        <dt>Your phone (optional):</dt>
                                        <dd><input class="form-control" type="email" placeholder="Please enter your phone"/></dd>
                                    </dl>

                                    <dl>
                                        <dt><label for="modal_message">Your message</label></dt>
                                        <dd><textarea id="modal_message" class="form-control" rows="5" ></textarea></dd>
                                    </dl>

                                    <dl>
                                        <dt><input type="submit" class="button modal-leave-active"  value="Send" /></dt>
                                        <dd><input type="button" class="button modal-default-button" value="Close" /></dd>
                                    </dl>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection
@section('footer')
    @include('layouts.footer')
@endsection

@section('additional_scripts')
    <script>

        $('.is-offset-4').attr('id','advance-search');

        function sortresults(e){
            document.location.replace('/search/{{$id}}/'+ e.value);
        }

        function showModalListing(id){
            $('#modal_listings_email').show();
        }

        function goBack() {
            var path = window.location.protocol+'//'+window.location.hostname
            if ($.inArray(document.referrer, [path+'/', path+'/rentals', path+'/sales'])) {
                //alert(document.referrer);
                window.location.href = document.referrer;		
            }
            else
                history.go(-1);

            return false;
        }

    </script>
@endsection
