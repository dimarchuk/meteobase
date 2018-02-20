$(document).ready(function () {

    $('#regions').on('click', function (event) {
        event.preventDefault();
        var regions = $('#regions-select').val();
        var regions_json = JSON.stringify(regions);

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/',
            data: {regions_id: regions_json},
            dataType: 'json',
            success: function (json) {

                $('#stations-select').remove();

                var select = $('<select/>', {
                    id: 'stations-select',
                    class: 'form-control',
                    multiple: 'multiple',
                    name: 'stationName[]',
                    size: '8'
                });

                $.each(json, function () {
                    $('<option/>', {
                        val: this.IND_ST,
                        text: this.NAME_ST
                    }).appendTo(select);
                });

                $('.sections-wrapper').append(select);

                if (json) {
                    console.log(json);
                } else console.log('NO!');

            }
        });
    });

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
                } else console.log('NO!');

            }
        });
    });
});