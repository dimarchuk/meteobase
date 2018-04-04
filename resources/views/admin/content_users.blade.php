<div class="container-fluid">
    <div class="panel panel-primary">
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
                                            $users[$i]->admin = "Гість"
                                        @endphp
                                    @endif
                                    @php

                                        $id = $i + 1;
                                        echo "<td>{$id}</td>
                                            <td>{$users[$i]->name}</td>
                                            <td>{$users[$i]->email}</td>
                                            <td>{$users[$i]->admin}</td>
                                            <td>{$users[$i]->created_at}</td>
                                            <td><a href=" . url('admin/edit/user/' . "{$users[$i]->id}") . " class=\"btn btn-primary btn-sm edit\" data-modal=\"pgl-modal\">Редагувати</a>
                                            <a href=" . url('admin/delete/user/' . "{$users[$i]->id}") . " class=\"btn btn-danger btn-sm\">Видалити</a></td>"
                                    @endphp
                                </tr>
                            @endfor
                            </tbody>
                        </table>
                    </div>
                    <a href="{!! url('register') !!}" class="btn btn-primary btn-sm">Зареєструвати нового
                        користувача</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="pgl-overlay"></div>
<div class="pgl-modal" id="pgl-modal"><a class="pgl-modal-close">x</a>
    <div class="grl-modal-body">
        <div class="pgl-modal-content">
            <div class="panel-body">

                <form method="POST" enctype="multipart/form-data"
                      action="">
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">ФіО:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="userName" value=""
                                   name='userName'>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label">Email:</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="userEmail"
                                   value=""
                                   name="userEmail">
                        </div>
                    </div>
                    <fieldset class="form-group">
                        <div class="row">
                            <label class="col-sm-2">Статус: </label>
                            <div class="col-sm-10">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gridRadios"
                                           id="gridRadios1" value="1">
                                    <label class="form-check-label" for="gridRadios1">
                                        Адміністратор
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gridRadios"
                                           id="gridRadios2"
                                           value="0">
                                    <label class="form-check-label" for="gridRadios2">
                                        Гість
                                    </label>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <div class="form-group row">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary form-submit">Зберегти</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


