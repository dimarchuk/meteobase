<?php

namespace App\Http\Controllers;

use App\User;
use \Illuminate\View\View;

/**
 * Class AdminController
 * @package App\Http\Controllers
 */
class AdminController extends Controller
{
    /**
     * @return View
     */
    public function show(): View
    {

        $users = User::all();
        return view('admin.users', $data = [
            'users' => $users
        ]);
    }
}
