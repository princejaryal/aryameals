<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function showLoginForm(Request $request)
    {
        if ($request->session()->has('admin_id')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $admin = Admin::where('email', $credentials['email'])->first();

        if (! $admin || ! password_verify($credentials['password'], $admin->password)) {
            return back()
                ->withErrors(['email' => 'Invalid admin credentials.'])
                ->withInput();
        }

        $request->session()->put('admin_id', $admin->id);

        return redirect()->route('admin.dashboard');
    }
}
