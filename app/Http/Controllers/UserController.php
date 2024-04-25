<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRight;
use Dotenv\Validator;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\Concerns\Has;

class UserController extends Controller
{

    public function login(Request $request) {
        if ($request['username'] == null) {
            return \redirect()->back()->with('error', 'Adj meg egy felhasználónevet a bejelentkezéshez!');
        }
        $user = User::where('username', '=', $request['username'])->first();
        if ($user == null) {
            return redirect()->back()->with('error', 'Sikertelen bejelentkezés! Ilyen felhasználónév nem létezik!');
        }
        if (!Hash::check($request['password'], $user->password)) {
            return \redirect()->back()->with('error', 'Sikertelen bejelntkezés! Rossz felhasználónév és jelszó páros lett megadva!');
        }
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
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
           'firstName' => 'required|string',
           'lastName' => 'required|string',
           'phoneNumber' => 'required|integer',
           'position' => 'required|string'
        ]);
        if ($validator->fails()) {
            return \redirect()->back()->with('error', 'Sikertelen művelet! Hiányzó adatok voltak, vagy valamelyik adattag nem megfelően volt megadva!')->withInput();
        }
        $baseUsername = strtolower($request['firstName'].$request['lastName'][0]);
        $nameCount = DB::table('users')->where('username', '=', $baseUsername)->count();
        if ($nameCount == 1) {
            $baseUsername .= $nameCount+1;
        }

        $user = User::create([
            'firstName' => $request['firstName'],
            'lastName' => $request['lastName'],
            'username' => $baseUsername,
            'password' => Hash::make('xX123456'),
            'phoneNumber' => $request['phoneNumber'],
            'position' => $request['position'],
        ]);
        return Redirect::back()->with('success', 'Sikeresen felvetted az új alkalmazottat!<br>
                                                    A kapott felhasználónév: <b>'.$baseUsername.'</b>
                                                    <br>Alapértelmezett jelszó: <b>xX123456</b><br>
                                                    <b>Az első bejelentkezésnél kérlek változtatsd meg!</b>');
    }
    public function loadProfilePage() {
        return view('settings.profile', [
            'authInfo' => Auth::user()
        ]);
    }
    public function setNewPassword(Request $request) {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'oldPassword' => 'required',
            'newPassword' => 'required',
            'reNewPassword' => 'required'
        ]);
        if ($validator->fails()) {
            return \redirect()->back()->with('error', 'Sikertelen művelet! Valamelyik mező üresen maradt!');
        }
        if ($request['newPassword'] != $request['reNewPassword']) {
            return \redirect()->back()->with('error', 'Sikertelen művelet! A kettő jelszó nem egyezik meg!');
        }
        $user = Auth::user();
        $isSameOldPassword = Hash::check($request['oldPassword'], $user->getAuthPassword());
        if (!$isSameOldPassword) {
            return \redirect()->back()->with('error', 'Sikertelen művelet! Nem megfelelő régi jelszót adtál meg!');
        }
        $user->update(['password' => Hash::make($request['newPassword'])]);
        return redirect()->back()->with('success', 'Sikeres jelszóváltoztatás! Jegyezd meg jól, mivel csak te vagy egy admin tudja megváltoztatni!');
    }

    public function setDefaultPassword($employeeId) {
        $user = User::find($employeeId);
        if ($user == null) {
            return redirect()->back()->with('error', 'Sikeretelen művelet! Ez a felhasználó nem létezik!');
        }
        $newPassword = Str::password(8, true, true, false);
        $user->update(['password' => Hash::make($newPassword)]);

        return \redirect()->back()->with('success', 'Jelszó helyreállítás megtörtént!<br>Az új jelszó: <b>'.$newPassword.'</b><br>Kérlek bejelntkezés után egyből változtasd meg!');
    }

    public function userDelete($employeeId) {
        $user = User::find($employeeId);
        if ($user == null) {
            return redirect()->back()->with('error', 'Sikeretelen művelet! Ez a felhasználó már nem is létezik!');
        }
        $user->delete();

        return \redirect()->back()->with('success', 'Sikeres művelet! <b>'.$user->firstName.' '.$user->lastName.' ('.$user->username.')</b> nevű felhasználót sikeresen eltávolítottad!');
    }
}
