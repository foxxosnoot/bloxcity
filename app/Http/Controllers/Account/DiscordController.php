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

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DiscordController extends Controller
{
    public function index()
    {
        return view('account.discord');
    }

    public function update(Request $request)
    {
        if (Auth::user()->flooding()) {
            return back()->withErrors(['You are trying to do things too fast.']);
        }

        if (!empty(Auth::user()->discord_id)) {
            $myU = Auth::user();
            $myU->discord_code = null;
            $myU->discord_id = null;
            $myU->save();

            Auth::user()->updateFlood();

            return back()->with('success_message', 'Discord account has been unlinked!');
        }

        if (!empty(Auth::user()->discord_code)) {
            return back()->withErrors(['You have already generated a code!']);
        }

        Auth::user()->generateDiscordCode();
        Auth::user()->updateFlood();

        return back()->with('success_message', 'Discord code has been generated!');
    }
}
