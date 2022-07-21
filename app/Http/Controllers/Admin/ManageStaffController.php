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

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ManageStaffController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->power < 4) {
            abort(404);
        }

        if ($request->has('search')) {
            if (User::where('username', '=', $request->search)->exists()) {
                $user = User::where('username', '=', $request->search)->first();

                if ($user->id == 1) {
                    return back()->withErrors(['This is a system account!']);
                }

                if ($user->power >= Auth::user()->power && Auth::user()->id != 1) {
                    return back()->withErrors(['This user has the same rank as you!']);
                }
            }
        }

        return view('admin.manage-staff')->with([
            'user' => $user ?? null
        ]);
    }

    public function update(Request $request)
    {
        if (Auth::user()->power < 4) {
            abort(404);
        }

        if (!isset($request->user_id)) {
            abort(404);
        }

        if (!User::where('id', '=', $request->user_id)->exists()) {
            abort(404);
        }

        $user = User::where('id', '=', $request->user_id)->first();
        $rankLimit = 5;

        if ($user->id == 1) {
            return back()->withErrors(['This is a system account!']);
        }

        if ($user->power >= Auth::user()->power && Auth::user()->id != 1) {
            return abort(404);
        }

        $this->validate(request(), [
            'rank' => ['required', 'numeric']
        ]);

        if ($request->rank > $rankLimit || $request->rank >= Auth::user()->power) {
            return back()->withErrors(['Invalid rank.']);
        }

        $user->timestamps = false;
        $user->power = $request->rank;
        $user->save();

        return back()->with('success_message', 'User rank has been updated!');
    }
}
