<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use DB;

class UsersController extends Controller
{
    /**
     * @param Request $request
     * @param int $userId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function edit(Request $request, int $userId)
    {
        if ($request->isMethod('POST')) {
            parse_str($_POST['data'], $data);
            User::where('id', $userId)->update(['name' => $data['userName'], 'email' => $data['userEmail'], 'admin' => $data['gridRadios']]);
            return response()->json([
                'message' => 'success'
            ], 200);


        } else if ($request->isMethod('GET')) {
            $col = ['id', 'name', 'email', 'admin'];
            $selectedUser = User::select($col)->where('id', '=', $userId)->get();
            $user = $selectedUser[0];
            return response(json_encode($user), 200);
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
