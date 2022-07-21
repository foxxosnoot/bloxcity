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

use App\Models\Item;
use App\Models\User;
use App\Models\Report;
use App\Models\Comment;
use App\Models\ForumReply;
use App\Models\ForumThread;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index($type, $id)
    {
        $allowedTypes = ['user', 'item', 'forum-thread', 'forum-reply', 'item-comment'];

        if (!in_array($type, $allowedTypes)) {
            abort(404);
        }

        if ($type == 'user') {
            $content = User::where('username', '=', $id)->firstOrFail();
            $title = 'Report ' . $content->username;

            if ($content->banned) {
                abort(404);
            }
        } else if ($type == 'item') {
            $content = Item::where('id', '=', $id)->with(['creator'])->firstOrFail();
            $title = 'Report an item by ' . $content->creator->username;

            if (!$content->public_view) {
                abort(404);
            }
        } else if ($type == 'forum-thread') {
            $content = ForumThread::where('id', '=', $id)->with(['creator'])->firstOrFail();
            $title = 'Report a forum thread by ' . $content->creator->username;

            if ($content->deleted) {
                abort(404);
            }
        } else if ($type == 'forum-reply') {
            $content = ForumReply::where('id', '=', $id)->with(['creator'])->firstOrFail();
            $title = 'Report a forum reply by ' . $content->creator->username;

            if ($content->deleted) {
                abort(404);
            }
        } else if ($type == 'item-comment') {
            $content = Comment::where('id', '=', $id)->with(['creator'])->firstOrFail();
            $title = 'Report a comment by ' . $content->creator->username;

            if (!$content->item->public_view) {
                abort(404);
            }
        }

        if (($type == 'user' && $content->id == 1) || ($type != 'user' && $content->creator->id == 1)) {
            abort(404);
        }

        return view('report.index')->with([
            'type' => $type,
            'content' => $content,
            'title' => $title
        ]);
    }

    public function store(Request $request)
    {
        $allowedTypes = ['user', 'item', 'forum-thread', 'forum-reply', 'item-comment'];
        $allowedReasons = ['spam', 'profanity', 'sensitive_topics', 'offsite_links', 'harassment', 'discrimination', 'sexual_content', 'inappropriate_content', 'false_information', 'other'];

        if (!in_array($request->type, $allowedTypes) || !in_array($request->reason, $allowedReasons)) {
            abort(404);
        }

        if ($request->type == 'user') {
            $content = User::where('id', '=', $request->id)->firstOrFail();

            if ($content->banned) {
                abort(404);
            }
        } else if ($request->type == 'item') {
            $content = Item::where('id', '=', $request->id)->with(['creator'])->firstOrFail();

            if (!$content->public_view) {
                abort(404);
            }
        } else if ($request->type == 'forum-thread') {
            $content = ForumThread::where('id', '=', $request->id)->with(['creator'])->firstOrFail();

            if ($content->deleted) {
                abort(404);
            }
        } else if ($request->type == 'forum-reply') {
            $content = ForumReply::where('id', '=', $request->id)->with(['creator'])->firstOrFail();

            if ($content->deleted) {
                abort(404);
            }
        } else if ($request->type == 'item-comment') {
            $content = Comment::where('id', '=', $request->id)->with(['creator'])->firstOrFail();

            if (!$content->item->public_view) {
                abort(404);
            }
        }

        if (($request->type == 'user' && $content->id == 1) || ($request->type != 'user' && $content->creator->id == 1)) {
            abort(404);
        }

        if (Auth::user()->flooding()) {
            return back()->withErrors(['You are trying to do things too fast.']);
        }

        $report = Report::create([
            'user_id' => Auth::user()->id,
            'content_id' => $request->id,
            'type' => $request->type,
            'reason' => $request->reason,
            'comment' => $request->comment
        ]);

        Auth::user()->updateFlood();

        return redirect()->route('report.thank_you');
    }

    public function thankYou()
    {
        return view('report.thank-you');
    }
}
