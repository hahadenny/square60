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
                        <h4>Total: <b>{{$dataList->total()}}</b></h4>
                        @if($dataList->total() != 0)
                            <table>
                                <tr>
                                    <th class="has-text-centered">
                                        title
                                    </th>
                                    <th class="has-text-centered">
                                        text
                                    </th>
                                    <th class="has-text-centered">

                                    </th>
                                    <th class="has-text-centered">

                                    </th>
                                </tr>
                                @foreach($dataList as $item)
                                    <tr>
                                        <td>
                                            {{$item->title}}
                                        </td>
                                        <td>
                                            {!! $item->text !!}
                                        </td>
                                        <td >
                                            <form method="POST" action="{{ route('editArticle') }}" enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <input type="submit" class="button is-info" name="submit" value="edit">
                                                <input type="hidden" name="id" value="{{$item->id}}">
                                            </form>
                                        </td>
                                        <td >
                                            <form method="POST" action="{{ route('deleteArticle') }}" enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <input type="submit" class="button is-danger" name="submit" onclick="return confirm('Are you sure you want to delete this item?');" value="delete">
                                                <input type="hidden" name="id" value="{{$item->id}}">
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            {{$dataList->links()}}
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