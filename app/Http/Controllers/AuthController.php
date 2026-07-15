<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (session()->has('user')) {
            return redirect()->intended('dashboard');
        }
        $datas['headerlinks'] = HeaderLinks();
        $datas['scriptlinks'] = ScriptLinks();
        return view('login', compact('datas'));
    }

    public function CheckUserlogin(Request $request)
    {
        // dd($request->all());
        $user = User::where('username', $request->username)->whereNull('deleted_at')->first();
        if (!$user) {
            return redirect('/')->with('error', 'Invalid Username');
        }

        if ($user->validatePassword($request->login['password'])) {
            Session::put('user', $user);
            return redirect()->intended('dashboard');
        } else {
            return redirect('/')->with('error', 'Invalid Password');
        }
    }

    public function logout(Request $request)
    {
        session()->invalidate();
        return redirect('/');
    }
}
