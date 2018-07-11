@extends('layouts.app')

@section('header')
    <header>
        @include('layouts.header')
    </header>
@endsection

@section('content')
    <div id="app">
        <div>
            <section >


                <div class="main-content columns is-mobile is-centered">
                    <div class="content">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <h4>Become an expert:</h4>
                        <p>- 25% discount on Featured listening</p>
                        <p>- 25% access to propertywise </p>
                        <p>- access Building Owner list</p>

                            <h4>Select payment plan:</h4>
                            <div class="container">
                                <label class="checkbox">
                                    <input id="month" type="checkbox" class="price" value="20">
                                    $20 per month.
                                </label>
                            </div>
                            <div class="container">
                                <label class="checkbox">
                                    <input id="year" type="checkbox" class="price" value="200">
                                    $200 per year.
                                </label>
                            </div>
                            <div class="container">
                                Auto renew<label class="checkbox">
                                    <input type="checkbox">
                                    Yes.
                                </label>
                            </div>

                            <hr>
                            <div class="container">
                                <h5>Total: <span id="total"></span></h5>
                                <a class="button is-primary">Check out</a>
                            </div>


                    </div>

                </div>

            </section>
        </div>
    </div>
@endsection


@section('footer')
    @include('layouts.footer')
@endsection

@section('additional_scripts')
    <script>

        $('input.price').on('change', function() {
            $('input.price').not(this).prop('checked', false);
            if($(this).is(":checked")) {
                var total = $(this).val();
                $('#total').html('$'+total);
            }else{
                $('#total').html('');
            }
        });


    </script>
@endsection