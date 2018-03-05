<div class="container-fluid">
    <div class="panel panel-defaul">
        <div class="row">
            <div class="col-md-12">
                <nav class="my-navbar">
                    <ul>
                        <li><a href="{{url('/')}}">КC-01(строковий)</a></li>
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
                    <form action="{!! url('/') !!}" method="POST">
                        <div id="dates" class="form-group">
                            <label>Період:</label>
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

                        <div id="srok" class="form-group">
                            <div class="row">
                                <div class="col-sm-1" style="margin-top: 6px;"> Срок:</div>
                                <div class="col-sm-11">
                                    <select id="srok-select" class="form-control" name="srok" size="1">
                                        <option value="All">Всі строки</option>
                                        <option value="0">0</option>
                                        <option value="3">3</option>
                                        <option value="6">6</option>
                                        <option value="9">9</option>
                                        <option value="12">12</option>
                                        <option value="15">15</option>
                                        <option value="18">18</option>
                                        <option value="21">21</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        @if(isset($regions) && is_object($regions))
                            <div id="regions" class="form-group">
                                <label>Назва області:</label>
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
                                <label>Назва станції:</label>
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

                        @if(isset($categories) && is_object($categories))
                            <div id="categories" class="form-group">
                                <label>Дані:</label>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <select id="categories-select" class="form-control" name="collumns[]" size="9"
                                                multiple>
                                            @foreach($categories as $category)
                                                @if(isset($selectedCategories))
                                                    @if(in_array($category->code_col_name, $selectedCategories))
                                                        @php
                                                            echo "<option selected value=\"{$category->code_col_name}\">{$category->col_name}</option>"
                                                        @endphp
                                                    @else
                                                        @php
                                                            echo "<option value=\"{$category->code_col_name}\">{$category->col_name}</option>"
                                                        @endphp
                                                    @endif
                                                @else
                                                    @if($category->selekted_col == true)
                                                        @php
                                                            echo "<option selected value=\"{$category->code_col_name}\">{$category->col_name}</option>"
                                                        @endphp
                                                    @else
                                                        @php
                                                            echo "<option value=\"{$category->code_col_name}\">{$category->col_name}</option>"
                                                        @endphp
                                                    @endif
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
                                    @if(isset($selectedCategories))
                                        @foreach($selectedCategories as $selectedCategory)
                                            @if($category->code_col_name == $selectedCategory)
                                                @php
                                                    echo "<th>{$category->short_col_name}</th>"
                                                @endphp
                                            @endif
                                        @endforeach
                                    @else
                                        @if($category->selekted_col == true)
                                            @php
                                                echo "<th>{$category->short_col_name}</th>"
                                            @endphp
                                        @endif
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
                    <ul class="pagination">
                        @php
                            echo $paginationLinks;
                        @endphp

                    </ul>
                </div>

            </div>
        </div>
    </div>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>
    <script type="text/javascript" src="./js/ajaxRequests.js"></script>
    <script type="text/javascript" src="./js/filters.js"></script>