@if(isset($dataForTable) && is_object($dataForTable))
    <div class="table-responsive">
        <table class="table table-condensed table-striped">
            <thead>
            <tr>
                <th>Назва області</th>
                <th>Назва станції</th>
                <th>Індекс</th>
                <th>Дата</th>
                <th>Час</th>
                <th>Шторм</th>
                <th>Код групи</th>
                <th>Явище</th>
                <th>Код явища</th>
                <th>1</th>
                <th>2</th>
                <th>3</th>
            </tr>
            </thead>
            <tbody>
            @foreach($dataForTable as $item)
                <tr>
                    <td>{{$item->NAME_OBL}}</td>
                    <td>{{$item->NAME_ST}}</td>
                    <td>{{$item->IND_ST}}</td>
                    <td>{{$item->DATE_CH}}</td>
                    <td>{{$item->TIME_HOUR}}:{{ $item->TIME_MIN }}</td>
                    <td>{{$item->STORM_AVIA}}</td>
                    <td>{{$item->CODGROUP}}</td>
                    <td>{{$item->HENOTYP_DECODE}}</td>
                    <td>{{$item->CODPHENOTYP}}</td>
                    <td style="visibility: hidden">1</td>
                    <td style="visibility: hidden">2</td>
                    <td style="visibility: hidden">3</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <ul class="pagination">
        @php
            echo $paginationLinks
        @endphp
    </ul>
@endif