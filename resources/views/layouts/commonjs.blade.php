<script src="/js/jquery.cookie.js"></script>
<script>

$(window).on('resize orientationchange', function() {
    if ($('.tabs .tabs-body .tab.active').length == 0) { //no district selected
        //select default district 1 (Manhattan)
        selectDistrict(1);
    }
});

function selectDistrict(subDistrictId) {
    var e = $('#district_'+subDistrictId).attr("data-tab");
    $(".tabs .tabs-header span").removeClass("active"), $(".tabs .tabs-body .tab").removeClass("active"), $('#district_'+subDistrictId).addClass("active"), $("#" + e).addClass("active");
}

/* mobile tab */
$('.tabs .tabs-body .tab .tab-ttl').click(function () {
    $('.checkbox').prop("checked", false);
});

function showSubDistricts(parentId) {
    $('.checkbox').prop("checked", false);
    //$('.subdistricts').hide();
    //$('[data-parent='+parentId+']').show();
    $.cookie('selected_district', parentId);
}

$('#search_main').on('click', function() {
    if (!$('.checkbox').is(':checked')) {
        swal("Please select at least one location!");
        return false;
    }

    //for testing
    var formData = $('#mainFrom').serialize();
    //alert(formData);
});


@if(session('searchData'))
var searchData = {!! session('searchData') !!};
@else
var searchData = null;
@endif

//console.log(searchData);

if(searchData && searchData.districts && searchData.estate_type == $('#real_estate_type').val()){
    var districts = searchData.districts;

    if ($("label[for='"+districts[0]+"']").length) {
        var subDistrictId = $("label[for='"+districts[0]+"']").attr('data-parent');
        //alert(subDistrictId);

        //select district
        if (!$('#district_'+subDistrictId).hasClass("active")) {
            var e = $('#district_'+subDistrictId).attr("data-tab");
            $(".tabs .tabs-header span").removeClass("active"), $(".tabs .tabs-body .tab").removeClass("active"), $('#district_'+subDistrictId).addClass("active"), $("#" + e).addClass("active");
        }

        //check subdistrict
        for (var i = 0; i < districts.length; i++) {
            if ($('#'+districts[i]).length) {
                $('#'+districts[i]).prop('checked', true);

                var s = $('#'+districts[i]).is(":checked");
                $('#'+districts[i]).parent("li").find("li").each(function(e, t) {
                    $(t).find(".checkbox").prop("checked", s)
                }),
                function e(t) {
                    var s = !0;
                    $(t).parent("li").parent("ul").find("li").each(function(e, t) {
                        $(t).find(".checkbox").is(":checked") || (s = !1)
                    });
                    $(t).parent("li").parent("ul").siblings(".checkbox").prop("checked", s);
                    0 !== $(t).parent("li").parent("ul").siblings(".checkbox").parent("li").parent("ul").length && e($(t).parent("li").parent("ul").siblings(".checkbox"))
                }('#'+districts[i])
            }
        }    

        if(searchData.types){
            var types = searchData.types;
            for (var i = 0; i < types.length; i++) {
                $('#'+types[i]).attr('checked', 'checked');
            }
        }

        $('#bedFor').val(searchData.beds[0]);
        $('#bedTO').val(searchData.beds[1]);

        $('#bathFor').val(searchData.baths[0]);
        $('#bathTo').val(searchData.baths[1]);

        $('#priceFor').val(searchData.price[0]);
        $('#priceTo').val(searchData.price[1]);

        if(searchData.filters){
            var filters = searchData.filters;

            for (var i = 0; i < filters.length; i++) {
                $('#'+filters[i]).attr('checked', 'checked');
            }
        }   
    }
}
else {
    if ($.cookie('selected_district')) {        
        selectDistrict($.cookie('selected_district'));
    }
}

</script>