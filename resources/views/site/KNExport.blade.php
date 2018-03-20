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
            @if(isset($selectedCategories))
                @foreach($selectedCategories as $selectedCategory)
                    @if($category->code_col_name == $selectedCategory)
                        @php
                            echo "<th>{$category->short_col_name}</th>"
                        @endphp
                    @endif
                @endforeach
            @endif
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($dataFromSrok as $item)
        <tr>
            @foreach($selectedCategories as $selectedCategory)
                @php
                    echo "<td>{$item->$selectedCategory}</td>";
                @endphp
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>