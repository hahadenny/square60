$(document).ready(function () {
    var ww = $(window).width();
    /*  */
    /* checkbox logic  */
    /*  */
    $('.tabs-body .tab .checkbox').change(function (e) {
        var isChecked = $(this).is(':checked');
        //check all child 
        $(this).parent('li').find('li').each(function (index, el) {
            $(el).find('.checkbox').prop("checked", isChecked);
        })
        //check parent
        checkParent(this);
    })

    function checkParent(el) {
        var allChildChecked = true;
        $(el).parent('li').parent('ul').find('li').each(function (index, el) {
            if (!$(el).find('.checkbox').is(':checked')) {
                allChildChecked = false
            }
        })
        $(el).parent('li').parent('ul').siblings('.checkbox').prop("checked", allChildChecked);
        if ($(el).parent('li').parent('ul').siblings('.checkbox').parent('li').parent('ul').length !== 0) {
            checkParent($(el).parent('li').parent('ul').siblings('.checkbox'))
        }
    }

    /*  */
    /* select style */
    /*  */
    $('select.custom').styler();

    /*  */
    /* select filter logic */
    /*  */
    $('.custom.from').change(function (e) {
        var val = $(this).val()
        var select = $(this).parent('.jqselect').siblings('.jqselect').find('select');
        console.log(select)
        //need to disable prop
    })
    $('.custom.to').change(function (e) {
        console.log($(this).val())
        //need to disable prop
    })

    /* show more */
    $('.filter-page .more-btn').click(function () {
        if ($(this).hasClass('open')) {
            $(this).removeClass('open');
            $(this).siblings('.list').find('li').each(function (index, el) {
                if (index > 5) {
                    $(el).slideUp();
                }
            })
        } else {
            $(this).addClass('open');
            $(this).siblings('.list').find('li').slideDown();
        }
    })


    /*  */
    /* tabs */
    /* tab */
    $('.tabs .tabs-header span').click(function () {
        if ($(this).hasClass('active')) {
            return
        }
        var id = $(this).attr('data-tab');
        $('.tabs .tabs-header span').removeClass('active');
        $('.tabs .tabs-body .tab').removeClass('active');
        $(this).addClass('active');
        $('#' + id).addClass('active');

    })
    /* mobile tab */
    $('.tabs .tabs-body .tab .tab-ttl').click(function () {
        if ($(this).parent('.tab').hasClass('active')) {
            $(this).parent('.tab').removeClass('active');
            return;
        }
        $('.tabs .tabs-header span').removeClass('active');
        $('.tabs .tabs-body .tab').removeClass('active');
        $(this).parent('.tab').addClass('active');
    })

    $('.tabs .tab .mobile-ttl').click(function () {
        $(this).siblings('ul').slideToggle();
        $(this).toggleClass('active')
    })

    /* mobile nav */
    $('.header .mobile-btn').click(function (e) {
        if ($(this).hasClass('active')) {

            $(this).removeClass('active');
            $('#mobile-menu').fadeOut();
            $('.header .mobile-btn .close').hide()
            $('.header .mobile-btn .open').fadeIn();
            $('body, html').attr('style', '')

        } else {
            $('body, html').css('overflow', 'hidden')
            $(this).addClass('active');
            $('#mobile-menu').fadeIn();
            $('.header .mobile-btn .open').hide();
            $('.header .mobile-btn .close').fadeIn();

        }
    })

    /* slider */
    $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        asNavFor: '.slider-nav'
    });
    $('.slider-nav').slick({
        centerMode: false,
        slidesToShow: 5,
        slidesToScroll: 1,
        arrows: false,
        asNavFor: '.slider-for',
        focusOnSelect: true,
        responsive: [{
            breakpoint: 767,
            settings: {
                slidesToShow: 3
            }
        }]
    });

    function initMap() {
        var uluru = {
            lat: Number($('#map').attr('data-lat')),
            lng: Number($('#map').attr('data-lng')),
        };
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 4,
            center: uluru
        });
        var marker = new google.maps.Marker({
            position: uluru,
            map: map
        });
    }
    initMap();

    /* curentrlu slider */
    if (ww < 1024) {
        $('.currently > .container > ul').slick({
            centerMode: false,
            slidesToShow: 3,
            arrows: false,
            dots: true,
            responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    }

    $(window).on('resize orientationchange', function () {

        ww = $(window).width();
        if (ww < 1024 && !$('.currently > .container > ul').hasClass('slick-initialized')) {
            $('.currently > .container > ul').slick({
                centerMode: false,
                slidesToShow: 3,
                arrows: false,
                dots: true,
                responsive: [{
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 2
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 1
                        }
                    }
                ]
            });
        }
    })
});