$(document).foundation();

$(function () {
    function setProdSliderSlides() {
        var windowWidth = $(window).width();
        if (windowWidth > 960) {
            return 4;
        } else if (windowWidth > 720) {
            return 3;
        } else if (windowWidth > 480) {
            return 2;
        } else {
            return 1;
        }
    }

    $('#header-slider')
        .bxSlider({
            mode: "fade",
            //infiniteLoop: false,
            adaptiveHeight: true,
            controls: false,
            auto: true,
            onSliderLoad: function (index) {
                var img = $('.h-slider__item').eq(index).data('img');
                $('.js-slider-bg').css({
                    'backgroundImage': 'url(' + img + ')'
                });

                if (Foundation.utils.is_small_only()) {
                    $('.js-slider-bg-small').css({
                        'backgroundImage': 'url(' + img + ')'
                    });
                } else {
                    $('.js-slider-bg-small').css({
                        'backgroundImage': 'none'
                    });
                }
            },
            onSlideBefore: function ($el, oldIndex, newIndex) {
                var img = $('.h-slider__item').eq(newIndex).data('img');
                $('.js-slider-bg').css({
                    'backgroundImage': 'url(' + img + ')'
                })

                if (Foundation.utils.is_small_only()) {
                    $('.js-slider-bg-small').css({
                        'backgroundImage': 'url(' + img + ')'
                    });
                } else {
                    $('.js-slider-bg-small').css({
                        'backgroundImage': 'none'
                    });
                }
            }
        });

    $('#price-range')
        .ionRangeSlider({
            type: "double",
            hide_min_max: true,
            hide_from_to: true,
            onChange: function (data) {
                $('#min-price').val(data.from);
                $('#max-price').val(data.to);
            }
        });

    var priceRange = $('#price-range').data('ionRangeSlider');

    $('#min-price, #max-price')
        .on('blur', function (e) {
            var minVal = parseInt($('#min-price').val(), 10),
                maxVal = parseInt($('#max-price').val(), 10);

            if (minVal <= parseInt($('#min-price').attr('min'), 10) || isNaN(minVal)) {
                minVal = $('#min-price').attr('min')
            }

            if (maxVal >= parseInt($('#max-price').attr('max'), 10) || isNaN(maxVal)) {
                maxVal = $('#max-price').attr('max');
            }

            if (minVal > maxVal) {
                if ($(e.target).attr('id') == 'min-price') {
                    minVal = maxVal;
                } else {
                    maxVal = minVal;
                }
            }

            $('#min-price').val(minVal);
            $('#max-price').val(maxVal);

            priceRange.update({
                from: minVal,
                to: maxVal
            });
        });

        $('.product__color')
            .on('click', function (e) {
                var $this = $(this);
                $this.closest('.product__colors').find('.product__color_active').removeClass('product__color_active');
                $this.addClass('product__color_active');
            });

    $('#gallery-stage').bxSlider({
        mode: 'fade',
        pagerCustom: "#gallery-thumbs",
        controls: false,
        onSliderLoad: function (index) {
            $('#gallery-stage').removeClass('gallery__stage_fix');
        }
    });

    $('.gallery__stage-link').magnificPopup({
        type: 'image',
        gallery:{
            enabled:true
        },
        zoom: {
            enabled: true
        }
    });

    var catCarouselOptions = {
            infiniteLoop: false,
            pager: false,
            slideWidth: 1000,
            moveSlides: 1,
            nextText: '<span class="fa fa-angle-right"></span>',
            prevText: '<span class="fa fa-angle-left"></span>',
            hideControlOnEnd: true,
            adaptiveHeight: true
        },
        catCarousel = new Array();

    $('.c-products__carousel').each(function () {
        var $this = $(this);

        catCarousel.push($this.bxSlider($.extend(catCarouselOptions, {
            minSlides: setProdSliderSlides(),
            maxSlides: setProdSliderSlides(),
            onSliderLoad: function () {
                $this.removeClass('c-products__carousel_fix');
            }
        })));
    });

    $(window)
        .on('resize', Foundation.utils.throttle(function () {
            for (var i= 0, l = catCarousel.length; i<l; i++) {
                catCarousel[i].reloadSlider($.extend(catCarouselOptions, {
                    minSlides: setProdSliderSlides(),
                    maxSlides: setProdSliderSlides()
                }))
            }
        }, 100));

    var mcsOptions = {
        setWidth: '100%',
        scrollInertia: 500,
        scrollbarPosition: 'inside',
        axis: 'x',
        theme: 'plastic',
        advanced: {
            autoExpandHorizontalScroll: false,
            updateOnContentResize: true
        }
    };

    $('.table-wrapper').mCustomScrollbar(mcsOptions);
    $('.counter').formCounter();

    function mapInitialize($el, address) {
        var geocoder = new google.maps.Geocoder();
        var mapOptions = {
            scrollwheel: false,
            zoom: 14,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map($el[0], mapOptions);
        var ico = 'img/map-ico.png';
        var marker = new google.maps.Marker({
            map: map
            //icon: ico
        });

        geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                    map: map,
                    //icon: ico,
                    position: results[0].geometry.location
                });
            } else {
                alert("Geocode was not successful for the following reason: " + status);
            }
        });
    }

    if ($('#form-map').length) {
        mapInitialize($('#form-map'), $('#form-map').data('address'));
    }

    $('.order__header')
        .on('click', function () {
            var $order = $(this).closest('.order');
            $order.toggleClass('order_active');
            $order.find('.order__content').stop(true, false).slideToggle(200);
        });

    if ($(window).scrollTop() > 300) {
        $('.btn-to-top').stop(true, false).fadeIn();
    }

    $('.btn-to-top')
        .on('click', function () {
            $('body, html').stop(true, false).animate({
                scrollTop: 0
            }, 500);
        });

    $(window)
        .on('scroll', Foundation.utils.throttle(function () {
            if ($(window).scrollTop() > 300) {
                $('.btn-to-top').stop(true, false).fadeIn();
            } else {
                $('.btn-to-top').stop(true, false).fadeOut();
            }
        }, 200));
});