$(document).ready(function () {

    var path = location.pathname;
    // console.log($('input[type=date]'));
    // $('input[type=date]').each(function (index, value) {
    //     value.setAttribute('type', 'month');
    //     value.value = '2018-07';
    //     console.log(value);
    // });
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

                // multiSelect('#stations-select');

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
            // dataType: 'html',
            success: function (view) {
                $('.table-responsive').remove();
                $('.main-content').html(view);

                $(document).on('click', '.pagination a', function (event) {
                    event.preventDefault();
                    var currentPage = $(this).attr('href').split('page=')[1];
                    getSelectedPage(currentPage, path);
                });

                if (path === '/') getGroup9();
                else if (path === '/warep') getStrings();

            }
        });
    });


    if (path === '/') getGroup9();
    else if (path === '/warep') getStrings();
});

function getSelectedPage(page, path) {

    var fData = $('form').serialize() + "&requestName=selectInfoForTable" + "&page=" + page;
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: location.pathname,
        data: {data: fData},
        dataType: 'html'
    }).done(function (view) {

        $('.table-responsive').remove();
        $('.main-content').html(view);

        if (path === '/') getGroup9();
        else if (path === '/warep') getStrings();

    }).fail(function () {
        alert('Page could not be loaded.');
    });
}


function getGroup9() {
    $("tr").on('click', function (event) {
        var selectedRow = $(this).children("td");
        var data = "id=" + selectedRow[2].textContent + "&date=" + selectedRow[3].textContent + "&srok=" + selectedRow[4].textContent + "&requestName=selectGroup9";
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/',
            data: {data: data},
            dataType: 'json'
        }).done(function (response) {

            $('#group9').remove();

            if (response.length != 0) {
                var content = ' <table id="group9" class="table table-condensed table-striped"><caption>9 Група</caption><thead><tr><th>Явища</th><th>Значення</th></tr></thead><tbody>';


                $.each(response, function (item) {
                    content += '<tr><td>' + response[item].KOD_SPSP + '</td><td>' + response[item].VALUE_SPSP + '</td></tr>';
                });

                content += '</tbody></table>';
                $('.group9-wrapper').append(content);
            }

        }).fail(function () {
            alert('Group 9 could not be loaded.');
        });

    });
}

function getStrings() {
    $("tr").on('click', function (event) {
        var selectedRow = $(this).children("td")[6].textContent;
        var rows = $($("table").find("tbody tr"));

         var exportURL = $("#export");
        exportURL[0].href = '/export' + '/' + selectedRow;

        setTableHeaders(selectedRow);

        rows.each(function (index, row) {
            if (selectedRow === row.children[6].textContent) {
                $($(row).children("td").splice(9)).css("visibility", "visible");
                $(row).css("backgroundColor", "#808080").css("color", "#ffffff");

            } else {
                $(row).css("backgroundColor", "").css("color", "");
                $($(row).children("td").splice(9)).css("visibility", "hidden")

            }
        });
    });
}

function setTableHeaders(numberOfGroup) {
    var head = $($("table").find("thead tr"));
    var cols = $($(head[0]).children("th").splice(9));
    var grups = {
        0: ['Діам./Товщина, мм', 'Т,°С', 'Показн. явища'],
        1: ['Напрям вітру', 'Сер. шв вітру', 'Макс. шв. вітру'],
        2: ['Напрямок явища', 'Вид опадів', ''],
        3: ['Опади, мм', 'Період, год', ''],
        7: ['МДВ, км', 'Явища', 'Трив. НЯ/СГЯ, год'],
        8: ['К-сть хмар', 'Форма хмар', 'Вис. нижн. межі, м'],
        9: ['Діаметр, мм', '', '']
    };
    // 'grups[numberOfGroup].' + 'PAR' + (index + 1)
    cols.each(function (index, col) {
        col.innerText = grups[numberOfGroup][index];
    });
}
