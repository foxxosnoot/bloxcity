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
use App\Models\Friend;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FriendsController extends Controller
{
    public function index()
    {
        $friendRequests = Auth::user()->friendRequests();

        return view('account.friends')->with([
            'friendRequests' => $friendRequests
        ]);
    }

    public function update(Request $request)
    {
        $allowedActions = ['accept', 'decline', 'send', 'remove'];
        $user = User::where('id', '=', $request->user_id)->firstOrFail();

        if (!in_array($request->action, $allowedActions) || $user->id == Auth::user()->id) {
            abort(404);
        }

        if ($request->action == 'accept') {
            if (!Friend::where([['sender_id', '=', $user->id], ['receiver_id', '=', Auth::user()->id], ['status', '=', 'pending']])->exists()) {
                abort(404);
            }

            $friendRequest = Friend::where([['sender_id', '=', $user->id], ['receiver_id', '=', Auth::user()->id], ['status', '=', 'pending']])->first();
            $friendRequest->status = 'accepted';
            $friendRequest->save();

            return back()->with('success_message', 'Friend request from '. $friendRequest->creator->username .' has been accepted!');
        } else if ($request->action == 'decline') {
            if (!Friend::where([['sender_id', '=', $user->id], ['receiver_id', '=', Auth::user()->id], ['status', '=', 'pending']])->exists()) {
                abort(404);
            }

            $friendRequest = Friend::where([['sender_id', '=', $user->id], ['receiver_id', '=', Auth::user()->id], ['status', '=', 'pending']])->first();
            $friendRequest->delete();

            return back()->with('success_message', 'Friend request from '. $friendRequest->creator->username .' has been declined!');
        } else if ($request->action == 'send') {
            if (Friend::where([['sender_id', '=', $user->id], ['receiver_id', '=', Auth::user()->id], ['status', '=', 'pending']])->orWhere([['sender_id', '=', Auth::user()->id], ['receiver_id', '=', $user->id], ['status', '=', 'pending']])->exists()) {
                return back()->withErrors(['This user has already sent you a friend request.']);
            }

            if ($this->areFriends($user->id)) {
                return back()->withErrors(['You are already friends with this user.']);
            }

            if ($user->setting_friend != 'everyone') {
                return back()->withErrors(['This user does not accept friend requests.']);
            }

            $friendRequest = Friend::create([
                'receiver_id' => $user->id,
                'sender_id' => Auth::user()->id
            ]);

            return back()->with('success_message', 'You have sent a friend request to this user!');
        } else if ($request->action == 'remove') {
            if (!$this->areFriends($user->id)) {
                return back()->withErrors(['You are not friends with this user.']);
            }

            $friendRequest = Friend::where('status', '=', 'accepted')->where(function($query) use($user) {
                $query->where([['receiver_id', '=', $user->id], ['sender_id', '=', Auth::user()->id]])->orWhere([['receiver_id', '=', Auth::user()->id], ['sender_id', '=', $user->id]]);
            })->first();
            $friendRequest->delete();

            return back()->with('success_message', 'You have removed this user from your friends!');
        }
    }

    private function areFriends($userId)
    {
        $friendsArray = [];

        foreach (Auth::user()->friends() as $friend) {
            $friendsArray[] = $friend->id;
        }

        return in_array($userId, $friendsArray);
    }
}
