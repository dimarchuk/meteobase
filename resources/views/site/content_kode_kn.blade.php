<div class="container">
    <div class="row mt-5">
        <div class="col-md-4">
            <form action="/" method="POST">
                <div class="form-group">
                    <label>Период:</label>
                    <div class="row">
                        <div class="col-sm-1 mt-3"> З:</div>
                        <div class="col-sm-11">
                            <input type='date' class='form-control mt-1' name='dateFrom'>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-1 mt-4"> По:</div>
                        <div class="col-sm-11">
                            <input type='date' class='form-control mt-3' name='dateTo'>
                        </div>
                    </div>
                </div>

                @if(isset($regions) && is_object($regions))
                    <div class="form-group">
                        <label>Назва області:</label>
                        <div class="row">
                            <div class="col-sm-12">
                                <select class="form-control" name="regionName[]" id="region-name" multiple size="6">
                                    {{--<option selected disabled hidden>Выберіть область</option>--}}
                                    @foreach($regions as $key => $region)
                                        @if($key == 0)
                                            continue;
                                        @else
                                            <?="<option value=\"{$key}\">{$region->NAME_OBL}</option>"?>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="form-group">
                    <label>Назва станції:</label>
                    <div class="row">
                        <div class="col-sm-12">
                            <select class="form-control" name="stationName[]" id="station-name" multiple>
                                <option selected disabled hidden>Выберіть станцію</option>
                                <option value="Станція 1">Станція 1</option>
                                <option value="Станція 2">Станція 2</option>
                                <option value="Станція 3">Станція 3</option>
                                <option value="Станція 4">Станція 4</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Дані:</label>
                    <div class="row">
                        <div class="col-sm-12">
                            <select class="form-control" name="collumns[]" id="collumns" size="4" multiple>
                                <option selected disabled hidden>Выберіть дані</option>
                                <option value="IND_ST">Індекс станції</option>
                                <option value="NAME_ST">Назва станції</option>
                                <option value="XGEO">X коордтната</option>
                                <option value="YGEO">Y коордтната</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 150px"> ОК</button>
            </form>
        </div>
        <div class="col-md-8" style="border: 1px solid black; height: 200px">
            TABLE
        </div>
    </div>
</div>
