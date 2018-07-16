<div class="container-fluid">
    <div class="panel panel-defaul">
        <div class="row">
            <div class="col-md-12">
                <nav class="my-navbar">
                    <ul>
                        <li><a href="{!! url('/')!!}">КC-01(строковий)</a></li>
                        <li><a href="{!! url('/warep') !!}">WAREP</a></li>
                        <li><a href="{!! url('/kndaily') !!}">КC-01(добовий)</a></li>
                        <li><a href="{!! url('/knmonthly') !!}">Дані за місяць</a></li>
                        <li><a href="#">CLIMAT(Сер. місячні дані)</a></li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="panel-body">
            <div class="row">

                <div class="col-md-3">
                    <form id="filters" action="{!! url('/') !!}" method="POST">
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

                        <div id="srok" class="form-group">
                            <div class="row">
                                <div class="col-sm-5" style="margin-top: 6px;"> Виберіть срок:</div>
                                <div class="col-sm-7">
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

                        @if(isset($categories) && is_object($categories))
                            <div id="categories" class="form-group">
                                <label>Виберіть дані:</label>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <select id="categories-select" class="form-control" name="collumns[]" size="9"
                                                multiple>
                                            @foreach($categories as $category)
                                                @if(isset($selectedCategories))
                                                    @if($category->code_col_name == 'NAME_OBL' || $category->code_col_name == 'NAME_ST'
                                                    || $category->code_col_name == 'IND_ST'|| $category->code_col_name == 'DATE_CH'
                                                    || $category->code_col_name == 'SROK_CH')
                                                        @php
                                                            echo "<option disabled value=\"{$category->code_col_name}\">{$category->col_name}</option>"
                                                        @endphp
                                                    @else
                                                        @if(in_array($category->code_col_name, $selectedCategories))
                                                            @php
                                                                echo "<option selected value=\"{$category->code_col_name}\">{$category->col_name}</option>"
                                                            @endphp
                                                        @else
                                                            @php
                                                                echo "<option value=\"{$category->code_col_name}\">{$category->col_name}</option>"
                                                            @endphp
                                                        @endif
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
                                @if($errors->any())
                                    <h5 class="alert alert-danger">{{$errors->first()}}</h5>
                                @endif
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 10px;"> ОК</button>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="float: right; margin-bottom: 10px;">
                        <a href="/export" id="export" class="btn btn-success" style="width: 100%">Create Excel</a>
                        </div>
                        </div>
                    </form>

                </div>

                <div class="col-md-9 main-content">
                    <div class="table-responsive">
                        <table id="KN" class="table table-condensed table-striped">
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
                            @foreach($dataForTable as $item)
                                <tr>
                                    @foreach($selectedCategories as $selectedCategory)
                                        @if($selectedCategory == 'A' || $selectedCategory == 'WW' || $selectedCategory == 'W1'
                                        || $selectedCategory == 'W2'|| $selectedCategory == 'CL'|| $selectedCategory == 'CM'
                                        || $selectedCategory == 'CH'|| $selectedCategory == 'E')
                                            @php
                                                echo "<td style = \"min-width: 270px;\">{$item->$selectedCategory}</td>"
                                            @endphp
                                        @else
                                            @php
                                                echo "<td>{$item->$selectedCategory}</td>"
                                            @endphp
                                        @endif
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

    <div class="panel panel-default">
        <div class="row">
            <div class="col-md-4 group9-wrapper" style="margin-left: 14px;">
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="./js/filters.js"></script>
<script type="text/javascript" src="./js/ajaxRequests.js"></script>
