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
                        <h4>Total: <b>{{$agentsList->total()}}</b></h4>
                        @if($agentsList->total() != 0)
                            <table>
                                <tr>
                                    <th class="has-text-centered">
                                        User ID
                                    </th>
                                    <th class="has-text-centered">
                                        Name
                                    </th>
                                    <th class="has-text-centered">
                                        Company
                                    </th>
                                    <th class="has-text-centered">
                                        Office phone
                                    </th>
                                    <th class="has-text-centered">

                                    </th>
                                    <th class="has-text-centered">

                                    </th>
                                </tr>
                                @foreach($agentsList as $item)
                                    <tr>
                                        <td>
                                            {{$item->user_id}}
                                        </td>
                                        <td>
                                            {{$item->first_name}} {{$item->last_name}}
                                        </td>
                                        <td>
                                            {{$item->company}}
                                        </td>
                                        <td>
                                            {{$item->office_phone}}
                                        </td>

                                        <td >
                                            <form method="POST" action="{{ route('editAgent') }}" enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <input type="submit" class="button is-info" name="submit" value="edit">
                                                <input type="hidden" name="id" value="{{$item->id}}">
                                            </form>
                                        </td>
                                        <td >
                                            <form method="POST" action="{{ route('deleteAgent') }}" enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <input type="submit" class="button is-danger" name="submit" onclick="return confirm('Are you sure you want to delete this item?');" value="delete">
                                                <input type="hidden" name="id" value="{{$item->id}}">
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            {{$agentsList->links()}}
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