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
                    <h4>Total: <b>{{$usersList->total()}}</b></h4>
                    @if($usersList->total() != 0)
                    <table>
                        <tr>
                            <th class="has-text-centered">
                                Name
                            </th>
                            <th class="has-text-centered">
                                Email
                            </th>
                            <th class="has-text-centered">
                                Phone
                            </th>
                            <th class="has-text-centered">

                            </th>
                            <th class="has-text-centered">

                            </th>
                        </tr>
                        @foreach($usersList as $item)
                        <tr>
                            <td>
                                {{$item->name}}
                            </td>
                            <td>
                                {{$item->email}}
                            </td>
                            <td>
                                {{$item->phone}}
                            </td>

                            <td >
                                <form method="POST" action="{{ route('editUser') }}" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <input type="submit" class="button is-info" name="submit" value="edit">
                                    <input type="hidden" name="id" value="{{$item->id}}">
                                </form>
                            </td>
                            <td >
                                <form method="POST" action="{{ route('deleteUser') }}" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <input type="submit" class="button is-danger" name="submit" onclick="return confirm('Are you sure you want to delete this item?');" value="delete">
                                    <input type="hidden" name="id" value="{{$item->id}}">
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                    {{$usersList->links()}}
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