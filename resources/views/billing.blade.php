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
            <section style="margin-bottom:60px;">

                <div class="main-content columns is-centered bill_content" style="margin:0 auto;padding:0 10px;">
                    <div class="content">
                        @if (session('status'))
                            <div class="alert alert-success" style="text-align:center;margin-bottom:25px;">
                                {{ session('status') }}
                            </div>
                        @endif

                        <h4 style="text-align:center;">{{Auth::user()->name}}'s Billing History</h4>

                        @if (count($features))
                        <h4 style="padding-top:40px;padding-left:10px;font-weight:bold;font-size:17px;">Features:</h4>
                        <table style="margin-top:20px;">
                            <tr>
                                <th class="has-text-centered">
                                    Order ID
                                </th>
                                <th class="has-text-centered">
                                    Listint ID
                                </th>
                                <th class="has-text-centered">
                                    Auto Renew
                                </th>
                                <th class="has-text-centered">
                                    Period
                                </th>
                                <th class="has-text-centered">
                                    Amount
                                </th>
                                <th class="has-text-centered">
                                    Start
                                </th>
                                <th class="has-text-centered">
                                    End
                                </th>
                                <th class="has-text-centered">
                                    Status
                                </th>
                            </tr>
                            @foreach($features as $feature)                            
                            <tr>
                                <td class="has-text-centered">
                                    {{$feature->id}}
                                </td>
                                <td class="has-text-centered">
                                    {{$feature->listing_id}}
                                </td>
                                <td class="has-text-centered">
                                    @if ($feature->renew)
                                        Yes
                                    @else
                                        No
                                    @endif
                                </td>
                                <td class="has-text-centered">
                                    {{$feature->period}}
                                </td>
                                <td class="has-text-centered">
                                    ${{$feature->amount}}
                                </td>
                                <td class="has-text-centered">
                                    {{Carbon\Carbon::parse($feature->created_at)->format('Y-m-d')}}
                                </td>
                                <td class="has-text-centered">
                                    {{Carbon\Carbon::parse($feature->ends_at)->format('Y-m-d')}}
                                </td>                                
                                <td class="has-text-centered">
                                    {{$feature->status}}
                                </td>
                            </tr>
                            @endforeach
                        </table>
                        @endif

                        @if (count($premiums))
                        <h4 style="padding-top:40px;padding-left:10px;font-weight:bold;font-size:17px;">Premiums:</h4>
                        <table style="margin-top:20px;">
                            <tr>
                                <th class="has-text-centered">
                                    Order ID
                                </th>
                                <th class="has-text-centered">
                                    Listint ID
                                </th>
                                <th class="has-text-centered">
                                    Auto Renew
                                </th>
                                <th class="has-text-centered">
                                    Period
                                </th>
                                <th class="has-text-centered">
                                    Amount
                                </th>
                                <th class="has-text-centered">
                                    Start
                                </th>
                                <th class="has-text-centered">
                                    End
                                </th>                                
                                <th class="has-text-centered">
                                    Status
                                </th>
                            </tr>
                            @foreach($premiums as $premium)                            
                            <tr>
                                <td class="has-text-centered">
                                    {{$premium->id}}
                                </td>
                                <td class="has-text-centered">
                                    {{$premium->listing_id}}
                                </td>
                                <td class="has-text-centered">
                                    @if ($premium->renew)
                                        Yes
                                    @else
                                        No
                                    @endif
                                </td>
                                <td class="has-text-centered">
                                    {{$premium->period}}
                                </td>
                                <td class="has-text-centered">
                                    ${{$premium->amount}}
                                </td>
                                <td class="has-text-centered">
                                    {{Carbon\Carbon::parse($premium->created_at)->format('Y-m-d')}}
                                </td>
                                <td class="has-text-centered">
                                    {{Carbon\Carbon::parse($premium->ends_at)->format('Y-m-d')}}
                                </td>                                
                                <td class="has-text-centered">
                                    {{$premium->status}}
                                </td>
                            </tr>
                            @endforeach
                        </table>
                        @endif

                        @if (count($memberships))
                        <h4 style="padding-top:40px;padding-left:10px;font-weight:bold;font-size:17px;">Membership:</h4>
                        <table style="margin-top:20px;">
                            <tr>
                                <th class="has-text-centered">
                                    Order ID
                                </th>
                                <th class="has-text-centered">
                                    Type
                                </th>
                                <th class="has-text-centered">
                                    Auto Renew
                                </th>
                                <th class="has-text-centered">
                                    Period
                                </th>
                                <th class="has-text-centered">
                                    Amount
                                </th>
                                <th class="has-text-centered">
                                    Start
                                </th>
                                <th class="has-text-centered">
                                    Ends
                                </th>                                
                                <th class="has-text-centered">
                                    Status
                                </th>
                            </tr>
                            @foreach($memberships as $membership)                            
                            <tr>
                                <td class="has-text-centered">
                                    {{$membership->id}}
                                </td>
                                <td class="has-text-centered">
                                    @if ($membership->type == 1)
                                        Silver
                                    @elseif ($membership->type == 2)
                                        Gold
                                    @elseif ($membership->type == 3)
                                        Diamond
                                    @endif
                                </td>
                                <td class="has-text-centered">
                                    @if ($membership->renew)
                                        Yes
                                    @else
                                        No
                                    @endif
                                </td>
                                <td class="has-text-centered">
                                    {{explode('_', $membership->period)[1]}}
                                </td>
                                <td class="has-text-centered">
                                    ${{$membership->amount}}
                                </td>
                                <td class="has-text-centered">
                                    {{Carbon\Carbon::parse($membership->created_at)->format('Y-m-d')}}
                                </td>
                                <td class="has-text-centered">
                                    {{Carbon\Carbon::parse($membership->ends_at)->format('Y-m-d')}}
                                </td>                                
                                <td class="has-text-centered">
                                    {{$membership->status}}
                                </td>
                            </tr>
                            @endforeach
                        </table>
                        @endif

                        @if (count($name_labels))
                        <h4 style="padding-top:40px;padding-left:10px;font-weight:bold;font-size:17px;">Name Labels:</h4>
                        <table style="margin-top:20px;">
                            <tr>
                                <th class="has-text-centered">
                                    Order ID
                                </th>
                                <th class="has-text-centered">
                                    Building ID
                                </th>
                                <th class="has-text-centered">
                                    Type
                                </th>
                                <th class="has-text-centered">
                                    Auto Renew
                                </th>
                                <th class="has-text-centered">
                                    Period
                                </th>
                                <th class="has-text-centered">
                                    Amount
                                </th>
                                <th class="has-text-centered">
                                    Start
                                </th>
                                <th class="has-text-centered">
                                    End
                                </th>                                
                                <th class="has-text-centered">
                                    Status
                                </th>
                            </tr>
                            @foreach($name_labels as $name_label)                            
                            <tr>
                                <td class="has-text-centered">
                                    {{$name_label->id}}
                                </td>
                                <td class="has-text-centered">
                                    {{$name_label->building_id}}
                                </td>
                                <td class="has-text-centered">
                                    {{$name_label->type}}
                                </td>
                                <td class="has-text-centered">
                                    @if ($name_label->renew)
                                        Yes
                                    @else
                                        No
                                    @endif
                                </td>
                                <td class="has-text-centered">
                                    {{$name_label->period}}
                                </td>
                                <td class="has-text-centered">
                                    ${{$name_label->amount}}
                                </td>
                                <td class="has-text-centered">
                                    {{Carbon\Carbon::parse($name_label->created_at)->format('Y-m-d')}}
                                </td>
                                <td class="has-text-centered">
                                    {{Carbon\Carbon::parse($name_label->ends_at)->format('Y-m-d')}}
                                </td>                                
                                <td class="has-text-centered">
                                    {{$name_label->status}}
                                </td>
                            </tr>
                            @endforeach
                        </table>
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