<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserRight;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    public function __construct()
    {
        $this->middleware('guest');
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'phoneNumber' => ['required', 'integer'],
            'position' => ['required', 'string', 'max:255']
        ]);
    }
    public function create(array $data)
    {
        $baseUsername = $data['firstName'].$data['lastName'][0];
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
            'firstName' => $data['firstName'],
            'lastName' => $data['lastName'],
            'username' => $baseUsername,
            'password' => Hash::make('xX123456'),
            'phoneNumber' => $data['phoneNumber'],
            'position' => $data['position'],
            'rightsId' => $rights->rightsId
        ]);

        return Redirect::back();
    }

}
