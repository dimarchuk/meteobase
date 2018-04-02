<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class UsersController extends Controller
{
    /**
     * @param Request $request
     * @param $userId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $userId)
    {
        if ($request->method() === 'GET') {
            return view('admin.user_edit');
        } else if ($request->method() === 'POST') {
            var_dump($request->method());
        }
    }

    /**
     * @param $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($userId)
    {
        DB::table('users')->where('id', '=', $userId)->delete();

        return redirect()->back();
    }
}
