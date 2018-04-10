<div class="container-fluid">
    <div class="panel panel-defaul">
        <div class="row">
            <div class="col-md-12">
                <nav class="my-navbar">
                    <ul>
                        <li><a href="{!! url('/')!!}">КC-01(строковий)</a></li>
                        <li><a href="{!! url('/warep') !!}">WAREP</a></li>
                        <li><a href="#">КC-01(добовий)</a></li>
                        <li><a href="#">Дані за місяць</a></li>
                        <li><a href="#">CLIMAT(Сер. місячні дані)</a></li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="panel-body">
            <div class="row">

                <div class="col-md-3">
                    <form id="filters" action="{!! url('/warep') !!}" method="POST">
                        <div id="dates" class="form-group">
                            <label>Виберіть період:</label>
                            <div class="row">
                                <div class="col-sm-1" style="margin-top: 10px;"> З:</div>
                                <div class="col-sm-11">
                                    <input type='date' class='form-control' name='dateFrom' style="margin-top: 5px;">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-1" style="margin-top: 15px;"> По:</div>
                                <div class="col-sm-11">
                                    <input type='date' class='form-control' name='dateTo' style="margin-top: 10px;">
                                </div>
                            </div>
                        </div>

                        <div id="storm" class="form-group">
                            <div class="row">
                                <div class="col-sm-5" style="margin-top: 6px;"> Виберіть категорію:</div>
                                <div class="col-sm-7">
                                    <select id="storm-select" class="form-control" name="storm" size="1">
                                        <option value="All">STORM & AVIA</option>
                                        <option value="1">STORM</option>
                                        <option value="2">AVIA</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        @if(isset($appearances) && is_object($appearances))
                            <div id="appearance" class="form-group">
                                <div class="row">
                                    <div class="col-sm-5" style="margin-top: 6px;"> Виберіть явище:</div>
                                    <div class="col-sm-7">
                                        <select id="appearance-select" class="form-control" name="appearance" size="1">
                                            <option value="All">Всі явища</option>
                                            @foreach($appearances as $appearance)
                                                <option value="{{ $appearance->CODE_WAREP }}">{{ $appearance->CWCW }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(isset($regions) && is_object($regions))
                            <div id="regions" class="form-group">
                                <label>Виберіть назву області:</label>
                                <div class="row">
                                    <div class="col-sm-12 regions-wrapper">
                                        <select id="regions-select" class="form-control" name="regionName[]" size="6"
                                                multiple>
                                            @foreach($regions as $key => $region)
                                                @if($key == 0)
                                                    @continue
                                                @else
                                                    @if(isset($selectedRegions))
                                                        @if(in_array($region->OBL_ID, $selectedRegions))
                                                            @php
                                                                echo "<option selected value=\"{$region->OBL_ID}\">{$region->NAME_OBL}</option>"?>
                                                            @endphp
                                                        @else
                                                            @php
                                                                echo "<option value=\"{$region->OBL_ID}\">{$region->NAME_OBL}</option>"?>
                                                            @endphp
                                                        @endif
                                                    @else
                                                        @php
                                                            echo "<option value=\"{$region->OBL_ID}\">{$region->NAME_OBL}</option>"?>
                                                        @endphp
                                                    @endif
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(isset($stations) && is_object($stations))
                            <div id="stations" class="form-group">
                                <label>Виберіть назву станції:</label>
                                <div class="row">
                                    <div class="col-sm-12 sections-wrapper">
                                        <select id="stations-select" class="form-control" name="stationName[]" size="8"
                                                multiple>
                                            @foreach($stations as $station)
                                                @if(isset($selectedStations))
                                                    @if(in_array($station->IND_ST, $selectedStations))
                                                        @php
                                                            echo "<option selected value=\"{$station->IND_ST}\">{$station->NAME_ST}</option>"
                                                        @endphp
                                                    @else
                                                        @php
                                                            echo "<option value=\"{$station->IND_ST}\">{$station->NAME_ST}</option>"
                                                        @endphp
                                                    @endif
                                                @else
                                                    @php
                                                        echo "<option value=\"{$station->IND_ST}\">{$station->NAME_ST}</option>"
                                                    @endphp
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 10px;">
                                    ОК
                                </button>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="float: right; margin-bottom: 10px;">
                                <a href="/export" id="export" class="btn btn-success" style="width: 100%">Create
                                    Excel</a>
                            </div>
                        </div>
                    </form>

                </div>

                <div class="col-md-9 main-content">
                    @if(isset($dataForTable) && is_object($dataForTable))
                        <div class="table-responsive">
                            <table id="warep" class="table table-condensed table-striped">
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
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <ul class="pagination">

                        </ul>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>
<script type="text/javascript" src="./js/filters.js"></script>
<script type="text/javascript" src="./js/ajaxRequests.js"></script>
