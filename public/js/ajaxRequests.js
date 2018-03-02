$(document).ready(function () {

    $('#regions').on('click', function (event) {
        // event.preventDefault();
        var regions_serialize = $('#regions-select').serialize() + "&requestName=selectStation";


        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/',
            data: {data: regions_serialize},
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

                $.each(json.station, function () {
                    $('<option/>', {
                        val: this.IND_ST,
                        text: this.NAME_ST
                    }).appendTo(select);
                });

                $('.sections-wrapper').append(select);

                multiSelect('#stations-select');

            }
        });
    });

    $('form').submit(function (event) {
        event.preventDefault();
        var $that = $(this),
            fData = $that.serialize() + "&requestName=selectInfoForTable";

        $('.table-responsive').remove();

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: $that.attr('method'),
            url: $that.attr('action'),
            data: {data: fData},
            dataType: 'html',
            success: function (view) {


                $('.main-content').html(view);

                $(document).on('click', '.pagination a', function (event) {
                    var currentPage = $(this).attr('href').split('page=')[1];
                    getSelectedPage(currentPage);
                    event.preventDefault();
                });

            },
        });
    });


});

function getSelectedPage(page) {

    var fData = $('form').serialize() + "&requestName=selectInfoForTable" + "&page=" + page;

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: '/',
        data: {data: fData},
        dataType: 'html',
    }).done(function (view) {

        $('.table-responsive').remove();

        $('.main-content').html(view);

    }).fail(function () {
        alert('Posts could not be loaded.');
    });
}