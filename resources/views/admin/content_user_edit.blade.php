<?php
/**
 * @var object $user
 */
?>
<div class="container-fluid" style="height: 100%; display: flex;  justify-content: center; align-items: center;">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="panel panel-primary" style="width: 500px;">
                @if (isset($user) && is_object($user))
                    <div class="panel-heading">{{ $user->email }}</div>
                    <div class="panel-body">

                        <form method="POST" enctype="multipart/form-data"
                              action="{!! url('/admin/edit/user/' . $user->id) !!}">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-2 col-form-label">ФіО:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputEmail3" value="{{ $user->name }}"
                                           name='userName'>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword3" class="col-sm-2 col-form-label">Email:</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="inputPassword3"
                                           value="{{ $user->email }}"
                                           name="userEmail">
                                </div>
                            </div>
                            <fieldset class="form-group">
                                <div class="row">
                                    <label class="col-sm-2">Статус: </label>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            @if($user->admin == true)
                                                <input class="form-check-input" type="radio" name="gridRadios"
                                                       id="gridRadios1" value="1" checked>
                                            @else
                                                <input class="form-check-input" type="radio" name="gridRadios"
                                                       id="gridRadios1" value="1" checked>
                                            @endif
                                            <label class="form-check-label" for="gridRadios1">
                                                Адміністратор
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            @if($user->admin == false)
                                                <input class="form-check-input" type="radio" name="gridRadios"
                                                       id="gridRadios2"
                                                       value="0" checked>
                                            @else
                                                <input class="form-check-input" type="radio" name="gridRadios"
                                                       id="gridRadios2"
                                                       value="0">
                                            @endif
                                            <label class="form-check-label" for="gridRadios2">
                                                Гість
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Зберегти</button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
