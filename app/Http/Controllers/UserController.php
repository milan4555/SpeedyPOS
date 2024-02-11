<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    public function newEmployee(\Illuminate\Http\Request $request) {
        if ($request->all() == []) {
            return view('settings.newEmployee');
        }
        $baseUsername = $request['firstName'].$request['lastName'][0];
        $nameCount = DB::table('users')->where('username', '=', $baseUsername)->count();
        if ($nameCount == 1) {
            $baseUsername .= $nameCount+1;
        }

        $rights = UserRight::create([
            'isSuperior' => false,
            'canCreateProduct' => false,
            'canUpdateProduct' => false,
            'canDeleteProduct' => false,
        ]);

        User::create([
            'firstName' => $request['firstName'],
            'lastName' => $request['lastName'],
            'username' => $baseUsername,
            'password' => Hash::make('xX123456'),
            'phoneNumber' => $request['phoneNumber'],
            'position' => $request['position'],
            'rightsId' => $rights->rightsId
        ]);

        return Redirect::back();
    }
}
