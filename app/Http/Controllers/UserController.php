<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return view('user.index');
    }

    public function getUsers()
    {
        $users = User::orderBy('updated_at','desc')
                    ->get();

        return response()->json([
            'users' => $users,
        ]);
    }

    public function editUser(Request $request)
    {
        //return $request->all();

        //check validation
        $request->validate([
            'userId' => 'required|numeric',
            'userRole' => 'required',
            'isChecked' => 'required',
            // 'verifiedAt' => 'required',
        ]);

        $user = User::Find($request->userId);
        if ($user->role == 'admin'){
            return response()->json([
                'modalError' => 'admin cannot change by yourself.',
            ]);
        }

        if ($request->userRole == 'admin'){
            return response()->json([
                'modalError' => 'admin already exist.',
            ]);
        }

        $user->role = $request->userRole;

        if ($request->verifiedAt){
            $user->email_verified_at = $request->verifiedAt;
        }

        if ($request->isChecked == 'yes'){
            $user->email_verified_at = null;
        }

        $user->save();

        return response()->json([
            'modalMessage' => 'completed to edit user.',
        ]);
    }
}
