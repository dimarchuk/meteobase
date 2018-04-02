
<div class="container-fluid">
    <div class="panel panel-defaul">
        <div class="panel-body">

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped">
                            <thead>
                            <tr>
                                <th>№</th>
                                <th>Ім'я</th>
                                <th>Електронна адреса</th>
                                <th>Статус</th>
                                <th>В системі з:</th>
                            </tr>
                            </thead>
                            <tbody>
                            @for($i = 0; $i < count($users); $i++)
                                <tr>
                                    @if($users[$i]->admin != 0 || $users[$i]->admin != null)
                                        @php
                                            $users[$i]->admin = 'Адміністратор'
                                        @endphp
                                    @else
                                        @php
                                            $users[$i]->admin = "-"
                                        @endphp
                                    @endif
                                    @php

                                        $id = $i + 1;
                                        echo "<td>{$id}</td>
                                            <td>{$users[$i]->name}</td>
                                            <td>{$users[$i]->email}</td>
                                            <td>{$users[$i]->admin}</td>
                                            <td>{$users[$i]->created_at}</td>
                                            <td><a href=" . url('admin/edit/user/' . "{$users[$i]->id}") . " class=\"btn btn-primary btn-sm\">Редагувати</a>
                                            <a href=" . url('admin/delete/user/' . "{$users[$i]->id}") . " class=\"btn btn-danger btn-sm\">Видалити</a></td>"
                                    @endphp
                                </tr>
                            @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
