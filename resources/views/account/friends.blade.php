<!--
MIT License
Copyright (c) 2021-2022 FoxxoSnoot
Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:
The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
-->

@extends('master', [
    'pageTitle' => 'Friends',
    'bodyClass' => 'user-friends-page',
    'gridFluid' => true
])

@section('content')
    <h5>Friends</h5>
    <div class="container">
        <div class="grid-x grid-margin-x">
            @forelse ($friendRequests as $friendRequest)
                <div class="cell small-6 medium-2 user-friend mb-25">
                    <a href="{{ route('users.profile', ['username' => $friendRequest->creator->username]) }}">
                        <img class="user-friend-avatar" src="{{ storage($friendRequest->creator->avatar_url) }}">
                    </a>
                    <a href="{{ route('users.profile', ['username' => $friendRequest->creator->username]) }}" class="user-friend-username">
                        @if ($friendRequest->creator->online())
                            <div class="user-friend-status status-online" title="{{ $friendRequest->creator->username }} is online" data-tooltip></div>
                        @else
                            <div class="user-friend-status status-offline" title="{{ $friendRequest->creator->username }} is offline" data-tooltip></div>
                        @endif
                        {{ $friendRequest->creator->username }}
                    </a>
                    <div class="push-10"></div>
                    <form action="{{ route('account.friends.update') }}" method="POST" style="display:inline-block;">
                        {{ csrf_field() }}
                        <input type="hidden" name="action" value="accept">
                        <input type="hidden" name="user_id" value="{{ $friendRequest->creator->id }}">
                        <button class="button button-green" type="submit">Accept</button>
                    </form>
                    <form action="{{ route('account.friends.update') }}" method="POST" style="display:inline-block;">
                        {{ csrf_field() }}
                        <input type="hidden" name="action" value="decline">
                        <input type="hidden" name="user_id" value="{{ $friendRequest->creator->id }}">
                        <button class="button button-red" type="submit">Decline</button>
                    </form>
                </div>
            @empty
                <div class="auto cell">You currently have no incoming friend requests.</div>
            @endforelse
        </div>
    </div>
@endsection
