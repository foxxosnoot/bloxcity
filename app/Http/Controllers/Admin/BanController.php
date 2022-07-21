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

use Carbon\Carbon;
use App\Models\Ban;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BanController extends Controller
{
    public function index($username)
    {
        if (Auth::user()->power < 2) {
            abort(404);
        }

        $user = User::where('username', '=', $username)->firstOrFail();

        if ($user->power >= Auth::user()->power) {
            return back()->withErrors(['You can not ban people with the same power as you.']);
        }

        if ($user->banned) {
            return back()->withErrors(['User is already banned.']);
        }

        return view('admin.ban')->with([
            'user' => $user
        ]);
    }

    public function store(Request $request)
    {
        if (Auth::user()->power < 2) {
            abort(404);
        }

        if (!isset($request->user_id)) {
            abort(404);
        }

        if (!User::where('id', '=', $request->user_id)->exists()) {
            abort(404);
        }

        $user = User::where('id', '=', $request->user_id)->first();
        $allowedLengths = ['warning', '12_hours', '1_day', '3_days', '7_days', '14_days', 'closed'];
        $allowedCategories = ['spam', 'profanity', 'sensitive_topics', 'offsite_links', 'harassment', 'discrimination', 'sexual_content', 'inappropriate_content', 'false_information', 'other'];

        if ($user->power >= Auth::user()->power || $user->banned) {
            return abort(404);
        }

        $this->validate(request(), [
            'category' => ['required'],
            'length' => ['required']
        ]);

        if (!in_array($request->length, $allowedLengths)) {
            return back()->withErrors(['Invalid length.']);
        }

        if (!in_array($request->category, $allowedCategories)) {
            return back()->withErrors(['Invalid category.']);
        }

        switch ($request->length) {
            case 'warning':
                $lengthSeconds = 1;
                break;
            case '12_hours':
                $lengthSeconds = 43200;
                break;
            case '1_day':
                $lengthSeconds = 86400;
                break;
            case '3_days':
                $lengthSeconds = 259200;
                break;
            case '7_days':
                $lengthSeconds = 604800;
                break;
            case '14_days':
                $lengthSeconds = 1209600;
                break;
            case 'closed':
                $lengthSeconds = 31536000;
                break;
        }

        $ban = Ban::create([
            'user_id' => $user->id,
            'mod_id' => Auth::user()->id,
            'category' => $request->category,
            'content' => '', // todo
            'note' => $request->note,
            'length' => $request->length,
            'banned_until' => Carbon::createFromTimestamp(time() + $lengthSeconds)->format('Y-m-d H:i:s')
        ]);

        $user->timestamps = false;
        $user->banned = true;
        $user->save();

        return redirect()->route('admin.users', ['search' => $user->username])->with('success_message', 'User has been banned!');
    }
}
