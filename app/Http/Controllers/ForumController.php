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

use App\Models\ForumReply;
use App\Models\ForumTopic;
use App\Models\ForumThread;
use Illuminate\Http\Request;
use Genert\BBCode\Facades\BBCode;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    public function index()
    {
        $topics = ForumTopic::with(['lastThread', 'lastPoster'])->orderBy('id')->get();

        return view('forum.index')->with([
            'topics' => $topics
        ]);
    }

    public function myThreads()
    {
        if (!settings('forum_enabled')) {
            return redirect()->route('forum.index');
        }

        $threads = ForumThread::where('creator_id', '=', Auth::user()->id)->orderBy('updated_at', 'DESC')->with(['creator'])->paginate(15);

        return view('forum.my-threads')->with([
            'threads' => $threads
        ]);
    }

    public function search(Request $request)
    {
        if (!settings('forum_enabled')) {
            return redirect()->route('forum.index');
        }

        $search = $request->search ?? '';
        $topics = ForumTopic::get(['id', 'name']);

        if ($request->has('topic') && !ForumTopic::where('id', '=', $request->topic)->exists()) {
            return back()->withErrors(['This topic doesn\'t exist.']);
        }

        if (Auth::check() && Auth::user()->power > 1) {
            $threads = ForumThread::where([
                ['topic_id', '=', $request->topic],
                ['title', 'LIKE', '%' . $search . '%']
            ])->orderBy('updated_at', 'DESC')->with(['creator'])->paginate(15);
        } else {
            $threads = ForumThread::where([
                ['topic_id', '=', $request->topic],
                ['title', 'LIKE', '%' . $search . '%'],
                ['deleted', '=', false]
            ])->orderBy('updated_at', 'DESC')->with(['creator'])->paginate(15);
        }

        return view('forum.search')->with([
            'threads' => $threads,
            'topics' => $topics
        ]);
    }

    public function topic($id)
    {
        if (!settings('forum_enabled')) {
            return redirect()->route('forum.index');
        }

        $topic = ForumTopic::where('id', '=', $id)->firstOrFail();

        if (Auth::check() && Auth::user()->power > 1) {
            $threads = ForumThread::where('topic_id', '=', $topic->id)->orderBy('pinned', 'DESC')->orderBy('updated_at', 'DESC')->with(['creator', 'lastPoster'])->paginate(15);
        } else {
            $threads = ForumThread::where([['topic_id', '=', $topic->id], ['deleted', '=', false]])->orderBy('pinned', 'DESC')->orderBy('updated_at', 'DESC')->with(['creator', 'lastPoster'])->paginate(15);
        }

        return view('forum.topic')->with([
            'topic' => $topic,
            'threads' => $threads
        ]);
    }

    public function thread($id)
    {
        if (!settings('forum_enabled')) {
            return redirect()->route('forum.index');
        }

        $thread = ForumThread::where('id', '=', $id)->firstOrFail();

        if ($thread->creator->power > 1) {
            $thread->body = BBCode::only('bold')->convertToHtml($thread->body);
        }

        if (Auth::check() && Auth::user()->power > 1) {
            $replies = ForumReply::where('thread_id', '=', $id)->with(['creator'])->orderBy('id', 'ASC')->paginate(10);
        } else {
            if ($thread->deleted) {
                abort(404);
            }

            $replies = ForumReply::where([['thread_id', '=', $id], ['deleted', '=', false]])->with(['creator'])->orderBy('id', 'ASC')->paginate(10);
        }

        return view('forum.thread')->with([
            'thread' => $thread,
            'replies' => $replies
        ]);
    }

    public function create($id)
    {
        if (!settings('forum_enabled')) {
            return redirect()->route('forum.index');
        }

        $topic = ForumTopic::where('id', '=', $id)->firstOrFail();

        return view('forum.create')->with([
            'topic' => $topic
        ]);
    }

    public function createStore(Request $request)
    {
        if (!settings('forum_enabled')) {
            return redirect()->route('forum.index');
        }

        $topic = ForumTopic::where('id', '=', $request->topic_id)->firstOrFail();

        if (Auth::user()->flooding()) {
            return back()->withErrors(['You are trying to do things too fast.']);
        }

        if (time() < (strtotime(Auth::user()->created_at) + 84600)) {
            return back()->withErrors(['Your account must be at least one day old to forum.']);
        }


        $this->validate(request(), [
            'title' => ['required', 'max:50'],
            'body' => ['required', 'min:3', 'max:7500']
        ]);

        if (isProfanity($request->title) || isProfanity($request->body)) {
            return back()->withErrors(['One or more words in your post triggered our profanity filter. Please update and try again.']);
        }

        $thread = ForumThread::create([
            'topic_id' => $topic->id,
            'creator_id' => Auth::user()->id,
            'title' => $request->title,
            'body' => $request->body,
            'last_poster_id' => Auth::user()->id
        ]);

        Auth::user()->updateFlood();

        $topic->last_thread_id = $thread->id;
        $topic->last_poster_id = Auth::user()->id;
        $topic->save();

        return redirect()->route('forum.thread', ['id' => $thread->id])->with('success_message', 'Thread has been created!');
    }

    public function reply($id)
    {
        if (!settings('forum_enabled')) {
            return redirect()->route('forum.index');
        }

        $thread = ForumThread::where('id', '=', $id)->with(['topic'])->firstOrFail();

        if (Auth::user()->power < 2 && ($thread->locked || $thread->deleted)) {
            abort(404);
        }

        return view('forum.reply')->with([
            'thread' => $thread
        ]);
    }

    public function replyStore(Request $request)
    {
        if (!settings('forum_enabled')) {
            return redirect()->route('forum.index');
        }

        $thread = ForumThread::where('id', '=', $request->thread_id)->with(['topic'])->firstOrFail();

        if (Auth::user()->flooding()) {
            return back()->withErrors(['You are trying to do things too fast.']);
        }

        if (Auth::user()->power < 2 && ($thread->locked || $thread->deleted)) {
            abort(404);
        }

        if (time() < (strtotime(Auth::user()->created_at) + 84600)) {
            return back()->withErrors(['Your account must be at least one day old to forum.']);
        }

        $this->validate(request(), [
            'body' => ['required', 'min:3', 'max:7500']
        ]);

        if (isProfanity($request->body)) {
            return back()->withErrors(['One or more words in your post triggered our profanity filter. Please update and try again.']);
        }

        $reply = ForumReply::create([
            'thread_id' => $thread->id,
            'creator_id' => Auth::user()->id,
            'body' => $request->body
        ]);

        Auth::user()->updateFlood();

        $thread->last_reply_id = $reply->id;
        $thread->last_poster_id = Auth::user()->id;
        $thread->save();

        $thread->topic->last_thread_id = $thread->id;
        $thread->topic->last_poster_id = Auth::user()->id;
        $thread->topic->save();

        return redirect()->route('forum.thread', ['id' => $thread->id])->with('success_message', 'Reply has been created!');
    }

    public function quote($id)
    {
        if (!settings('forum_enabled')) {
            return redirect()->route('forum.index');
        }

        $reply = ForumReply::where('id', '=', $id)->with(['thread'])->firstOrFail();

        if (Auth::user()->power < 2 && ($reply->thread->locked || $reply->thread->deleted)) {
            abort(404);
        }

        return view('forum.quote')->with([
            'reply' => $reply
        ]);
    }

    public function quoteStore(Request $request)
    {
        if (!settings('forum_enabled')) {
            return redirect()->route('forum.index');
        }

        $reply = ForumReply::where('id', '=', $request->reply_id)->with(['thread'])->firstOrFail();

        if (Auth::user()->power < 1 && ($reply->thread->locked || $reply->thread->deleted)) {
            abort(404);
        }

        if (Auth::user()->flooding()) {
            return back()->withErrors(['You are trying to do things too fast.']);
        }

        $this->validate(request(), [
            'body' => ['required', 'min:3', 'max:7500']
        ]);

        if (isProfanity($request->body)) {
            return back()->withErrors(['One or more words in your post triggered our profanity filter. Please update and try again.']);
        }

        $reply = ForumReply::create([
            'thread_id' => $reply->thread->id,
            'creator_id' => Auth::user()->id,
            'body' => $request->body,
            'quote_id' => $reply->id
        ]);

        Auth::user()->updateFlood();

        $reply->thread->last_reply_id = $reply->id;
        $reply->thread->last_poster_id = Auth::user()->id;
        $reply->thread->save();

        $reply->thread->topic->last_thread_id = $reply->thread->id;
        $reply->thread->topic->last_poster_id = Auth::user()->id;
        $reply->thread->topic->save();

        return redirect()->route('forum.thread', ['id' => $reply->thread->id])->with('success_message', 'Reply has been created!');
    }

    public function edit($type, $id)
    {
        if (!settings('forum_enabled')) {
            return redirect()->route('forum.index');
        }

        $allowedTypes = ['thread', 'reply'];

        if (!in_array($type, $allowedTypes) || Auth::user()->power < 4) {
            abort(404);
        }

        if ($type == 'thread') {
            $post = ForumThread::where('id', '=', $id)->with(['topic'])->firstOrFail();
        } else if ($type == 'reply') {
            $post = ForumReply::where('id', '=', $id)->with(['thread'])->firstOrFail();
        }

        return view('forum.edit')->with([
            'type' => $type,
            'post' => $post
        ]);
    }

    public function editUpdate(Request $request)
    {
        if (!settings('forum_enabled')) {
            return redirect()->route('forum.index');
        }

        $allowedTypes = ['thread', 'reply'];

        if (!isset($request->id)) {
            abort(404);
        }

        if (!in_array($request->type, $allowedTypes) || Auth::user()->power < 4) {
            abort(404);
        }

        if ($request->type == 'thread') {
            $post = ForumThread::where('id', '=', $request->id)->firstOrFail();

            $this->validate(request(), [
                'title' => ['required', 'max:50'],
                'body' => ['required', 'min:3', 'max:7500']
            ]);

            $post->timestamps = false;
            $post->title = $request->title;
            $post->body = $request->body;
            $post->save();

            return redirect()->route('forum.thread', ['id' => $post->id])->with('success_message', 'Thread has been updated!');
        } else if ($request->type == 'reply') {
            $post = ForumReply::where('id', '=', $request->id)->firstOrFail();

            $this->validate(request(), [
                'body' => ['required', 'min:3', 'max:7500']
            ]);

            $post->body = $request->body;
            $post->save();

            return redirect()->route('forum.thread', ['id' => $post->thread_id])->with('success_message', 'Reply has been updated!');
        }
    }

    public function move($id)
    {
        if (!settings('forum_enabled')) {
            return redirect()->route('forum.index');
        }

        if (Auth::user()->power < 2) {
            abort(404);
        }

        $thread = ForumThread::where('id', '=', $id)->firstOrFail();
        $topics = ForumTopic::get(['id', 'name']);

        return view('forum.move')->with([
            'thread' => $thread,
            'topics' => $topics
        ]);
    }

    public function moveUpdate(Request $request)
    {
        if (!settings('forum_enabled')) {
            return redirect()->route('forum.index');
        }

        if (Auth::user()->power < 2) {
            abort(404);
        }

        $thread = ForumThread::where('id', '=', $request->thread_id)->firstOrFail();

        $this->validate(request(), [
            'topic' => ['required', 'numeric']
        ]);

        if (!ForumTopic::where('id', '=', $request->topic)->exists()) {
            return back()->withErrors(['This topic doesn\'t exist.']);
        }

        $thread->timestamps = false;
        $thread->topic_id = $request->topic;
        $thread->update();

        return redirect()->route('forum.thread', ['id' => $thread->id])->with('success_message', 'Thread has been moved!');
    }

    public function moderate(Request $request)
    {
        $allowedTypes = ['thread', 'reply'];

        if (!isset($request->id)) {
            abort(404);
        }

        if (!in_array($request->type, $allowedTypes) || Auth::user()->power < 2) {
            abort(404);
        }

        if ($request->type == 'thread') {
            $allowedActions = ['switch_delete', 'scrub_title', 'scrub_body', 'switch_lock', 'switch_pin'];
            $thread = ForumThread::where('id', '=', $request->id)->firstOrFail();
            $thread->timestamps = false;

            if (!in_array($request->action, $allowedActions)) {
                abort(404);
            }

            if ($request->action == 'switch_delete') {
                $thread->deleted = !$thread->deleted;
                $thread->save();

                $word = ($thread->deleted) ? 'deleted' : 'undeleted';

                return back()->with('success_message', 'Thread has been '. $word .' for regular users.');
            } else if ($request->action == 'scrub_title') {
                $thread->scrubTitle();

                return back()->with('success_message', 'Thread title has been scrubbed.');
            } else if ($request->action == 'scrub_body') {
                $thread->scrubBody();

                return back()->with('success_message', 'Thread body has been scrubbed.');
            } else if ($request->action == 'switch_lock') {
                $thread->locked = !$thread->locked;
                $thread->save();

                $word = ($thread->locked) ? 'locked' : 'locked';

                return back()->with('success_message', 'Thread has been '. $word .'.');
            } else if ($request->action == 'switch_pin') {
                $thread->pinned = !$thread->pinned;
                $thread->save();

                $word = ($thread->pinned) ? 'pinned' : 'unpinned';

                return back()->with('success_message', 'Thread has been '. $word .'.');
            }
        } else if ($request->type == 'reply') {
            $allowedActions = ['switch_delete', 'scrub'];
            $reply = ForumReply::where('id', '=', $request->id)->firstOrFail();
            $reply->timestamps = false;

            if (!in_array($request->action, $allowedActions)) {
                abort(404);
            }

            if ($request->action == 'switch_delete') {
                $reply->deleted = !$reply->deleted;
                $reply->save();

                $word = ($reply->deleted) ? 'deleted' : 'undeleted';

                return back()->with('success_message', 'Reply has been '. $word .' for regular users.');
            } else if ($request->action == 'scrub') {
                $reply->scrubBody();

                return back()->with('success_message', 'Reply has been scrubbed.');
            }
        }
    }
}
