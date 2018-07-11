@extends('layouts.app')

@section('header')

@endsection

@section('content')
    <div class="mobile-menu" id="mobile-menu">
        @include('layouts.header2_1')        
    </div>
    <div id="app">
        <div>
            
            @include('partial.header')  

            <section style="width:80%;max-width:300px; margin:0 auto;">

                <div class="main-content columns is-mobile is-centered" style="margin-bottom:40px;">
                    <div class="content">
                        @if (session('status'))
                            <div class="alert alert-success" style="margin-bottom:20px;color:red;text-align:center;">
                                {{ session('status') }}
                            </div>
                        @endif

                            <form action="{{route('featuring')}}" method="post" id="payment-form" style="width:100%;max-width:550px;margin:0 auto;padding:0 10px;">
                                {{ csrf_field() }}

                                    <h4>Feature Listing:</h4>
                                    @if(Auth::user()->premium)
                                    <div style="color:red;margin-bottom:15px;"><i>You will have 25% discount!</i></div>
                                    @endif
                                    <div class="container" style="padding-left:0px;">
                                        <label class="checkbox">
                                            <input id="week" name="period" required="required" type="radio" class="price" value="1w">
                                            $<span class="1w">{{env('FEAT_1W')}}</span> one week.
                                        </label>
                                    </div>
                                    <div class="container" style="padding-left:0px;">
                                        <label class="checkbox">
                                            <input id="twoWeeks" name="period" required="required" type="radio" class="price" value="2w">
                                            $<span class="2w">{{env('FEAT_2W')}}</span> two weeks.
                                        </label>
                                    </div>
                                    <div class="container" style="padding-left:0px;">
                                        <label class="checkbox">
                                            <input id="fourWeeks" name="period" required="required" type="radio" class="price" value="4w">
                                            $<span class="4w">{{env('FEAT_4W')}}</span> four weeks.
                                        </label>
                                    </div>
                                    {{--
                                    <div class="container" style="padding-left:0px;">
                                        <label class="checkbox">
                                            <input id="sixWeeks" name="period" required="required" type="radio" class="price" value="6w">
                                            $<span class="6w">250</span> six weeks.
                                        </label>
                                    </div>
                                    <div class="container" style="padding-left:0px;">
                                        <label class="checkbox">
                                            <input id="year" name="period" required="required" type="radio" class="price" value="2m">
                                            $<span class="2m">300</span> two months.
                                        </label>
                                    </div>
                                    --}}
                                    <div class="container" style="margin-top:10px;padding-left:0px;">
                                        Auto Renew&nbsp;<label class="checkbox">
                                            <input type="checkbox"  name="recuring" value="1">
                                            Yes.
                                            <input type="hidden" name="id" value="{{request()->id}}">
                                        </label>
                                    </div>
                                    <hr>

                                <div>

                                    <div class="form-row">
                                        <label for="card-element">
                                            Credit/Debit Card:
                                        </label>

                                        <div id="card-element" class="stripe_paym" style="margin-top:10px;">
                                        </div>

                                        <div id="card-errors" role="alert" style="margin-top:10px;color:red"></div>
                                    </div>
                                    <div class="container" style="margin-top:30px;padding-left:0px;">
                                        <h5>Total: <span id="total"></span></h5>
                                        <button class="button is-primary mainbgc" style="margin-top:20px;">Check Out</button>
                                    </div>


                                </div>

                                    <br><a id="previous" class="button is-link" onclick="previousPage()" style="display: none">BACK</a>


                            </form>

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
    <script src="https://js.stripe.com/v3/"></script>
    <script>

        function nextPage(){
            $("#next").hide();
            $("#page1").hide();
            $("#page2").show();
            $("#previous").show();
        }

        function previousPage(){
            $("#page2").hide();
            $("#previous").hide();
            $("#next").show();
            $("#page1").show();
        }

        /*$('.price').change(function() {
            if($(this).is(":checked")) {

                var total = $(this).val();
                $('#total').html('$'+total);
            }else{
                $('#total').html('');
                $(this).not(this).prop('checked', false);
            }
        });*/

        $('input.price').on('change', function() {
            $('input.price').not(this).prop('checked', false);
            if($(this).is(":checked")) {
                var total_span = $(this).val();
                var total = $('.'+total_span).html();
                @if(Auth::user()->premium)
                var total_int = parseFloat(total);
                var dis_total = total_int * 0.75;
                total = dis_total.toFixed(2);
                $('#total').html('$'+total+' <i style="color:red">(discounted)</i>');                
                @else
                $('#total').html('$'+total);
                @endif
            }else{
                $('#total').html('');
            }
        });


        // Create a Stripe client.
        var stripe = Stripe('{{env('STRIPE_KEY')}}');

        var elements = stripe.elements();

        var style = {
            base: {
                color: '#32325d',
                lineHeight: '18px',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {style: style});

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        // Handle real-time validation errors from the card Element.
        card.addEventListener('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Handle form submission.
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    // Inform the user if there was an error.
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    // Send the token to your server.
                    stripeTokenHandler(result.token);
                }
            });
        });


        function stripeTokenHandler(token) {
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            // Submit the form
            form.submit();
        }
    </script>
@endsection