@extends('layouts.app')

@section('header')
    <header>
        @include('layouts.header')
    </header>
@endsection

@section('content')
    <div id="app">
        <div>
            <section>
                <div class="main-content columns is-mobile is-centered">
                    <div class="content">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        <h4>Total: <b>{{$buildings->total()}}</b></h4>
                        @if($buildings->total() != 0)
                            <table>
                                <tr>
                                    <th class="has-text-centered">
                                        Name
                                    </th>
                                    <th class="has-text-centered">
                                        Address
                                    </th>
                                    <th class="has-text-centered">
                                        City
                                    </th>
                                    <th class="has-text-centered">
                                        Build Year
                                    </th>
                                    <th class="has-text-centered">

                                    </th>
                                    <th class="has-text-centered">

                                    </th>
                                </tr>
                                @foreach($buildings as $item)
                                    <tr>
                                        <td>
                                            {{$item->building_name}}
                                        </td>
                                        <td>
                                            {{$item->building_address}}
                                        </td>
                                        <td>
                                            {{$item->building_city}}
                                        </td>
                                        <td>
                                            {{$item->building_build_year}}
                                        </td>

                                        <td >
                                            <form method="POST" action="{{ route('editBuilding') }}" enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <input type="submit" class="button is-info" name="submit" value="edit">
                                                <input type="hidden" name="id" value="{{$item->id}}">
                                            </form>
                                        </td>
                                        <td >
                                            <form method="POST" action="{{ route('deleteBuilding') }}" enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <input type="submit" class="button is-danger" name="submit" onclick="return confirm('Are you sure you want to delete this item?');" value="delete">
                                                <input type="hidden" name="id" value="{{$item->id}}">
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            {{$buildings->links()}}
                        @endif
                    </div>

                </div>
            </section>
        </div>
    </div>
@endsection


@section('footer')
    @include('layouts.footer')
@endsection