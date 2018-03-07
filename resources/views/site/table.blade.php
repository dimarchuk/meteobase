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
                            //echo "<td>{$item->$codeCategory}</td>"
                    @endphp

                    @if($codeCategory == 'A')
                        @php
                            echo "<td style = \"min-width: 200px;\">{$item->$codeCategory}</td>"
                        @endphp
                    @else
                        @php
                            echo "<td>{$item->$codeCategory}</td>"
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