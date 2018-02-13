$(document).ready(function () {

    $('form').submit(function (event) {
        event.preventDefault();
        var $that = $(this),
            fData = $that.serialize();

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: $that.attr('method'),
            url: $that.attr('action'),
            data: {form_data: fData},
            dataType: 'json',
            success: function (json) {

                if (json) {
                    console.log(json);
                } else console.log('NOOOOOOOOOO!!!!');

            }
        });
    });
});