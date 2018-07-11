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

                <div class="main-content is-mobile" style="margin-bottom:40px;max-width:550px;margin:0 auto;">
                    <div class="content" style="margin-top:20px;">
                        @if (session('status'))
                            <div class="alert alert-success" style="margin-left:10px;margin-bottom:25px;">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if(isset($saleSaved))
                        <h4 style="margin-left:10px;"><b>Saved Sale Search</b></h4>
                            <table>
                                @foreach($saleSaved as $item)
                                    <tr>
                                        <td width="70%">@if(isset($item->sort)){{$item->sort}}@endif
                                            <a href="/saved-searches/{{$item->search_id}}" class="navbar-item modal-button" style="padding-left:0px;">
                                                {{$item->title}}
                                            </a>
                                        </td>
                                        <td width="30%" align=left>
                                            <form method="POST" action="{{ route('deleteSearch') }}" enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <input type="submit" class="button is-primary mainbgc" name="submit" onclick="return confirm('Are you sure you want to delete this item?');" value="delete">
                                                <input type="hidden" name="search" value="{{$item->search_id}}">
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                        <br>
                        @if(isset($rentalSaved))
                        <h4 style="margin-left:10px;"><b>Saved Rental Search</b></h4>
                                <table>
                                    @foreach($rentalSaved as $item)
                                        <tr>
                                            <td width="70%">
                                                <a href="/saved-searches/{{$item->search_id}}" class="navbar-item modal-button">
                                                    {{$item->title}}
                                                </a>
                                            </td>
                                            <td width="30%" align=left>
                                                <form method="POST" action="{{ route('deleteSearch') }}" enctype="multipart/form-data">
                                                    {{ csrf_field() }}
                                                    <input type="submit" class="button is-primary mainbgc" name="submit" onclick="return confirm('Are you sure you want to delete this item?');" value="delete">
                                                    <input type="hidden" name="search" value="{{$item->search_id}}">
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                        @endif

                        @if(!isset($rentalSaved) && !isset($saleSaved) && !isset($savedSales) && !isset($savedRentals) && !isset($savedBuildings))
                            <h4 class="has-text-centered">You do not have saved search</h4>
                        @endif

                        <br>

                        @if(isset($savedSales))
                        <h4 style="margin-left:10px;"><b>Saved Sales</b></h4>
                            <table>
                                @foreach($savedSales as $item)
                                    <tr>
                                        <td width="70%">
                                            <a href="/show/{{str_replace(' ', '_', $item->full_address)}}/{{$item->save_id}}" class="navbar-item modal-button" style="padding-left:0px;">
                                                {{$item->full_address}} {{$item->unit}}
                                            </a>
                                        </td>
                                        <td width="30%" align=left>
                                            <form method="POST" action="{{ route('deleteItem') }}" enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <input type="submit" class="button is-primary mainbgc" name="submit" onclick="return confirm('Are you sure you want to delete this item?');" value="delete">
                                                <input type="hidden" name="sid" value="{{$item->id}}">
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                        <br>
                        @if(isset($savedRentals))
                        <h4 style="margin-left:10px;"><b>Saved Rentals</b></h4>
                            <table>
                                @foreach($savedRentals as $item)
                                    <tr>
                                        <td width="70%">
                                            <a href="/show/{{str_replace(' ', '_', $item->full_address)}}/{{$item->save_id}}" class="navbar-item modal-button" style="padding-left:0px;">
                                                {{$item->full_address}} {{$item->unit}}
                                            </a>
                                        </td>
                                        <td width="30%" align=left>
                                            <form method="POST" action="{{ route('deleteItem') }}" enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <input type="submit" class="button is-primary mainbgc" name="submit" onclick="return confirm('Are you sure you want to delete this item?');" value="delete">
                                                <input type="hidden" name="sid" value="{{$item->id}}">
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                        <br>
                        @if(isset($savedBuildings))
                        <h4 style="margin-left:10px;"><b>Saved Buildings</b></h4>
                            <table>
                                @foreach($savedBuildings as $item)
                                    <tr>
                                        <td width="70%">
                                            <a href="/building/{{$item->save_id}}/{{$item->save_id2}}" class="navbar-item modal-button" style="padding-left:0px;">
                                                {{$item->building_name}}
                                            </a>
                                        </td>
                                        <td width="30%" align=left>
                                            <form method="POST" action="{{ route('deleteItem') }}" enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <input type="submit" class="button is-primary mainbgc" name="submit" onclick="return confirm('Are you sure you want to delete this item?');" value="delete">
                                                <input type="hidden" name="sid" value="{{$item->id}}">
                                            </form>
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