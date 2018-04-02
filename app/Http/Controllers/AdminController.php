<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use \Illuminate\View\View;
use Auth;

/**
 * Class AdminController
 * @package App\Http\Controllers
 */
class AdminController extends Controller
{
    /**
     * @param Request $request
     * @return View
     */
    public function show(Request $request): View
    {

        $users = User::all();
        return view('admin.users', $data = [
            'users' => $users
        ]);
    }
}
