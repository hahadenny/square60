<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bulma.css') }}">
    <link rel="stylesheet" href="{{ asset('css/print.css') }}" media="print" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/fullcalendar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lightbox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert.css') }}">

    @php if (preg_match('/\/agent\//', $_SERVER['REQUEST_URI'])) { @endphp
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @php } @endphp

    <link rel="stylesheet" href="{{ asset('css/bulma2.css') }}">
    <link rel="shortcut icon" href="/images/favicon.ico" />
    @if(isset($agent))
        @if($agent->full_name)
        <title>{{$agent->full_name}}@if($agent->company) - {{$agent->company}}@endif</title>
        @else
        <title>{{$agent->first_name}} {{$agent->last_name}}@if($agent->company) - {{$agent->company}}@endif</title>
        @endif
        <meta name="description" content="{{strip_tags($agent->description)}}" />
    @else
        <title>Square60</title>
        <meta name="description" content="An authentic real estate search engine for single family and multi family house, apartment, buildings, co-op, condo sales and rentals in New York City (NYC) five boro: Manhattan, Brooklyn, Queens, Bronx, Staten Island." />
    @endif
</head>

<body class="main" style="margin-top:0px;">

        @include('layouts.common')

        @yield('header')
        <main class="site-content">
        @yield('content')
        </main>
        @yield('footer')

<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4SYF6DSHc9axY_cHSRt7-YIF5GN0w64I"></script>

<link href='https://api.mapbox.com/mapbox-gl-js/v0.44.2/mapbox-gl.css' rel='stylesheet' />
<link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.1.1/mapbox-gl-geocoder.css' type='text/css' />

<script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.1.1/mapbox-gl-geocoder.min.js'></script>
<script src='https://api.mapbox.com/mapbox-gl-js/v0.44.2/mapbox-gl.js'></script>


<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="{{ asset('js/fullcalendar.min.js') }}"></script>
<script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('js/lightbox.min.js') }}"></script>
<script src="{{ asset('js/select2.full.min.js') }}"></script>
<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/main2.js') }}"></script>

    <script>
        var isMobile = false; //initiate as false
        // device detection
        if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
            || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) { 
            isMobile = true;
        }

        if (!isMobile) {
            $(window).on('resize orientationchange', function() {
                //hide mobile menu button
                if ($('.header .mobile-btn').hasClass('active')) {
                    $('.header .mobile-btn').removeClass('active');
                    $('#mobile-menu').fadeOut();
                    $('.header .mobile-btn .close').hide()
                    $('.header .mobile-btn .open').fadeIn();
                    $('body, html').attr('style', '')
                }
            });
        }

        $(document).ready(function() {
            $('#title-location').hide();

            var filtersLabel = $('.filters label');

            filtersLabel.hide();

            filtersLabel.slice(0, 6).show();

            $("#more-filters").on('click', function (e) {
                e.preventDefault();
                $('.filters label').show();
                $('#more-filters').remove();

            });

            LogoShow();

            $(".only_number").keypress(function(e) {
                return isNumber(e, this);
            });

            $('.tabs a').on('click', function() {
                $('.col_details').show('');
            });

            $('.s_city').on('click', function() {
                $('.s_city').removeClass('active');
                $(this).addClass('active');
                $('#city_id').val($(this).attr('data-id'));
            });

            $('.nem a').on('click', function() {
                $('.nem a').removeClass('active');
                $(this).addClass('active');
                $('#district_1').click();
                $('#real_estate_type').val($(this).attr('data-type'));

                if($(this).attr('data-type') === '1'){
                    $('#price').html('Price: ');
                    $('.extrafilter').remove();
                    $('#extraTypes').html('');
                    $('.type-block, .typeh').show();
                }

                if($(this).attr('data-type') === '2'){
                    $('#price').html('Rent: ');
                    $('#extraTypes').html('<input id="extraCheckbox" name="types[]" type="checkbox"  value="0" />rental Buidling');
                    $('.extrafilter').remove();
                    $('.filters_list').append('<li class="extrafilter filters "><label for="354" style=""><input name="filters[]" id="354" value="354" type="checkbox">no Fee</label></li>');
                    $('.type-block, .typeh').hide();
                }
            });

            $('#search_main').on('click', function() {
		        if ($('#real_estate_type').val() == '2')
   		            $("input[type=checkbox][name='types[]']").prop('checked', false);

                var formData = $('#searchForm').serialize();

                priceFrom = parseInt($('#price_from').val());
                priceTo = parseInt($('#price_to').val());
                if (priceTo < priceFrom) {
                    $('#price_from').addClass('error');
                    $('#price_to').addClass('error');
                    $('#price_to').focus();
                    return false;
                }
            });

            var dropdown = document.querySelector('.dropdown');
            if(dropdown){
                dropdown.addEventListener('click', function(event) {
                    event.stopPropagation();
                    dropdown.classList.toggle('is-active');
                });
            }
        });

        function isNumber(evt, element) {

            var charCode = (evt.which) ? evt.which : event.keyCode

            if (
                    (charCode != 45 || $(element).val().indexOf('-') != -1) &&
                    (charCode != 46 || $(element).val().indexOf('.') != -1) &&
                    (charCode < 48 || charCode > 57))
                return false;

            return true;
        }

        function LogoShow() {
            $('.navbar-brand').on('click', function() {
                $('.n').css('margin-top', '5vh');
                $('.search-wrap').css('display', 'none');
                $('header').removeClass('active');
                $('.s_city,.nem2').removeClass('active');
                $('.col_details').addClass('hidden');
                $('header').addClass('hidden');
                $('#logo').fadeIn('slow');

            });

            $('.tabs').on('click', function() {
                $('.n').css('margin-top', '5vh');
                $('.search-wrap').css('margin-top', '10vh').fadeIn('fast');
                $('header').removeClass('hidden');
                $('#logo').fadeOut('fast');
                $('header').addClass('active');
                $('.col_details').removeClass('hidden');
            });


        }

        function showSubDistricts(parentId){

            $('.loc-arr input:checkbox').prop("checked", false);
            $('#title-location').hide();
            $('.subdistricts').hide();
            $('[data-parent='+parentId+']').show();

        }

        function toggleSearch(){

            $('#search').toggle();
            $('#district_1').addClass("active");
            this.showSubDistricts(1);

        }

        $('.print-window').click(function() {
            window.print();
        });

        $(".showModal, #showModal").click(function() {
            $("#modalMain").addClass("is-active");
        });

        $("#showModalSubmit").click(function() {
            $("#modalSubmit").addClass("is-active");
        });

        $(".modal-close").click(function() {
            $(".modal").removeClass("is-active");
        });

        $(".box input[name=type]:radio").change(function () {
            $(':radio:checked').not(this).prop('checked',false)
            $('label.is-success').removeClass('is-success is-selected'); //Reset selection

            $(this).closest('label').addClass('is-success is-selected');   //Add class to list item
        });

        submitRole = function() {
            var new_role = $('input[name=type]:checked', '#changeRoleForm').val();

            if (new_role == 3) {
                swal('You need to sign up a new account for agent.');
                return false;
            }
            else {
                document.changeRoleForm.submit();
                return true;                
            }
        }

        //log visit
        var url = window.location.pathname;
        var token = '{{Session::token()}}';
        $.post("/api/log",
        {
            url: url,
            _token: token
        },
        function(data, status){ 
            //alert("Data: " + data + "\nStatus: " + status);
        }).fail(function(response) {
            //alert('Error: ' + response.responseText);
        });
    </script>
    @yield('additional_scripts')
</body>
</html>
