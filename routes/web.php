<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', ['uses' => 'Kode_knController@show', 'as' => 'home']);

Route::post('/', function () {
    if (isset($_POST['form_data'])) {
        $req = false; // изначально переменная для "ответа" - false
        parse_str($_POST['form_data'], $form_data); // разбираем строку запроса
        // Приведём полученную информацию в удобочитаемый вид
        ob_start();
        echo 'До обработки: ' . $_POST['form_data'];
        echo 'После обработки:';
        echo '<pre>';
        print_r($form_data);
        echo '</pre>';
        $req = ob_get_contents();
        ob_end_clean();
        echo json_encode($req); // вернем полученное в ответе
        exit;
    } else echo "data: {$_POST['form_data']}";
});
