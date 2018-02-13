<!DOCTYPE html>
<html lang="uk">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css"
          integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <link href="./css/style.css" rel="stylesheet" type="text/css">
    <title>Meteo Base</title>
</head>
<body>
<header id="header">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <nav class="navbar">
                    <ul>
                        <li><a href="#">КН-01(строковий)</a></li>
                        <li><a href="#">Warep</a></li>
                        <li><a href="#">КH-01(добовий)</a></li>
                        <li><a href="#">Дані за місяць</a></li>
                        <li><a href="#">CLIMAT(Сер. місячні дані)</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>

<main id="content">
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

                    <div class="form-group">
                        <label>Назва області:</label>
                        <div class="row">
                            <div class="col-sm-12">
                                <select class="form-control" name="regionName[]" id="region-name" multiple>
                                    <option selected disabled hidden>Выберіть область</option>
                                    <option value="Область 1">Область 1</option>
                                    <option value="Область 2">Область 2</option>
                                    <option value="Область 3">Область 3</option>
                                    <option value="Область 4">Область 4</option>
                                </select>
                            </div>
                        </div>
                    </div>

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
</main>

</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> <!-- load jquery via CDN -->
<script src="./js/magic.js"></script>
<script src="./js/filters.js"></script>

</html>