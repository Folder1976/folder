// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeline', 'timelineEnd', 'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

/**
 * counter
 * @name = formCounter
 */
(function ($) {

    var defaults = {

    };
    var options;

    var methods = {
        init: function (params) {
            options = $.extend({}, defaults, params);
            options.counter = this;

            options.counter
                .on('change keyup', '.counter__input', function () {
                    privateMethods._checkValue($(this));
                })
                .on('click', '.counter__minus', function () {
                    var $counter = $(this).closest('.counter').find('.counter__input'),
                        val = parseInt($counter.val(), 10);
                    $counter.val(val-1).change();
                })
                .on('click', '.counter__plus', function () {
                    var $counter = $(this).closest('.counter').find('.counter__input'),
                        val = parseInt($counter.val(), 10);
                    $counter.val(val+1).change();
                });
        }
    };

    var privateMethods = {
        _checkValue: function (el) {
            var val = parseFloat(el.val(), 10),
                min = el.attr('min'),
                max = el.attr('max'),
                $minus = el.closest('.counter').find('.counter__minus'),
                $plus = el.closest('.counter').find('.counter__plus');
            if (isNaN(val) || val <= min) {
                el.val(min).focus();
                $minus.attr('disabled', true);
                $plus.attr('disabled', false);
                return;
            }

            if (val >= max) {
                el.val(max).focus();
                $plus.attr('disabled', true);
                $minus.attr('disabled', false);
                return;
            }

            $plus.attr('disabled', false);
            $minus.attr('disabled', false);
        }
    };

    $.fn.formCounter = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method == 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Плагин применен с некорректными параметрами');
        }
    };
})(jQuery);

