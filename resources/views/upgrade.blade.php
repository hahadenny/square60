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
                <div class="main-content columns is-mobile is-centered" style="margin-bottom:40px;">
                    <div class="content">
                        

                        @if (0 && $alreadyExpert)  {{--old code--}}
                            <div>
                                You are already a premium member!
                            </div>
                        @else
                        <div id="page1">
                            <div class="column is-centered" style="text-align:left;margin-bottom:10px;">
                                <div class="inner">
                                    @if (session('status'))
                                        <div class="alert alert-success" style="margin-bottom:20px; text-align:left;">
                                            {!! session('status') !!}
                                        </div>
                                    @endif

                                    @if (Auth::user()->premium)
                                        <div style="margin-bottom:20px;color:red;font-size:18px;">
                                            <b>You are a @if(Auth::user()->premium==1)Silver @elseif(Auth::user()->premium==2)Gold @elseif(Auth::user()->premium==3)Diamond @endif Member!</b>
                                            @if (isset($alreadyExpert->type))
                                            <div style="font-size:13px;margin-top:10px;">
                                                Membership ends: {{Carbon\Carbon::parse($alreadyExpert->ends_at)->format('Y-m-d')}}<br>                                    
                                                @if ($alreadyExpert->renew == 1)
                                                    Auto Renew on: {{Carbon\Carbon::parse($alreadyExpert->ends_at)->format('Y-m-d')}}<br>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                    @endif
                                    <h4 style="text-align:left;margin-bottom:30px;margin-top:30px;"><b>Upgrade Your Membership Today!</b></h4>
                                    <h4>These are the Benefits of Becoming a Member:</h4>
                                    <ul style="padding-top:10px;margin-left:15px;">
                                        <li style="list-style-type:disc">Display your Bio and Listing on your own personal webpage.</li>
                                        <li style="list-style-type:disc">You can access the owner's mailing list.</li>
                                        <li style="list-style-type:disc">Users can view your web pages here at Square60.com.</li>
                                        <li style="list-style-type:disc">Have access to Square60Lot.com.</li>
                                        <li style="list-style-type:disc">25% discount on feature listing.</li>
                                    </ul>
                                    <div style="text-align:left;margin-top:50px;">
                                        <h4>Upgrade Today!</h4>
                                        <a class="button is-primary mainbgc" onclick="nextPage()" style="margin-top:10px;">Upgrade</a>
                                    </div>
                                </div>                                
                            </div>                            
                        </div>

                        <form action="/upgradeForm" method="post" id="payment-form">
                        {{ csrf_field() }}
                        <div id="page2" style="display: none;">
                            <div class="column is-centered">
                            <div class="inner">
                            <h4>Membership Type:</h4>
                            <div class="container" style="padding-left:0px;">
                                <label class="checkbox">
                                    <input name="type" required="required" type="radio" value="1" onclick="changePrice(1);" checked>
                                    Silver
                                </label>
                                <label class="checkbox">
                                    <input name="type" required="required" type="radio" value="2" onclick="changePrice(2);">
                                    Gold
                                </label>
                                <label class="checkbox">
                                    <input name="type" required="required" type="radio" value="3" onclick="changePrice(3);">
                                    Diamond
                                </label>
                            </div>

                            <div class="mtype" id="type_1" style="margin-top:20px;">
                                <div class="container" style="padding-left:0px;">
                                    <label class="checkbox">
                                        <input id="month" name="period" required="required" type="radio" class="price" value="1_1m">
                                        $<span class="1_1m">{{env('SILV_1M')}}</span> per month.
                                    </label>
                                </div>
                                <div class="container" style="padding-left:0px;margin-top:10px;">
                                    <label class="checkbox">
                                        <input id="year" name="period" required="required" type="radio" class="price" value="1_1y">
                                        $<span class="1_1y">{{env('SILV_1Y')}}</span> per year.
                                    </label>
                                </div>
                            </div>

                            <div class="mtype" id="type_2" style="margin-top:20px;display:none;">
                                <div class="container" style="padding-left:0px;">
                                    <label class="checkbox">
                                        <input id="month" name="period" required="required" type="radio" class="price" value="2_1m">
                                        $<span class="2_1m">{{env('GOLD_1M')}}</span> per month.
                                    </label>
                                </div>
                                <div class="container" style="padding-left:0px;margin-top:10px;">
                                    <label class="checkbox">
                                        <input id="year" name="period" required="required" type="radio" class="price" value="2_1y">
                                        $<span class="2_1y">{{env('GOLD_1Y')}}</span> per year.
                                    </label>
                                </div>
                            </div>

                            <div class="mtype" id="type_3" style="margin-top:20px;display:none;">
                                <div class="container" style="padding-left:0px;">
                                    <label class="checkbox">
                                        <input id="month" name="period" required="required" type="radio" class="price" value="3_1m">
                                        $<span class="3_1m">{{env('DIAM_1M')}}</span> per month.
                                    </label>
                                </div>
                                <div class="container" style="padding-left:0px;margin-top:10px;">
                                    <label class="checkbox">
                                        <input id="year" name="period" required="required" type="radio" class="price" value="3_1y">
                                        $<span class="3_1y">{{env('DIAM_1Y')}}</span> per year.
                                    </label>
                                </div>
                            </div>

                            <div class="container" style="padding-left:0px;margin-top:10px;">
                                Auto Renew&nbsp;&nbsp;<label class="checkbox">
                                    <input type="checkbox"  name="recuring" value="1">
                                    Yes.
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
                                    <div class="container" style="padding-left:0px;margin-top:30px;">
                                        <h5>Total: <span id="total"></span></h5>
                                        <button class="button is-primary" style="margin-top:20px;">Check Out</button>
                                        <br><br>
                                        <a id="previous" class="button is-link mainbgc" onclick="previousPage()" style="display: none;">BACK</a>
                                    </div>                                
                            </div>
                            </div>
                            </div>
                        </div>

                        </form>

                    </div>
                    @endif
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
            $('.alert-success').html('');
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

        $('input.price').on('change', function() {
            $('input.price').not(this).prop('checked', false);
                if($(this).is(":checked")) {
                    var total_span = $(this).val();
                    var total = $('.'+total_span).html();
                    $('#total').html('$'+total);
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

        function changePrice(type) {
            $("input[name='period']").prop("checked", false);
            $('.mtype').hide();
            $('#type_'+type).show();
        }
    </script>
@endsection