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
    @yield('header')
</header>

<main id="content">
    @yield('content')
</main>
</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> <!-- load jquery via CDN -->
<script type="text/javascript" src="./js/filters.js"></script>
<script type="text/javascript" src="./js/magic.js"></script>

</html>