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

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class InboxController extends Controller
{
    public function index(Request $request)
    {
        if ($request->sort == 'sent') {
            $messages = Message::where('sender_id', '=', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(15);
            $string = 'You do not have any sent messages.';
        } else if ($request->sort == 'history') {
            $messages = Message::where([['receiver_id', '=', Auth::user()->id], ['seen', '=', true]])->orderBy('created_at', 'DESC')->paginate(15);
            $string = 'You do not have any history.';
        } else {
            $request->sort = 'incoming';
            $messages = Message::where([['receiver_id', '=', Auth::user()->id], ['seen', '=', false]])->orderBy('created_at', 'DESC')->paginate(15);
            $string = 'You do not have any incoming messages.';
        }

        return view('account.inbox.index')->with([
            'messages' => $messages,
            'string' => $string
        ]);
    }

    public function show($id)
    {
        $message = Message::where([['id', '=', $id], ['receiver_id', '=', Auth::user()->id]])->orWhere([['id', '=', $id], ['sender_id', '=', Auth::user()->id]])->firstOrFail();

        if (!$message->seen && Auth::user()->id == $message->receiver_id) {
            $message->seen = true;
            $message->save();
        }

        return view('account.inbox.show')->with([
            'message' => $message
        ]);
    }

    public function compose($username)
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        if ($user->id == Auth::user()->id || $user->setting_message == 'no_one' || $user->banned) {
            abort(404);
        }

        return view('account.inbox.compose')->with([
            'user' => $user
        ]);
    }

    public function composeStore(Request $request)
    {
        $user = User::where('id', '=', $request->user_id)->firstOrFail();

        if (Auth::user()->flooding()) {
            return back()->withErrors(['You are trying to do things too fast.']);
        }

        if ($user->id == Auth::user()->id || $user->setting_message == 'no_one' || $user->banned) {
            abort(404);
        }

        $this->validate(request(), [
            'title' => ['required', 'max:50'],
            'body' => ['required', 'min:3', 'max:7500']
        ]);

        if (isProfanity($request->title) || isProfanity($request->body)) {
            return back()->withErrors(['One or more words in your message has triggered our profanity filter. Please update and try again.']);
        }

        $message = Message::create([
            'receiver_id' => $user->id,
            'sender_id' => Auth::user()->id,
            'title' => $request->title,
            'body' => $request->body
        ]);

        Auth::user()->updateFlood();

        return redirect()->route('account.inbox.show', ['id' => $message->id])->with('success_message', 'Message has been sent!');
    }

    public function reply($id)
    {
        $message = Message::where([['id', '=', $id], ['receiver_id', '=', Auth::user()->id]])->firstOrFail();

        return view('account.inbox.reply')->with([
            'message' => $message
        ]);
    }

    public function replyStore(Request $request)
    {
        $message = Message::where([['id', '=', $request->message_id], ['receiver_id', '=', Auth::user()->id]])->firstOrFail();

        if (Auth::user()->flooding()) {
            return back()->withErrors(['You are trying to do things too fast.']);
        }

        $this->validate(request(), [
            'body' => ['required', 'min:3', 'max:7500']
        ]);

        if (isProfanity($request->body)) {
            return back()->withErrors(['One or more words in your body triggered our profanity filter. Please update and try again.']);
        }

        $body = $request->body .'
        ------------------------------
        '. $message->body;

        $reply = Message::create([
            'receiver_id' => $message->sender_id,
            'sender_id' => Auth::user()->id,
            'title' => 'RE: '. $message->title,
            'body' => $body
        ]);

        Auth::user()->updateFlood();

        return redirect()->route('account.inbox.show', ['id' => $reply->id])->with('success_message', 'Message has been sent!');
    }
}
