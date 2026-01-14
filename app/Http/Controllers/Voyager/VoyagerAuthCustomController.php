<?php

namespace App\Http\Controllers\Voyager;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Http\Controllers\VoyagerAuthController as BaseVoyagerAuthController;
use App\Models\User;

class VoyagerAuthCustomController extends BaseVoyagerAuthController
{
    public function login()
    {
       
        // Show login form
        return parent::login();
    }

    public function postLogin(Request $request)
    {
        $this->validateLogin($request);

        // Find user by email or mobile
        $user = User::where('email', $request->email)
                    ->orWhere('mobile', $request->email)
                    ->first();

        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'These credentials do not match our records.']);
        }

        // Attempt login with the found user
        if (Auth::attempt(['email' => $user->email, 'password' => $request->password], $request->remember)) {
            return redirect()->intended(route('voyager.dashboard'));
        }

        return redirect()->back()->withErrors(['email' => 'These credentials do not match our records.']);
    }
}
