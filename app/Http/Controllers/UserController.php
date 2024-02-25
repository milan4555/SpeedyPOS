<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRight;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{

    public function login(Request $request) {
        $user = User::where('username', '=', $request['username'])->firstOrFail();
        Auth::login($user);
        return Redirect::to('/home');
    }

    public function logout() {
        Auth::logout();
        return Redirect::to('/');
    }
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

        $user = User::create([
            'firstName' => $request['firstName'],
            'lastName' => $request['lastName'],
            'username' => $baseUsername,
            'password' => Hash::make('xX123456'),
            'phoneNumber' => $request['phoneNumber'],
            'position' => $request['position'],
            'rightsId' => $rights->rightsId
        ]);

        Auth::login($user);
        return Redirect::back();
    }
}
