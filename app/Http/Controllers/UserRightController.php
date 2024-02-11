<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UserRightController extends Controller
{
    public function getView() {
        $allUsers = DB::table('users')
            ->join('user_rights', 'users.rightsId', 'user_rights.rightsId')
            ->select('*')
            ->get()
            ->toArray();
        return view('settings.userRights', [
            'users' => $allUsers
        ]);
    }

    public function changeRight($rightsId, $optionName) {
        if ($optionName == 'isSuperior') {
            $row = UserRight::find($rightsId);
            if ($row->isSuperior) {
                $row->update([
                    'isSuperior' => false,
                    'canUpdateProduct' => false,
                    'canDeleteProduct' => false,
                    'canCreateProduct' => false
                ]);
            } else {
                $row->update([
                    'isSuperior' => true,
                    'canUpdateProduct' => true,
                    'canDeleteProduct' => true,
                    'canCreateProduct' => true
                ]);
            }
            return Redirect::back();
        }
        $row = UserRight::find($rightsId);
        if ($row->$optionName) {
            $row->update([$optionName => false]);
        } else {
            $row->update([$optionName => true]);
        }
        return Redirect::back();
    }
}
