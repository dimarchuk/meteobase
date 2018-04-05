$(document).ready(function () {

    /**
     * Edit user name, email, status
     */
    $('.edit').on('click', function (event) {
        event.preventDefault();

        var url = this.href

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'GET',
            url: url,
            dataType: 'json',
            success: function (json) {

                $('#userName').val(json.name);
                $('#userEmail').val(json.email);

                if (true == json.admin) {
                    $('#gridRadios1').prop('checked', true);
                } else $('#gridRadios2').prop('checked', true);

                //show edit form
                $('.pgl-modal').pglModal({'duration': 300});

            }
        });
    });

});

/**
 * @param options
 * @returns {*}
 */
$.fn.pglModal = function (options) {

    var defaults = {
        duration: 300, // animation show/hide duration
        varticalAlign: true // Align middle
    };

    var settings = $.extend({}, defaults, options);

    return this.each(function () {

        var $elem = $(this),
            $submit = $elem.find('.form-submit'),
            $close = $elem.find('.pgl-modal-close');


        //init modal
        $elem.init = function () {
            $elem.loadSettings();
            $elem.handler();
            if (settings.varticalAlign) {
                $elem.setTop();
            }
        };

        //settings load
        $elem.loadSettings = function () {
            $elem.css({'transition-duration': settings.duration + 'ms'});
        };

        //init handlers
        $elem.handler = function () {
            //show
            $elem.add('.pgl-overlay').css('display', 'block');
            setTimeout(function () {
                $elem.add('.pgl-overlay').addClass('pgl-show');
            }, 100);

            //submit
            $submit.on('click', function (event) {
                event.preventDefault();

                hide();
            });

            $close.on('click', function () {
                hide();
            });

            function hide() {
                setTimeout(function () {
                    $elem.add('.pgl-overlay').css('display', 'none');
                }, settings.duration + 150);
                $elem.add('.pgl-overlay').removeClass('pgl-show');
            }

        };

        //padding top modal
        $elem.setTop = function () {
            var height = $elem.height(),
                win_height = $(window).height();
            $elem.css({'top': (win_height - height) / 2 - 100});
        };

        $elem.init();

    });

};