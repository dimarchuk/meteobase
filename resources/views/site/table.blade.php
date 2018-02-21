<div class="table-responsive span3">
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
                    $cat = $category->code_col_name;
                        echo "<td>{$item->$cat}</td>"
                    @endphp
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{--@php--}}
    {{--echo $dataFromSrok->links();--}}
{{--@endphp--}}