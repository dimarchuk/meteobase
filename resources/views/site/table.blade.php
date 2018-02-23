<div class="table-responsive">
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            @foreach($categories as $category)
                @php
                    echo "<th>{$category->short_col_name}</th>"
                @endphp
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($dataFromSrok as $item)
            <tr>
                @foreach($categories as $category)
                    @php
                        $codeCategory = $category->code_col_name;
                            echo "<td>{$item->$codeCategory}</td>"
                    @endphp
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<ul class="pagination">

    @if ($currentPage != 1)
        @php
            $pervpage = '<li><a href="http://127.0.0.1:8000?page=1">«</a></li>';
        @endphp
    @else
        @php
            $pervpage = '';
        @endphp
    @endif
    @if($currentPage != $countPages)
        @php
            $nextpage = '<li><a href="http://127.0.0.1:8000?page='. $countPages .'">»</a></li>';
        @endphp
    @else
        @php
            $nextpage = '';
        @endphp
    @endif
    @if($currentPage - 2 > 0)
        @php
            $page2left = '<li><a href="http://127.0.0.1:8000?page='. ($currentPage - 2) .'">'. ($currentPage - 2) .'</a></li>';
        @endphp
    @else
        @php
            $page2left = '';
        @endphp
    @endif
    @if($currentPage - 1 > 0)
        @php
            $page1left = '<li><a href="http://127.0.0.1:8000?page='. ($currentPage - 1) .'">'. ($currentPage - 1) .'</a></li>';
        @endphp
    @else
        @php
            $page1left = '';
        @endphp
    @endif
    @if($currentPage + 1 <= $countPages)
        @php
            $page1right = '<li><a href="http://127.0.0.1:8000?page='. ($currentPage + 1) .'">'. ($currentPage + 1) .'</a></li>';
        @endphp
    @else
        @php
            $page1right = '';
        @endphp
    @endif
    @if($currentPage + 2 <= $countPages)
        @php
            $page2right = '<li><a href="http://127.0.0.1:8000?page='. ($currentPage + 2) .'">'. ($currentPage + 2) .'</a></li>';
        @endphp
    @else
        @php
            $page2right = '';
        @endphp
    @endif

    @php

        echo $pervpage.$page2left.$page1left. '<li class="active"><a href="http://127.0.0.1:8000?page='. ($currentPage) .'">'. ($currentPage) .'</a></li>' .$page1right.$page2right.$nextpage;
    @endphp
</ul>

