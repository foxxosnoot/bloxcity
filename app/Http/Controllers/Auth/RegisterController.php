<?php
/**
 * MIT License
 *
 * Copyright (c) 2021-2022 FoxxoSnoot
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UsernameHistory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.register');
    }

    public function authenticate(Request $request)
    {
        if (!settings('registration_enabled')) {
            abort(404);
        }

        $this->validate(request(), [
            'username' => ['required', 'min:3', 'max:20', 'regex:/\\A[a-z\\d]+(?:[.-][a-z\\d]+)*\\z/i', 'unique:users'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:6', 'max:255'],
            'accept_tos' => ['required']
        ], [
            'username.unique' => 'Username has already been taken.',
            'accept_tos.required' => 'You must agree to the Terms of Service.'
        ]);

        if (UsernameHistory::where('username', '=', $request->username)->exists()) {
            return back()->withErrors(['Username has already been taken.']);
        }

        if (User::where('ip', '=', $request->ip())->count() > 8) {
            return back()->withErrors(['You have reached the limit of 8 accounts.']);
        }

        if (isProfanity($request->username)) {
            return back()->withErrors(['This username is not allowed.']);
        }

        $password = bcrypt($request->password);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $password,
            'next_currency_payout' => Carbon::tomorrow(),
            'ip' => $request->ip()
        ]);

        Auth::login($user);

        return redirect()->route('home.dashboard')->with('success_message', 'You have successfully created your account!');
    }
}
