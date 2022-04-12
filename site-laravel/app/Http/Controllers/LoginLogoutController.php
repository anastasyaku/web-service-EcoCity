<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginLogoutController extends Controller
{
    function login(Request $request) {
        if ($request->isMethod('GET')) {
            if (Auth::check())
                return redirect("/dispatch");
            else
                return view("login");
        } else {
            $credentials = $request->only("name", "password");

            if (Auth::attempt($credentials)) {
                return redirect()->route("dispatch");
            } else {
                return view("login", ["failed" => true]);
            }
        }
    }

    function logout() {
        Auth::logout();
        return redirect("/");
    }
}
