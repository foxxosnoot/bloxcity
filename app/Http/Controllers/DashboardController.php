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

use App\Models\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $friendIDs = [];

        foreach (Auth::user()->friends() as $friend) {
            $friendIDs[] = $friend->id;
        }

        $blogPosts = ['posts' => []];
        $statuses = Status::where([['creator_id', '!=', Auth::user()->id], ['content', '!=', null]])->whereIn('creator_id', $friendIDs)->orderBy('created_at', 'DESC')->take(10)->get();

        return view('home.dashboard')->with([
            'statuses' => $statuses,
            'blogPosts' => $blogPosts
        ]);
    }

    public function status(Request $request)
    {
        if (Auth::user()->flooding()) {
            return back()->withErrors(['You are trying to do things too fast.']);
        }

        $this->validate(request(), [
            'content' => ['max:124']
        ]);

        if (isProfanity($request->content)) {
            return back()->withErrors(['One or more words in your status triggered our profanity filter. Please update and try again.']);
        }

        $status = Status::create([
            'creator_id' => Auth::user()->id,
            'content' => $request->content
        ]);

        Auth::user()->updateFlood();

        return back()->with('success_message', 'Status has been posted!');
    }
}
