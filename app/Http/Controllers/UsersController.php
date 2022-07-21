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

use App\Models\User;
use App\Models\Friend;
use Illuminate\Http\Request;
use App\Models\ItemFavorites;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $totalUsers = User::all()->count();
        $search = $request->search ?? '';

        $users = User::where([
            ['username', 'LIKE', '%' . $search . '%'],
            ['banned', '=', false]
        ])->orderBy('updated_at', 'DESC')->paginate(10);

        return view('users.index')->with([
            'totalUsers' => $totalUsers,
            'users' => $users
        ]);
    }

    public function show($username)
    {
        $user = User::where('username', '=', $username)->firstOrFail();
        $achievements = $user->achievements();
        $favoriteItems = $user->favoriteItems();
        $friends = $user->friends()->take(6);

        if ($user->banned) {
            abort(404);
        }

        if (Auth::check()) {
            $friendsArray = [];

            foreach ($user->friends() as $friend) {
                $friendsArray[] = $friend->id;
            }

            $areFriends = in_array(Auth::user()->id, $friendsArray);
            $isPending = Friend::where('status', '=', 'pending')->where(function($query) use($user) {
                $query->where([['receiver_id', '=', $user->id], ['sender_id', '=', Auth::user()->id]])->orWhere([['receiver_id', '=', Auth::user()->id], ['sender_id', '=', $user->id]]);
            })->first();
        }

        return view('users.show')->with([
            'user' => $user,
            'areFriends' => $areFriends ?? false,
            'isPending' => $isPending ?? false,
            'achievements' => $achievements,
            'favoriteItems' => $favoriteItems,
            'friends' => $friends
        ]);
    }

    public function friends($username)
    {
        $user = User::where('username', '=', $username)->firstOrFail();
        $friendsArray = [];

        foreach ($user->friends() as $friend) {
            $friendsArray[] = $friend->id;
        }

        $friends = User::whereIn('id', $friendsArray)->paginate(24);

        return view('users.friends')->with([
            'user' => $user,
            'friends' => $friends
        ]);
    }
}
