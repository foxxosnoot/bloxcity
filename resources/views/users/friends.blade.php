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
    'pageTitle' => $user->username . '\'s Friends',
    'bodyClass' => 'user-friends-page',
    'gridFluid' => true
])

@section('content')
    <h5>{{ $user->username }}'s Friends</h5>
    <div class="container">
        <div class="grid-x grid-margin-x">
            @forelse ($friends as $friend)
                <div class="cell small-6 medium-2 user-friend mb-25">
                    <a href="{{ route('users.profile', ['username' => $friend->username]) }}">
                        <img class="user-friend-avatar" src="{{ storage($friend->avatar_url) }}">
                    </a>
                    <a href="{{ route('users.profile', ['username' => $friend->username]) }}" class="user-friend-username">
                        @if ($friend->online())
                            <div class="user-friend-status status-online" title="{{ $friend->username }} is online" data-tooltip></div>
                        @else
                            <div class="user-friend-status status-offline" title="{{ $friend->username }} is offline" data-tooltip></div>
                        @endif
                        {{ $friend->username }}
                    </a>
                </div>
            @empty
                <div class="auto cell">This user has no friends.</div>
            @endforelse
        </div>
        {{ $friends->onEachSide(1)->links('pagination.default') }}
    </div>
@endsection
