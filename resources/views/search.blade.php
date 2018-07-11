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
            <section id="filters" class="" style="z-index:1;position:absolute;">
                <div class="container" style="padding:0px 0px;">
                    <div class="columns">
                        <div class="column is-4 is-offset-8" style="padding:0px;">
                            @include('partial.search2')
                        </div>
                    </div>
                </div>
            </section>

            <section id="top-search" style="margin-top:100px;">
                <div class="container" style="margin-bottom:0px;">
                    <div class="column is-12">
                        @if(isset($agents) && $agents->total() != 0)
                            <h3>{{$agents->total()}}
                                @if($agents->total() > 1)
                                    agents
                                @else
                                    agent
                                @endif
                                Found
                            </h3>
                            <hr class="large" />

                            @foreach ($agents as $agent)

                                <article class="media">
                                    <figure class="media-left">
                                        <div style="margin: 0px 20px 10px 0px;">
                                            @if (isset($agent->img))
                                                @if ($agent->web_site)
                                                <a href="{{$agent->web_site}}">
                                                    <p class="image is-128x128 agent-image" style="overflow: hidden;border-radius: 50%;background-image: url(@if (isset($agent->img) && $agent->img){{$agent->img}}@else /images/default_agent.jpg @endif);background-size:cover;background-position:top center;">
                                                        {{--<img src="{{$agent->img}}">--}}
                                                    </p>
                                                </a>
                                                @else
                                                    <p class="image is-128x128 agent-image" style="overflow: hidden;border-radius: 50%;background-image: url(@if (isset($agent->img) && $agent->img){{$agent->img}}@else /images/default_agent.jpg @endif);background-size:cover;background-position:top center;">
                                                        <img src="{{$agent->img}}">
                                                    </p>
                                                @endif
                                            @endif
                                        </div>
                                    </figure>
                                    <div class="media-content">
                                        <div class="content">
                                            <p>
                                                @php
                                                    if ($agent->full_name) 
                                                        $name = str_replace(' ', '_', $agent->full_name);
                                                    else
                                                        $name = str_replace(' ', '_', $agent->first_name) . '_'. str_replace(' ', '_', $agent->last_name);
                                                @endphp

                                                @if(isset($agent->id))
                                                    <a href="/agent/{{$name}}/{{$agent->id}}"><strong>{{$agent->full_name}}</strong></a>
                                                @else
                                                    <strong>{{$agent->full_name}}</strong>
                                                @endif
                                                <br />

                                                @if(isset($agent->company))
                                                    <small>
                                                        @if ($agent->web_site)
                                                            <a href="{{$agent->web_site}}">{{$agent->company}}</a>
                                                        @else
                                                            {{$agent->company}}
                                                        @endif
                                                    </small>
                                                    <br />
                                                @endif

                                                @if (isset($agent->phone))
                                                    <small>
                                                        <label></label> <span id="show_phone_span_{{$agent->id}}" onclick="showPhone({{$agent->id}})">Click for phone #</span><span style="display: none;" id="show_phone_{{$agent->id}}">{{$agent->phone}}</span>
                                                    </small>
                                                    <br />
                                                @endif

                                                <br />
                                            </p>
                                        </div>
                                    </div>
                                </article>
                                <hr />
                            @endforeach
                            <nav class="pagination is-centered" role="navigation" aria-label="pagination">
                                {{$agents->links()}}
                            </nav>
                        @endif

                        @if(isset($buildings) && $buildings->total() != 0)
                            <h3>{{$buildings->total()}}
                                @if($buildings->total() > 1)
                                    buildings
                                @else
                                    building
                                @endif
                                    Found
                            </h3>
                            <hr class="large" />

                            @foreach ($buildings as $k => $building)
                                <div class="columns">
                                    <div class="column is-6" style="padding-left:0px;">
                                        <span class="tags has-addons" style="margin-bottom:0px;"></span>                                        
                                        <p>
                                            <div class="is-size-5" style="font-size:15px !important;">
                                                @php
                                                    if (!app('request')->input('page'))
                                                        $page = 1;
                                                    else
                                                        $page = app('request')->input('page');
                                                @endphp
                                                {{20 * ($page - 1) + ($k+1)}}. &nbsp;<a href="/building/{{str_replace(' ', '_', $building->building_name)}}/{{str_replace(' ', '_', $building->building_city)}}">{{$building->building_name}}</a>
                                            </div>
                                            <div class="is-size-5" style="font-size:15px !important;margin-top:10px;">{{$building->building_address}}, {{$building->building_city}} {{$building->building_state}} {{$building->building_zip}}</div>
                                        </p>
                                        <div class="columns" style="margin-top:10px;">
                                            <div class="column" style="padding: 0px;">
                                                <ul>
                                                    <li style="margin-top:5px;">Condo</li>
                                                    <li style="margin-top:5px;">Units: {{$building->building_units}}</li>
                                                    <li style="margin-top:5px;">Stories: {{$building->building_stories}}</li>
                                                    
                                                </ul>
                                            </div>
                                            <div class="column" style="padding: 0px;">
                                                <ul>
                                                    <li style="margin-top:5px;">{{$building->neighborhood}}</li>
                                                    @if ($building->building_build_year)
                                                    <li style="margin-top:5px;">{{$building->building_build_year}}</li>
                                                    @endif
                                                    @if ($building->building_build_year < 1945)
                                                        <li style="margin-top:5px;">Prewar</li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                            @endforeach
                            <nav class="pagination is-centered" role="navigation" aria-label="pagination">
                                {{$buildings->links()}}
                            </nav>
                        @endif

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

    </script>
@endsection