<div class="container-fluid">
    <div class="panel panel-defaul">
        <div class="row">
            <div class="col-md-12">
                <nav class="my-navbar">
                    <ul>
                        <li><a href="#">КC-01(строковий)</a></li>
                        <li><a href="#">Warep</a></li>
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
                    <form action="/" method="POST">
                        <div id="dates" class="form-group">
                            <label>Период:</label>
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

                        @if(isset($regions) && is_object($regions))
                            <div id="regions" class="form-group">
                                <label>Назва області:</label>
                                <div class="row">
                                    <div class="col-sm-12 regions-wrapper">
                                        <select id="regions-select" class="form-control" name="regionName[]" id="region-name" size="6"
                                                multiple>
                                            @foreach($regions as $key => $region)
                                                @if($key == 0)
                                                    @continue
                                                @else
                                                    @php
                                                        echo "<option value=\"{$region->OBL_ID}\">{$region->NAME_OBL}</option>"?>
                                                    @endphp
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(isset($stations) && is_object($stations))
                            <div id="stations" class="form-group">
                                <label>Назва станції:</label>
                                <div class="row">
                                    <div class="col-sm-12 sections-wrapper">
                                        <select id="stations-select" class="form-control" name="stationName[]" id="station-name" size="8"
                                                multiple>
                                            @foreach($stations as $station)
                                                @php
                                                    echo "<option value=\"{$station->IND_ST}\">{$station->NAME_ST}</option>"
                                                @endphp
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(isset($categories) && is_object($categories))
                            <div id="categories" class="form-group">
                                <label>Дані:</label>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <select id="categories-select" class="form-control" name="collumns[]" id="collumns" size="9" multiple>
                                            @foreach($categories as $category)
                                                @if($category->selekted_col == true)
                                                    @php
                                                        echo "<option selected value=\"{$category->code_col_name}\">{$category->col_name}</option>"
                                                    @endphp
                                                @else
                                                    @php
                                                        echo "<option value=\"{$category->code_col_name}\">{$category->col_name}</option>"
                                                    @endphp
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <button type="submit" class="btn btn-primary" style="width: 150px"> ОК</button>
                    </form>
                </div>

                <div class="col-md-9 main-content">
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped">
                            <thead>
                            <tr>
                                @foreach($categories as $category)
                                    @if($category->selekted_col == true)
                                        @php
                                            echo "<th>{$category->short_col_name}</th>"
                                        @endphp
                                    @endif
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($dataFromSrok as $item)
                                <tr>
                                    @foreach($selectedCategories as $selectedCategory)
                                        @php
                                            echo "<td>{$item->$selectedCategory}</td>"
                                        @endphp
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @php
                        echo $dataFromSrok->links();
                    @endphp
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>
<script type="text/javascript" src="./js/ajaxRequests.js"></script>
<script type="text/javascript" src="./js/filters.js"></script>
