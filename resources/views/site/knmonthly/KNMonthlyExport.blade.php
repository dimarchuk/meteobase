<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<table>
    <thead>
    <tr>
        @foreach($categories as $category)
            @foreach($selectedCategories as $selectedCategory)
                @if($category['code_col_name'] == $selectedCategory['code_col_name'])
                    @php
                        echo "<th>{$category['short_col_name']}</th>"
                    @endphp
                @endif
            @endforeach
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($dataForTable as $item)
        <tr>
            @foreach($selectedCategories as $selectedCategory)
                @php
                    $cat = $selectedCategory['code_col_name'];
                    echo "<td>{$item[$cat]}</td>"
                @endphp
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>