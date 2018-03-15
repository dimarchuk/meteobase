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
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: $that.attr('method'),
            url: $that.attr('action'),
            data: {data: fData},
            dataType: 'html',
            success: function (view) {

                $('.table-responsive').remove();
                $('.main-content').html(view);

                $(document).on('click', '.pagination a', function (event) {
                    var currentPage = $(this).attr('href').split('page=')[1];
                    getSelectedPage(currentPage);
                    event.preventDefault();
                });

            },
        });
    });

    $("tr").on('click', function (event) {
        var selectedRow = $(this).children("td");
        var data = "id=" + selectedRow[2].textContent + "&date=" + selectedRow[3].textContent + "&srok=" + selectedRow[4].textContent + "&requestName=selectGroup9";
        getGroup9(data);

    });
});

function getSelectedPage(page) {

    var fData = $('form').serialize() + "&requestName=selectInfoForTable" + "&page=" + page;
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: '/',
        data: {data: fData},
        dataType: 'html'
    }).done(function (view) {

        $('.table-responsive').remove();

        $('.main-content').html(view);

    }).fail(function () {
        alert('Page could not be loaded.');
    });
}

function getGroup9(data) {

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: '/',
        data: {data: data},
        dataType: 'json'
    }).done(function (response) {

        console.log(response);

    }).fail(function () {

        alert('Group 9 could not be loaded.');

    });
}