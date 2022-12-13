<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class ChangePasswordController extends Controller
{
    public function index(Request $request)
    {
        Session::flash('password_changed', false);
        $request->validate([
            'password_old' => ['required', new MatchOldPassword],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::find(Auth::user()->id)->update(['password' => Hash::make($request->password)]);
        return Redirect::back()->with(['message' => 'đổi mật khẩu thành công', 'password_changed' => true]);
    }
}
