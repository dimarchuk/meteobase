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
                <th>{{$headers[0]}}</th>
                <th>{{$headers[1]}}</th>
                <th>{{$headers[2]}}</th>
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
                    <td>{{$item->PAR1}}</td>
                    <td>{{$item->PAR2}}</td>
                    <td>{{$item->PAR3}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif