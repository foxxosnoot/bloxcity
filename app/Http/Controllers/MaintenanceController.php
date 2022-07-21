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

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    public function index()
    {
        if (!settings('maintenance_enabled') || (settings('maintenance_enabled') && session()->has('maintenance_code'))) {
            return (Auth::check()) ? redirect()->route('dashboard') : redirect()->route('landing');
        }

        return view('maintenance.index');
    }

    public function authenticate(Request $request)
    {
        if (!settings('maintenance_enabled')) {
            abort(404);
        }

        if (session()->has('maintenance_code')) {
            return back()->withErrors(['Already authenticated.']);
        }

        if (!$request->has('passcode')) {
            return back()->withErrors(['Please provide a passcode.']);
        }

        if (!in_array($request->passcode, config('blox.maintenance_passcodes'))) {
            return back()->withErrors(['Invalid passcode.']);
        }

        session()->put('maintenance_code', $request->passcode);
        session()->save();

        return (Auth::check()) ? redirect()->route('dashboard') : redirect()->route('landing');
    }

    public function exit()
    {
        session()->forget('maintenance_code');

        return redirect()->route('maintenance');
    }
}
